<?php

/**
 * TLS Client FFI Example
 * 
 * Select the corresponding library file according to the operating system.
 * You need to manually download the library for your system:
 * https://github.com/bogdanfinn/tls-client/releases
 */

// Library path - adjust according to your operating system
$libraryPath = sys_get_temp_dir().'/tls-client-64.dll'; // Windows 64-bit

// Check if the library file exists
if (!file_exists($libraryPath)) {
    die("Library file does not exist: $libraryPath\n");
}

echo "Library file exists, attempting to load...\n";

try {
    // Define the C function interfaces to call
    // Note: Function signatures need to reference the exported functions of tls-client
    $ffi = FFI::cdef("
        typedef struct {} HttpRequest;
        typedef struct {} HttpResponse;
        
        // Function signatures need to be defined according to the actual API
        char* request(char* payload);
        void freeMemory(char* ptr);
        char* destroyAll();
        char* destroySession(char* sessionId);
    ", $libraryPath);

    echo "FFI library loaded successfully\n";
} catch (Exception $e) {
    die("Failed to load FFI library: " . $e->getMessage() . "\n");
}

// Construct request parameters (JSON format)
$payload = json_encode([
    'sessionId' => uniqid(),
    'followRedirects' => false,
    'forceHttp1' => false,
    'withDebug' => false,
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        'Accept' => '*/*',
        'Connection' => 'keep-alive',
    ],
    'requestUrl' => 'https://httpbin.org/get',
    'requestMethod' => 'GET',
    'requestBody' => '',
    'timeoutMilliseconds' => 30000,
    'tlsClientIdentifier' => 'chrome_124',
], JSON_PRETTY_PRINT);

echo "Sending request...\n";

// Call the request function
$response = $ffi->request($payload);

// Process response
if ($response) {
    // Properly handle C string
    $responseString = FFI::string($response);
    $responseData = json_decode($responseString, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        var_dump($responseData);
    } else {
        echo "Failed to decode JSON response: " . json_last_error_msg() . "\n";
        echo "Raw response: " . $responseString . "\n";
    }

    // Free memory
    $ffi->freeMemory($response);
} else {
    echo "Request returned empty response\n";
}

// Clean up all sessions
$cleanup = $ffi->destroyAll();
if ($cleanup) {
    $cleanupResult = FFI::string($cleanup);
    echo "Cleanup result: " . $cleanupResult . "\n";
    $ffi->freeMemory($cleanup);
}

?>
