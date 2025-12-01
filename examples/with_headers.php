<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 示例：带自定义头部的请求
 */

// 初始化 TLS 客户端
Client::init();

// 创建带有自定义头部的会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::CHROME_120,
    'timeout' => 30000,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Cache-Control' => 'no-cache',
    ]
]);

try {
    // 发起 GET 请求，同时添加额外的头部
    $response = $session->get('https://httpbin.org/headers', [
        'headers' => [
            'Authorization' => 'Bearer your-token-here',
            'X-Custom-Header' => 'CustomValue'
        ]
    ]);
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Response Headers:\n";
    print_r($response->headers());
    
    echo "Response Body:\n" . $response->text() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}