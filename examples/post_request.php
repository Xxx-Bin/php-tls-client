<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 示例：POST 请求发送 JSON 数据
 */

// 初始化 TLS 客户端
Client::init();

// 创建会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::CHROME_131,
    'timeout' => 30000,
]);

try {
    // 准备要发送的数据
    $data = [
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
        'message' => 'Hello, this is a test message!'
    ];
    
    // 发起 POST 请求
    $response = $session->post('https://httpbin.org/post', [
        'headers' => [
            'Content-Type' => 'application/json',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ],
        'body' => json_encode($data)
    ]);
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Response Body:\n" . $response->text() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}