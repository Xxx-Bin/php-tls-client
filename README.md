# PHP TLS Client

A PHP wrapper for the [tls-client](https://github.com/bogdanfinn/tls-client) library using PHP's FFI (Foreign Function Interface) extension.

## Description

This library allows you to make HTTP requests with customizable TLS fingerprints from PHP, using the power of the tls-client library through FFI. It helps bypass TLS fingerprinting techniques used by some websites to detect and block non-browser traffic.

The library provides a convenient way to simulate various browser fingerprints, allowing you to make requests that appear to come from popular browsers like Chrome, Firefox, and Safari. This makes it particularly useful for web scraping and automated testing scenarios where you need to avoid detection.

This project is based on [Sahil1337/node-tls-client](https://github.com/Sahil1337/node-tls-client), ported from Node.js to PHP. The primary improvement in this PHP version is the optimization of payload construction through dedicated builder classes.

## Features

### tls-client Features
- Make HTTP/1.1 and HTTP/2 requests with custom TLS fingerprints
- Support for various browser fingerprints (Chrome, Firefox, Safari, etc.)
- Custom JA3 fingerprinting
- Proxy support
- Cookie management
- Session persistence

### PHP Wrapper Features
- Optimized payload construction using builder patterns
- Direct h2_fp string configuration support

## Requirements


- PHP 7.4 or higher
- FFI extension enabled
- Internet connection (for automatic library download)

## Installation

### Via Composer (Recommended)

```bash
composer require xxx-bin/php-tls-client
```

### Manual Installation

1. Make sure you have PHP 7.4+ with FFI extension enabled
2. Clone or download this repository
3. Run `composer install` to install dependencies

The required tls-client shared library will be automatically downloaded on first use.

## Usage

```php
<?php

require_once 'vendor/autoload.php';

use PHPTLSClient\Session;
use PHPTLSClient\Client;
use PHPTLSClient\ClientIdentifier;

// Initialize the TLS client
// The required shared library will be automatically downloaded if not found
Client::init();

// Create a session with a specific browser fingerprint
$session = new Session([
    'tlsClientIdentifier' => ClientIdentifier::CHROME_124,
    'timeoutSeconds' => 30,
]);

try {
    // Make a GET request
    $response = $session->get('https://httpbin.org/get');
    
    echo "Status: " . $response->status() . "\n";
    echo "Body: " . $response->text() . "\n";
} finally {
    // Clean up
    $session->close();
    Client::destroy();
}
```

## Advanced Usage

### Custom TLS Configuration

For more advanced use cases, you can create custom TLS configurations using the builder pattern:

```php
use PHPTLSClient\Payload\CustomTlsClient;
use PHPTLSClient\Payload\H2Config;
use PHPTLSClient\Payload\PriorityParam;

// Create H2 configuration from h2fp format
$h2Config = (new H2Config())
    ->fromH2FP('1:65536;4:131072;5:16384|12517377|3:0:0:201,5:0:0:101,7:0:0:1,9:0:7:1,11:0:3:1,13:0:0:241|m,p,a,s ')
    ->setHeaderPriority((new PriorityParam())
        ->setStreamDep(1)
        ->setExclusive(true)
        ->setWeight(254));

// Create custom TLS client configuration
$customTlsClient = (new CustomTlsClient())
    ->setJa3String("771,4865-4866-4867-49195-49199-49196-49200-52393-52392-49171-49172-156-157-47-53,10-16-5-11-27-13-0-18-65037-51-65281-17613-45-43-23-35,4588-29-23-24,0")
    ->setH2Config($h2Config)
    ->setSupportedSignatureAlgorithms([
        CustomTlsClient::SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256,
        CustomTlsClient::SIGNATURE_ALGO_PSS_WITH_SHA256,
        // ... more algorithms
    ])
    ->setSupportedVersions([
        CustomTlsClient::TLS_VERSION_GREASE,
        CustomTlsClient::TLS_VERSION_1_3,
        CustomTlsClient::TLS_VERSION_1_2,
    ])
    ->setKeyShareCurves([
        CustomTlsClient::KEY_SHARE_CURVE_GREASE,
        CustomTlsClient::KEY_SHARE_CURVE_X25519,
        // ... more curves
    ])
    ->setCertCompressionAlgos([CustomTlsClient::CERT_COMPRESSION_ALGO_BROTLI])
    ->setAlpnProtocols(["h2", "http/1.1"])
    ->setAlpsProtocols(["h2"])
    ->setHeaderOrder(["accept", "user-agent", "accept-encoding", "accept-language"]);
```

## FFI Direct Usage

For advanced users who want to directly use FFI to call the tls-client library, check out the [FFI_simple.php](examples/FFI_simple.php) example. This shows how to directly interface with the C library using PHP's FFI extension.
you can check it out in more detail。[https://bogdanfinn.gitbook.io/open-source-oasis/shared-library/payload](https://bogdanfinn.gitbook.io/open-source-oasis/shared-library/payload)


## Automatic Library Download

This library automatically downloads the required tls-client shared library for your platform on first use:

- **Windows**: `tls-client-64.dll`
- **macOS**: `tls-client-arm64.dylib` or `tls-client-x86.dylib`
- **Linux**: `tls-client-x64.so` or `tls-client-arm64.so`

The library is downloaded from the official [tls-client releases](https://github.com/bogdanfinn/tls-client/releases) page and saved to your system's temporary directory.

You can monitor the download progress as it happens.

## Examples

Check out the [examples](examples/) directory for more detailed usage examples:

1. [Simple request](examples/simple.php) - Basic GET request
2. [With headers](examples/with_headers.php) - Request with custom headers
3. [POST request](examples/post_request.php) - Sending data with POST
4. [Using proxy](examples/with_proxy.php) - Making requests through a proxy
5. [Custom JA3](examples/custom_ja3.php) - Using a custom TLS fingerprint
6. [Custom JA3 with builder](examples/custom_ja3_by_builder.php) - Using a custom TLS fingerprint with the builder pattern
7. [Multiple requests](examples/multiple_requests.php) - Multiple requests in one session
8. [With cookies](examples/with_cookies.php) - Working with cookies
9. [FFI Simple](examples/FFI_simple.php) - Direct FFI usage example

## Session Options

The Session constructor accepts an array of options:

| Option | Type | Description |
|--------|------|-------------|
| `sessionId` | string | A unique identifier for the session |
| `headers` | array | Default headers to send with requests |
| `proxyUrl` | string | Proxy URL in format `http://user:pass@ip:port` |
| `tlsClientIdentifier` | string | Browser identifier (use ClientIdentifier constants) |
| `customTlsClient` | array | Custom TLS client configuration |
| `timeoutMilliseconds` | int | Request timeout in milliseconds |
| `timeoutSeconds` | int | Request timeout in seconds |
| `followRedirects` | bool | Follow HTTP redirects |
| `forceHttp1` | bool | Force HTTP/1.1 protocol |
| `withDebug` | bool | Enable debug mode |
| `insecureSkipVerify` | bool | Skip SSL certificate verification |
| `headerOrder` | array | Define the order of HTTP headers |
| `isByteRequest` | bool | Indicates if the request is a byte request |
| `isByteResponse` | bool | Indicates if the response is a byte response |
| `isRotatingProxy` | bool | Indicates if using a rotating proxy |
| `requestBody` | string | Request body content |
| `requestCookies` | array | Request cookies |
| `requestHostOverride` | string | Override the request host |
| `withRandomTLSExtensionOrder` | bool | Randomize TLS extension order |
| `withoutCookieJar` | bool | Disable cookie jar functionality |
| `withDefaultCookieJar` | bool | Use default cookie jar |
| `catchPanics` | bool | Catch panics during request |
| `certificatePinningHosts` | array | Hosts for certificate pinning |
| `transportOptions` | array | Transport layer options |
| `defaultHeaders` | array | Default headers |
| `connectHeaders` | array | Connection headers |
| `disableIPV6` | bool | Disable IPv6 |
| `disableIPV4` | bool | Disable IPv4 |
| `disableHttp3` | bool | Disable HTTP/3 |
| `localAddress` | string | Local address to bind to |
| `serverNameOverwrite` | string | Overwrite server name |
| `streamOutputBlockSize` | int | Stream output block size |
| `streamOutputEOFSymbol` | string | Stream output EOF symbol |
| `streamOutputPath` | string | Stream output path |
| `h2Settings` | array | HTTP/2 settings configuration |

## Request Options

Each request method (get, post, put, etc.) accepts an options array:

| Option | Type | Description |
|--------|------|-------------|
| `headers` | array | Request headers |
| `body` | string | Request body |
| `followRedirects` | bool | Follow redirects |
| `proxyUrl` | string | Proxy URL for this request |
| `cookies` | array | Cookies to send with the request |

Commonly used options:

| Option | Type | Description |
|--------|------|-------------|
| `tlsClientIdentifier` | string | Browser identifier for this request |
| `customTlsClient` | array | Custom TLS client configuration for this request |
| `timeoutMilliseconds` | int | Request timeout in milliseconds |
| `timeoutSeconds` | int | Request timeout in seconds |
| `forceHttp1` | bool | Force HTTP/1.1 protocol |
| `withDebug` | bool | Enable debug mode |
| `insecureSkipVerify` | bool | Skip SSL certificate verification |
| `headerOrder` | array | Define the order of HTTP headers |
| `isByteRequest` | bool | Indicates if the request is a byte request |
| `isByteResponse` | bool | Indicates if the response is a byte response |
| `isRotatingProxy` | bool | Indicates if using a rotating proxy |
| `requestHostOverride` | string | Override the request host |
| `withRandomTLSExtensionOrder` | bool | Randomize TLS extension order |
| `withoutCookieJar` | bool | Disable cookie jar functionality |
| `withDefaultCookieJar` | bool | Use default cookie jar |
| `catchPanics` | bool | Catch panics during request |
| `certificatePinningHosts` | array | Hosts for certificate pinning |
| `transportOptions` | array | Transport layer options |
| `defaultHeaders` | array | Default headers |
| `connectHeaders` | array | Connection headers |
| `disableIPV6` | bool | Disable IPv6 |
| `disableIPV4` | bool | Disable IPv4 |
| `disableHttp3` | bool | Disable HTTP/3 |
| `localAddress` | string | Local address to bind to |
| `serverNameOverwrite` | string | Overwrite server name |
| `streamOutputBlockSize` | int | Stream output block size |
| `streamOutputEOFSymbol` | string | Stream output EOF symbol |
| `streamOutputPath` | string | Stream output path |
| `h2Settings` | array | HTTP/2 settings configuration |

## Response Methods

The response object has the following methods:

| Method | Description |
|--------|-------------|
| `text()` | Get response body as text |
| `json()` | Get response body as JSON (parsed array) |
| `status()` | Get HTTP status code |
| `headers()` | Get response headers |
| `cookies()` | Get response cookies |
| `url()` | Get the final URL after redirects |
| `ok()` | Check if status is 200-299 |
| `usedProtocol()` | Get the protocol used (HTTP/1.1, HTTP/2, etc.) |
| `sessionId()` | Get the session ID |
| `id()` | Get the response ID |

## Client Identifiers

Available client identifiers (use the constants in `ClientIdentifier` class):

### Chrome
- `CHROME_103` to `CHROME_131`
- `CHROME_116_PSK`, `CHROME_116_PSK_PQ`
- `CHROME_131_PSK`

### Safari
- `SAFARI_15_6_1`, `SAFARI_16_0`
- `SAFARI_IPAD_15_6`
- `SAFARI_IOS_15_5`, `SAFARI_IOS_15_6`, `SAFARI_IOS_16_0`, `SAFARI_IOS_17_0`, `SAFARI_IOS_18_0`

### Firefox
- `FIREFOX_102` to `FIREFOX_133`

### Opera
- `OPERA_89` to `OPERA_91`

### Mobile Clients
- Zalando: `ZALANDO_ANDROID_MOBILE`, `ZALANDO_IOS_MOBILE`
- Nike: `NIKE_IOS_MOBILE`, `NIKE_ANDROID_MOBILE`
- MMS iOS: `MMS_IOS`, `MMS_IOS_1`, `MMS_IOS_2`, `MMS_IOS_3`
- Mesh: `MESH_IOS`, `MESH_IOS_1`, `MESH_IOS_2`, `MESH_ANDROID`, `MESH_ANDROID_1`, `MESH_ANDROID_2`
- Confirmed: `CONFIRMED_IOS`, `CONFIRMED_ANDROID`
- OkHttp Android: `OKHTTP4_ANDROID_7` to `OKHTTP4_ANDROID_13`

### Other
- `CLOUDSCRAPER`

## Error Handling

The library throws exceptions for various error conditions:
- `Exception` when the shared library cannot be loaded or downloaded
- Request-specific exceptions during HTTP operations

Always wrap your code in try-catch blocks for proper error handling.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

This library is based on [bogdanfinn's](https://github.com/bogdanfinn) tls client in Go and the PHP wrapper implementation.