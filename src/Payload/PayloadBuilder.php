<?php

namespace PHPTLSClient\Payload;

/**
 * PayloadBuilder 类用于构建符合 tls-client 规范的请求 payload
 */
class PayloadBuilder
{
    const DEFAULT_PAYLOAD = [
        // 必需字段
        'sessionId' => null,
        'followRedirects' => false,
        'forceHttp1' => false,
        'withDebug' => false,
        'catchPanics' => false,
        'headers' => null,
        'headerOrder' => null,
        'insecureSkipVerify' => false,
        'isByteRequest' => false,
        'isByteResponse' => false,
        'isRotatingProxy' => false,
        'proxyUrl' => null,
        'requestBody' => null,
        'requestCookies' => null,
        'requestHostOverride' => null,
        'requestMethod' => '',
        'requestUrl' => '',
        'timeoutMilliseconds' => 0,
        'withRandomTLSExtensionOrder' => false,
        'withoutCookieJar' => false,
        'withDefaultCookieJar' => false,

        // 可选字段
        'certificatePinningHosts' => null,
        'customTlsClient' => null,
        'transportOptions' => null,
        'defaultHeaders' => null,
        'connectHeaders' => null,
        'disableIPV6' => false,
        'disableIPV4' => false,
        'disableHttp3' => false,
        'localAddress' => null,
        'serverNameOverwrite' => '',
        'streamOutputBlockSize' => null,
        'streamOutputEOFSymbol' => null,
        'streamOutputPath' => null,
        'timeoutSeconds' => 0,
        'tlsClientIdentifier' => '',
        'h2Settings' => null,
    ];

    public array $payload = self::DEFAULT_PAYLOAD;

    public function __construct(?string $sessionId = null)
    {
        if(!empty($sessionId)){
            $this->payload['sessionId'] = $sessionId;
        }
    }

    /**
     * 设置请求 URL
     *
     * @param string $url
     * @return self
     */
    public function setUrl(string $url): self
    {
        $this->payload['requestUrl'] = $url;
        return $this;
    }

    /**
     * 设置请求方法
     *
     * @param string $method 可选值: GET, POST, PUT, DELETE, PATCH, HEAD, OPTIONS
     * @return self
     */
    public function setMethod(string $method): self
    {
        $this->payload['requestMethod'] = $method;
        return $this;
    }

    /**
     * 设置请求体
     *
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->payload['requestBody'] = $body;
        return $this;
    }

    /**
     * 设置请求头
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(?array $headers): self
    {
        $this->payload['headers'] = $headers;
        return $this;
    }

    /**
     * 添加单个请求头
     *
     * @param string $key
     * @param string $value
     * @return self
     */
    public function addHeader(string $key, string $value): self
    {
        $this->payload['headers'][$key] = $value;
        return $this;
    }

    /**
     * 设置超时时间（毫秒）
     *
     * @param int $milliseconds
     * @return self
     */
    public function setTimeout(int $milliseconds): self
    {
        $this->payload['timeoutMilliseconds'] = $milliseconds;
        return $this;
    }

    /**
     * 设置超时时间（秒）
     *
     * @param int $seconds
     * @return self
     */
    public function setTimeoutSeconds(int $seconds): self
    {
        $this->payload['timeoutSeconds'] = $seconds;
        return $this;
    }

    /**
     * 设置是否跟随重定向
     *
     * @param bool $follow
     * @return self
     */
    public function setFollowRedirects(bool $follow): self
    {
        $this->payload['followRedirects'] = $follow;
        return $this;
    }

    /**
     * 设置是否强制使用 HTTP/1.1
     *
     * @param bool $force
     * @return self
     */
    public function setForceHttp1(bool $force): self
    {
        $this->payload['forceHttp1'] = $force;
        return $this;
    }

    /**
     * 设置是否启用调试模式
     *
     * @param bool $debug
     * @return self
     */
    public function setWithDebug(bool $debug): self
    {
        $this->payload['withDebug'] = $debug;
        return $this;
    }

    /**
     * 设置是否跳过 SSL 证书验证
     *
     * @param bool $skip
     * @return self
     */
    public function setInsecureSkipVerify(bool $skip): self
    {
        $this->payload['insecureSkipVerify'] = $skip;
        return $this;
    }

    /**
     * 设置代理 URL
     *
     * @param string|null $proxyUrl 格式: http://user:pass@ip:port 或 http://ip:port
     * @return self
     */
    public function setProxyUrl(?string $proxyUrl): self
    {
        $this->payload['proxyUrl'] = $proxyUrl;
        return $this;
    }

    /**
     * 设置是否为轮换代理
     *
     * @param bool $rotating
     * @return self
     */
    public function setIsRotatingProxy(bool $rotating): self
    {
        $this->payload['isRotatingProxy'] = $rotating;
        return $this;
    }

    /**
     * 设置 TLS 客户端标识符
     *
     * @param string $identifier
     * @return self
     */
    public function setTlsClientIdentifier(string $identifier): self
    {
        $this->payload['tlsClientIdentifier'] = $identifier;
        return $this;
    }

    /**
     * 设置自定义 TLS 客户端配置
     *
     * @param array|CustomTlsClient|null $customTlsClient
     * @return self
     */
    public function setCustomTlsClient(array|CustomTlsClient|null $customTlsClient): self
    {
        if ($customTlsClient instanceof CustomTlsClient) {
            $this->payload['customTlsClient'] = $customTlsClient->build();
        } else {
            $this->payload['customTlsClient'] = $customTlsClient;
        }
        return $this;
    }

