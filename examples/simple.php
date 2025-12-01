<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 简单示例：使用默认配置发起 GET 请求
 */

// 初始化 TLS 客户端
Client::init();

// 创建会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::CHROME_124,
    'timeout' => 15000,
]);

try {
    // 发起 GET 请求
    $response = $session->get('https://httpbin.org/get');
    
    echo "Status Code: " . $response->status() . "\n";
    echo "Response Body:\n" . $response->text() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // 关闭会话并销毁客户端
//    $session->close();
    Client::destroy();
}