<?php

require_once __DIR__.'/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\Payload\CustomTlsClient;

/**
 * 示例：使用自定义 JA3 指纹
 */

// 初始化 TLS 客户端
Client::init();

// 创建自定义 TLS 客户端配置

// 创建带有自定义 JA3 指纹的会话
$session = new Session([
    'customTlsClient' => [
        'ja3string' => "771,4865-4866-4867-49195-49199-49196-49200-52393-52392-49171-49172-156-157-47-53,10-16-5-11-27-13-0-18-65037-51-65281-17613-45-43-23-35,4588-29-23-24,0",
        'h2Settings' => [
            'HEADER_TABLE_SIZE' => 65536,
            'MAX_CONCURRENT_STREAMS' => 1000,
            'INITIAL_WINDOW_SIZE' => 6291456,
            'MAX_HEADER_LIST_SIZE' => 262144,
        ],
        'h2SettingsOrder' => [
            "HEADER_TABLE_SIZE",
            "MAX_CONCURRENT_STREAMS",
            "INITIAL_WINDOW_SIZE",
            "MAX_HEADER_LIST_SIZE",
        ],
        'supportedSignatureAlgorithms' => [
            "ECDSAWithP256AndSHA256",
            "PSSWithSHA256",
            "PKCS1WithSHA256",
            "ECDSAWithP384AndSHA384",
            "PSSWithSHA384",
            "PKCS1WithSHA384",
            "PSSWithSHA512",
            "PKCS1WithSHA512",
        ],
        'alpnProtocols' => ["h2", "http/1.1"],
        'alpsProtocols' => ["h2"],
        'supportedVersions' => ["GREASE", "1.3", "1.2"],
        'keyShareCurves' => [ "P384"],
        'certCompressionAlgos' => ["brotli"],
        'pseudoHeaderOrder' => [":method", ":authority", ":scheme", ":path"],
        'connectionFlow' => 15663105,
        'headerOrder' => ["accept", "user-agent", "accept-encoding", "accept-language"],
// Some servers are unresponsive because of priorityFrames and headerPriority configurations.
//For example, httpbin.org may report an error. tls.peet.ws is normal
        'priorityFrames' => [
            [
                'streamID' => 3,
                'priorityParam' => [
                    'streamDep' => 1,
                    'exclusive' => true,
                    'weight' => 100,
                ],
            ],
        ],
        'headerPriority' => [
            'streamDep' => 1,
            'exclusive' => true,
            'weight' => 200,
        ],

    ],
    'headers' => [
        'accept' => "application/json,text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36",
        "accept-encoding" => "gzip, deflate, br",
        "accept-language" => "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7",

    ],

]);

try {
    // 发起 GET 请求

//    $url = 'https://httpbin.org/get';

    $url = 'https://tls.peet.ws/api/all';
    $response = $session->get($url,[]);

    echo "Status Code: ".$response->status()."\n";
    echo "Response Body:\n".$response->text()."\n";

;


} catch (Exception $e) {
    echo "Error: ".$e->getMessage()."\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}



