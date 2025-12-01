<?php

require_once __DIR__.'/../vendor/autoload.php';

use PHPTLSClient\Payload\PriorityParam;
use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\Payload\CustomTlsClient;
use PHPTLSClient\Payload\H2Config;


/**
 * 示例：使用自定义 JA3 指纹
 */

// 初始化 TLS 客户端
Client::init();

// 通过 h2fp 格式创建 H2 配置
$h2Config = (new H2Config())
    ->fromH2FP('1:65536;4:131072;5:16384|12517377|3:0:0:201,5:0:0:101,7:0:0:1,9:0:7:1,11:0:3:1,13:0:0:241|m,p,a,s ')
    ->setHeaderPriority((new PriorityParam())
        ->setStreamDep(1)
        ->setExclusive(true)
        ->setWeight(254))
;

// 创建自定义 TLS 客户端配置
$customTlsClient = (new CustomTlsClient())
    ->setJa3String("771,4865-4866-4867-49195-49199-49196-49200-52393-52392-49171-49172-156-157-47-53,10-16-5-11-27-13-0-18-65037-51-65281-17613-45-43-23-35,4588-29-23-24,0")
    ->setH2Config($h2Config)
    ->setSupportedSignatureAlgorithms([
        CustomTlsClient::SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256,
        CustomTlsClient::SIGNATURE_ALGO_PSS_WITH_SHA256,
        CustomTlsClient::SIGNATURE_ALGO_PKCS1_WITH_SHA256,
        CustomTlsClient::SIGNATURE_ALGO_ECDSA_WITH_P384_AND_SHA384,
        CustomTlsClient::SIGNATURE_ALGO_PSS_WITH_SHA384,
        CustomTlsClient::SIGNATURE_ALGO_PKCS1_WITH_SHA384,
        CustomTlsClient::SIGNATURE_ALGO_PSS_WITH_SHA512,
        CustomTlsClient::SIGNATURE_ALGO_PKCS1_WITH_SHA512,
    ])
    ->setSupportedVersions([
        CustomTlsClient::TLS_VERSION_GREASE,
        CustomTlsClient::TLS_VERSION_1_3,
        CustomTlsClient::TLS_VERSION_1_2,
    ])
    ->setKeyShareCurves([
        CustomTlsClient::KEY_SHARE_CURVE_GREASE,
        CustomTlsClient::KEY_SHARE_CURVE_X25519,
        CustomTlsClient::KEY_SHARE_CURVE_P256,
        CustomTlsClient::KEY_SHARE_CURVE_P384,

    ])
    ->setCertCompressionAlgos([CustomTlsClient::CERT_COMPRESSION_ALGO_BROTLI])
    ->setAlpnProtocols(["h2", "http/1.1"])
    ->setAlpsProtocols(["h2"])
    // 注意：连接流控制已通过 H2Config 设置，这里不再需要
    ->setHeaderOrder(["accept", "user-agent", "accept-encoding", "accept-language"]);

$customTlsClientData = $customTlsClient->build();
var_export($customTlsClientData);
$config = (new \PHPTLSClient\Payload\PayloadBuilder())
//    ->setTimeout(3000)
    ->setTimeoutSeconds(30)
    ->setFollowRedirects(true)
//    ->setWithRandomTLSExtensionOrder(true)
//    ->setForceHttp1(true)
    ->setWithDebug(true)
    ->setCustomTlsClient($customTlsClientData)
//    ->setCustomTlsClient(CustomTlsClient::createChrome124()->build())
//    ->setCustomTlsClient(CustomTlsClient::createFirefox132()->build())
//    ->setCustomTlsClient(CustomTlsClient::createSafari160()->build())
//    ->setTlsClientIdentifier(\PHPTLSClient\ClientIdentifier::CHROME_131)

    ->setHeaders([
        'accept' => "application/json,text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
        "user-agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36",
        "accept-encoding" => "gzip, deflate, br",
        "accept-language" => "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7",
    ])

    ->build();

// 创建带有自定义 JA3 指纹的会话
$session = new Session($config);

try {
    // 发起 GET 请求

    $url = 'https://tls.peet.ws/api/all';
    $response = $session->get($url,[
//        'tlsClientIdentifier'=>\PHPTLSClient\ClientIdentifier::CHROME_131,
//        'withRandomTLSExtensionOrder'=>true,
    ]);

    echo "Status Code: ".$response->status()."\n";
    echo "Response Body:\n".$response->text()."\n";
} catch (Exception $e) {
    echo "Error: ".$e->getMessage()."\n";
} finally {
    // 关闭会话并销毁客户端
    $session->close();
    Client::destroy();
}