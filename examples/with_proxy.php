<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

/**
 * 示例：使用代理服务器发起请求
 * 注意：需要替换为有效的代理地址
 */

// 初始化 TLS 客户端
Client::init();

// 创建会话
$session = new Session([
    'clientIdentifier' => ClientIdentifier::FIREFOX_132,
    'timeout' => 30000,
]);

try {
    // 发起 GET 请求（通过代理）
    $response = $session->get('https://httpbin.org/ip', [
        // 替换为有效的代理地址
//        'proxyUrl' => 'http://username:password@proxy-server:port',
//        'proxyUrl' => 'socsk5://username:password@proxy-server:port',
        'proxyUrl' => 'socks5://127.0.0.1:1081',
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