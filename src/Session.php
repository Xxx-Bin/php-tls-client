<?php

namespace PHPTLSClient;

use PHPTLSClient\Payload\PayloadBuilder;

class Session
{
    private Cookies $jar;
    private string $sessionId;
    private array $config;

    public function __construct(array $config = [])
    {
        $this->jar = new Cookies();
        $this->sessionId = uniqid();
        $this->config = $config;
    }

    public function cookies(): array
    {
        return $this->jar->fetchAllCookies();
    }

    /**
     * Performs a GET request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the GET request to.
     * @param array $options - The options for the GET request.
     *
     * @return Response|null The response from the execute method.
     */
    public function get(string $url, array $options = []): ?Response
    {
        return $this->execute("GET", $url, $options);
    }

    /**
     * Performs a POST request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the POST request to.
     * @param array $options - The options for the POST request.
     *
     * @return Response|null The response from the execute method.
     */
    public function post(string $url, array $options = []): ?Response
    {
        return $this->execute("POST", $url, $options);
    }

    /**
     * Performs a PUT request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the PUT request to.
     * @param array $options - The options for the PUT request.
     *
     * @return Response|null The response from the execute method.
     */
    public function put(string $url, array $options = []): ?Response
    {
        return $this->execute("PUT", $url, $options);
    }

    /**
     * Performs a DELETE request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the DELETE request to.
     * @param array $options - The options for the DELETE request.
     *
     * @return Response|null The response from the execute method.
     */
    public function delete(string $url, array $options = []): ?Response
    {
        return $this->execute("DELETE", $url, $options);
    }

    /**
     * Performs a PATCH request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the PATCH request to.
     * @param array $options - The options for the PATCH request.
     *
     * @return Response|null The response from the execute method.
     */
    public function patch(string $url, array $options = []): ?Response
    {
        return $this->execute("PATCH", $url, $options);
    }

    /**
     * Performs a HEAD request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the HEAD request to.
     * @param array $options - The options for the HEAD request.
     *
     * @return Response|null The response from the execute method.
     */
    public function head(string $url, array $options = []): ?Response
    {
        return $this->execute("HEAD", $url, $options);
    }

    /**
     * Performs an OPTIONS request to the provided URL with the provided options.
     *
     * @param string $url - The URL to perform the OPTIONS request to.
     * @param array $options - The options for the OPTIONS request.
     *
     * @return Response|null The response from the execute method.
     */
    public function options(string $url, array $options = []): ?Response
    {
        return $this->execute("OPTIONS", $url, $options);
    }

    /**
     * Closes the current session.
     *
     * @return array|null The response from the destroySession function.
     */
    public function close(): ?array
    {
        $ffi = Client::getInstance()->getFFI();

        $payload = json_encode([
            'sessionId' => $this->sessionId,
        ]);

        $response = $ffi->destroySession($payload);
        if ($response) {
            $responseString = \FFI::string($response);
            $response_arr = json_decode($responseString,1);
            Client::getInstance()->freeMemory($response_arr['id']);
            return json_decode($responseString, true);
        }
        return null;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $options
     * @return Response|null
     */

    public function execute(string $method, string $url, array $options = []): ?Response
    {
        $ffi = Client::getInstance()->getFFI();
        
        $requestCookies = $this->jar->mergeCookies(
            isset($options['cookies']) ? $options['cookies'] : [],
            $url
        );

        // 合并配置，options 中的值优先，但 insecureSkipVerify 和 timeoutSeconds 除外
        $mergedPayload = array_merge(
            PayloadBuilder::DEFAULT_PAYLOAD,
            $this->config, $options, [
                'sessionId' => $this->sessionId,
                'requestUrl' => $url,
                'requestMethod' => $method,
                'requestCookies' => $requestCookies,
            ]);

        $requestPayloadString = json_encode($mergedPayload);
        $rawResponse = $ffi->request($requestPayloadString);
        
        if ($rawResponse) {
            $responseString = \FFI::string($rawResponse);
            $response = json_decode($responseString, true);
            
            $cookies = $this->jar->syncCookies(
                isset($response['cookies']) ? $response['cookies'] : [],
                $url
            );
            
            // Free memory asynchronously
            Client::getInstance()->freeMemory($response['id']);
            
            return new Response(array_merge($response, ['cookies' => $cookies]));
        }
        
        return null;
    }

    private function getDefaultHeaders(): array
    {
        return [
            'User-Agent' => 'tls-client/2.1.0',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Accept' => '*/*',
            'Connection' => 'keep-alive',
        ];
    }

    private function isByteRequest(array $headers): bool
    {
        // 检查是否为字节请求
        return false;
    }
}