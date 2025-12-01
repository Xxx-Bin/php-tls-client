<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 示例：在同一个会话中发起多个请求（复用连接）
 */

// 初始化 TLS 客户端
Client::init();

// 创建会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::CHROME_124,
    'timeout' => 15000,
]);

try {
    // 第一个请求
    echo "Making first request...\n";
    $response1 = $session->get('https://httpbin.org/get');
    echo "First request status: " . $response1->status() . "\n";
    
    // 第二个请求（同一个会话）
    echo "Making second request...\n";
    $response2 = $session->get('https://httpbin.org/user-agent');
    echo "Second request status: " . $response2->status() . "\n";
    
    // 第三个 POST 请求
    echo "Making third (POST) request...\n";
    $response3 = $session->post('https://httpbin.org/post', [
        'body' => json_encode(['key' => 'value']),
        'headers' => [
            'Content-Type' => 'application/json'
        ]
    ]);
    echo "Third request status: " . $response3->status() . "\n";
    
    echo "All requests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}