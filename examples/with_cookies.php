<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 示例：处理 Cookies
 */

// 初始化 TLS 客户端
Client::init();

// 创建会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::SAFARI_16_0,
    'timeout' => 15000,
]);

try {
    // 发起第一个请求，设置 cookies
    echo "Setting cookies...\n";
    $response1 = $session->get('https://httpbin.org/cookies/set/session_id/abc123');
    
    // 查看当前会话中的 cookies
    $cookies = $session->cookies();
    echo "Current cookies:\n";
    print_r($cookies);
    
    // 发起第二个请求，使用 cookies
    echo "Making request with cookies...\n";
    $response2 = $session->get('https://httpbin.org/cookies');
    
    echo "Response with cookies:\n" . $response2->text() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}