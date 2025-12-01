<?php

namespace PHPTLSClient;

class Client
{
    private static ?Client $instance = null;
    private static bool $ready = false;
    private ?\FFI $ffi = null;
    private string $libraryPath = '';

    private function __construct(string $libraryPath)
    {
        $this->libraryPath = $libraryPath;
        
        // Define the C interface
        $this->ffi = \FFI::cdef("
            typedef struct {} HttpRequest;
            typedef struct {} HttpResponse;
            
            char* request(char* payload);
            void freeMemory(char* ptr);
            char* destroyAll();
            char* destroySession(char* sessionId);
        ", $this->libraryPath);
    }

    public static function init(?string $libraryPath = null): void
    {
        if (self::$ready) return;

        if ($libraryPath === null) {
            // Auto-detect library path based on OS
            $libraryPath = self::getLibraryPath();
        }

        // 如果库文件不存在，尝试下载
        if (!file_exists($libraryPath)) {
            $downloader = new Downloader(dirname($libraryPath));
            if (!$downloader->isLibraryExists()) {
                try {
                    $downloader->download();
                } catch (\Exception $e) {
                    throw new \Exception("Library file not found and failed to download: " . $e->getMessage());
                }
            }
        }

        if (!file_exists($libraryPath)) {
            throw new \Exception("Library file not found: " . $libraryPath);
        }

        self::$instance = new Client($libraryPath);
        self::$ready = true;
    }

    public static function destroy(): ?array
    {
        if (!self::$instance) {
            throw new \Exception("Client not initialized. Call init() first.");
        }
        
        $response = self::$instance->ffi->destroyAll();
        if ($response) {
            $responseString = \FFI::string($response);
            $response_arr = json_decode($responseString,1);
            self::$instance->freeMemory($response_arr['id']);
            return json_decode($responseString, true);
        }
        
        self::$instance = null;
        self::$ready = false;
        return null;
    }

    public static function getInstance(): Client
    {
        if (!self::$instance) {
            throw new \Exception("Client not initialized. Call init() first.");
        }
        return self::$instance;
    }

    public function getFFI(): \FFI
    {
        return $this->ffi;
    }

    public function freeMemory(string $id): void
    {
        $this->ffi->freeMemory($id);
    }

    public static function isReady(): bool
    {
        return self::$ready;
    }

    private static function getLibraryPath(): string
    {
        $tempDir = sys_get_temp_dir();

        $platform = php_uname('s');
        $machine = php_uname('m');

        if (stripos($platform, 'Windows') !== false) {
            return $tempDir . DIRECTORY_SEPARATOR . 'tls-client-64.dll';
        } elseif (stripos($platform, 'Darwin') !== false) {
            if (strpos($machine, 'arm64') !== false) {
                return $tempDir . DIRECTORY_SEPARATOR . 'tls-client-arm64.dylib';
            } else {
                return $tempDir . DIRECTORY_SEPARATOR . 'tls-client-x86.dylib';
            }
        } else {
            if (strpos($machine, 'aarch64') !== false || strpos($machine, 'arm64') !== false) {
                return $tempDir . DIRECTORY_SEPARATOR . 'tls-client-arm64.so';
            } else {
                return $tempDir . DIRECTORY_SEPARATOR . 'tls-client-x64.so';
            }
        }
    }
}