    /**
     * 设置是否禁用 HTTP/3
     *
     * @param bool $disable
     * @return self
     */
    public function setDisableHttp3(bool $disable): self
    {
        $this->payload['disableHttp3'] = $disable;
        return $this;
    }

    /**
     * 设置请求 Cookies
     *
     * @param array $cookies
     * @return self
     */
    public function setRequestCookies(array $cookies): self
    {
        $formattedCookies = [];
        foreach ($cookies as $name => $value) {
            $formattedCookies[] = ['name' => $name, 'value' => $value];
        }
        $this->payload['requestCookies'] = $formattedCookies;
        return $this;
    }

    /**
     * 设置连接头
     *
     * @param array|null $connectHeaders
     * @return self
     */
    public function setConnectHeaders(?array $connectHeaders): self
    {
        $this->payload['connectHeaders'] = $connectHeaders;
        return $this;
    }

    /**
     * 设置本地地址
     *
     * @param string|null $localAddress
     * @return self
     */
    public function setLocalAddress(?string $localAddress): self
    {
        $this->payload['localAddress'] = $localAddress;
        return $this;
    }

    /**
     * 设置服务器名称覆盖
     *
     * @param string $serverName
     * @return self
     */
    public function setServerNameOverwrite(string $serverName): self
    {
        $this->payload['serverNameOverwrite'] = $serverName;
        return $this;
    }

    /**
     * 设置是否禁用 IPv6
     *
     * @param bool $disable
     * @return self
     */
    public function setDisableIPV6(bool $disable): self
    {
        $this->payload['disableIPV6'] = $disable;
        return $this;
    }

    /**
     * 设置是否禁用 IPv4
     *
     * @param bool $disable
     * @return self
     */
    public function setDisableIPV4(bool $disable): self
    {
        $this->payload['disableIPV4'] = $disable;
        return $this;
    }

    /**
     * 设置传输选项
     *
     * @param array|TransportOptions|null $transportOptions
     * @return self
     */
    public function setTransportOptions(array|TransportOptions|null $transportOptions): self
    {
        if ($transportOptions instanceof TransportOptions) {
            $this->payload['transportOptions'] = $transportOptions->build();
        } else {
            $this->payload['transportOptions'] = $transportOptions;
        }
        return $this;
    }

    /**
     * 设置流输出块大小
     *
     * @param int|null $size
     * @return self
     */
    public function setStreamOutputBlockSize(?int $size): self
    {
        $this->payload['streamOutputBlockSize'] = $size;
        return $this;
    }

    /**
     * 设置流输出 EOF 符号
     *
     * @param string|null $symbol
     * @return self
     */
    public function setStreamOutputEOFSymbol(?string $symbol): self
    {
        $this->payload['streamOutputEOFSymbol'] = $symbol;
        return $this;
    }

    /**
     * 设置流输出路径
     *
     * @param string|null $path
     * @return self
     */
    public function setStreamOutputPath(?string $path): self
    {
        $this->payload['streamOutputPath'] = $path;
        return $this;
    }

    /**
     * 设置是否随机 TLS 扩展顺序
     *
     * @param bool $random
     * @return self
     */
    public function setWithRandomTLSExtensionOrder(bool $random): self
    {
        $this->payload['withRandomTLSExtensionOrder'] = $random;
        return $this;
    }

    /**
     * 设置请求主机覆盖
     *
     * @param string|null $host
     * @return self
     */
    public function setRequestHostOverride(?string $host): self
    {
        $this->payload['requestHostOverride'] = $host;
        return $this;
    }

    /**
     * 设置是否为字节响应
     *
     * @param bool $isByteResponse
     * @return self
     */
    public function setIsByteResponse(bool $isByteResponse): self
    {
        $this->payload['isByteResponse'] = $isByteResponse;
        return $this;
    }

    /**
     * 设置是否为字节请求
     *
     * @param bool $isByteRequest
     * @return self
     */
    public function setIsByteRequest(bool $isByteRequest): self
    {
        $this->payload['isByteRequest'] = $isByteRequest;
        return $this;
    }

    /**
     * 设置是否不使用 Cookie Jar
     *
     * @param bool $withoutCookieJar
     * @return self
     */
    public function setWithoutCookieJar(bool $withoutCookieJar): self
    {
        $this->payload['withoutCookieJar'] = $withoutCookieJar;
        return $this;
    }

    /**
     * 设置是否使用默认 Cookie Jar
     *
     * @param bool $withDefaultCookieJar
     * @return self
     */
    public function setWithDefaultCookieJar(bool $withDefaultCookieJar): self
    {
        $this->payload['withDefaultCookieJar'] = $withDefaultCookieJar;
        return $this;
    }

    /**
     * 设置是否捕获 panic
     *
     * @param bool $catchPanics
     * @return self
     */
    public function setCatchPanics(bool $catchPanics): self
    {
        $this->payload['catchPanics'] = $catchPanics;
        return $this;
    }

    /**
     * 设置请求头顺序
     *
     * @param array|null $headerOrder
     * @return self
     */
    public function setHeaderOrder(?array $headerOrder): self
    {
        $this->payload['headerOrder'] = $headerOrder;
        return $this;
    }

    /**
     * 设置 H2 Settings 配置
     *
     * @param array|H2Config|null $h2Settings
     * @return self
     */
    public function setH2Settings(array|H2Config|null $h2Settings): self
    {
        if ($h2Settings instanceof H2Config) {
            $this->payload['h2Settings'] = $h2Settings->build();
        } else {
            $this->payload['h2Settings'] = $h2Settings;
        }
        return $this;
    }

    /**
     * 获取构建完成的 payload
     *
     * @return array
     */
    public function build(): array
    {
        return $this->payload;
    }
}