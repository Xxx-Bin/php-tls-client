<?php

namespace PHPTLSClient;

class Downloader
{
    const GITHUB_API_URL = 'https://api.github.com/repos/bogdanfinn/tls-client/releases/latest';
    const USER_AGENT = 'php-tls-client';
    
    private string $libraryPath;
    private string $platform;
    private string $architecture;
    
    public function __construct(string $libraryPath)
    {
        $this->libraryPath = $libraryPath;
        $this->detectPlatform();
    }
    
    /**
     * 检测当前平台和架构
     */
    private function detectPlatform(): void
    {
        $platform = php_uname('s');
        $machine = php_uname('m');
        
        if (stripos($platform, 'Windows') !== false) {
            $this->platform = 'windows';
        } elseif (stripos($platform, 'Darwin') !== false) {
            $this->platform = 'darwin';
        } else {
            $this->platform = 'linux';
        }
        
        if (strpos($machine, 'aarch64') !== false || strpos($machine, 'arm64') !== false) {
            $this->architecture = 'arm64';
        } elseif (strpos($machine, 'x86_64') !== false || strpos($machine, 'AMD64') !== false) {
            $this->architecture = 'x64';
        } else {
            $this->architecture = 'x86';
        }
    }
    
    /**
     * 获取目标文件名
     */
    private function getTargetFileName(): string
    {
        switch ($this->platform) {
            case 'windows':
                return 'tls-client-64.dll';
            case 'darwin':
                if ($this->architecture === 'arm64') {
                    return 'tls-client-arm64.dylib';
                } else {
                    return 'tls-client-x86.dylib';
                }
            default: // linux
                if ($this->architecture === 'arm64') {
                    return 'tls-client-arm64.so';
                } else {
                    return 'tls-client-x64.so';
                }
        }
    }
    
    /**
     * 获取GitHub Release中对应的资产名称
     */
    private function getAssetName(string $version): string
    {
        switch ($this->platform) {
            case 'windows':
                return "tls-client-windows-64-$version.dll";
            case 'darwin':
                if ($this->architecture === 'arm64') {
                    return "tls-client-darwin-arm64-$version.dylib";
                } else {
                    return "tls-client-darwin-amd64-$version.dylib";
                }
            default: // linux
                if ($this->architecture === 'arm64') {
                    return "tls-client-linux-arm64-$version.so";
                } else {
                    return "tls-client-linux-64-$version.so";
                }
        }
    }
    
    /**
     * 从GitHub获取最新版本信息
     */
    public function getLatestRelease(): array
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: ' . self::USER_AGENT,
                    'Accept: application/vnd.github.v3+json'
                ],
                'timeout' => 30
            ]
        ]);
        
        $response = file_get_contents(self::GITHUB_API_URL, false, $context);
        
        if ($response === false) {
            throw new \Exception('Failed to fetch release information from GitHub');
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse GitHub API response');
        }
        
        return $data;
    }
    
    /**
     * 下载指定版本的库文件（带进度显示）
     */
    public function download(?string $version = null): bool
    {
        // 如果没有指定版本，则获取最新版本
        if ($version === null) {
            $release = $this->getLatestRelease();
            $version = ltrim($release['tag_name'], 'v');
        } else {
            $release = $this->getLatestRelease();
        }
        
        $assetName = $this->getAssetName($version);
        $targetFileName = $this->getTargetFileName();
        
        // 查找对应的资产
        $asset = null;
        foreach ($release['assets'] as $item) {
            if ($item['name'] === $assetName) {
                $asset = $item;
                break;
            }
        }
        
        if ($asset === null) {
            throw new \Exception("Asset not found: $assetName");
        }
        
        // 下载文件
        $downloadUrl = $asset['browser_download_url'];
        $fileSize = $asset['size'];
        
        echo "开始下载: $assetName\n";
        echo "文件大小: " . $this->formatBytes($fileSize) . "\n";
        
        // 使用自定义函数下载文件并显示进度
        $fileContents = $this->downloadWithProgress($downloadUrl, $fileSize);
        
        if ($fileContents === false) {
            throw new \Exception("Failed to download library from $downloadUrl");
        }
        
        echo "\n下载完成，正在保存文件...\n";
        
        // 保存文件
        $fullPath = $this->libraryPath . DIRECTORY_SEPARATOR . $targetFileName;
        if (file_put_contents($fullPath, $fileContents) === false) {
            throw new \Exception("Failed to save library to " . $fullPath);
        }
        
        echo "文件已保存到: $fullPath\n";
        return true;
    }
    
    /**
     * 带进度显示的文件下载
     */
    private function downloadWithProgress(string $url, int $totalSize): string|false
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: ' . self::USER_AGENT
                ],
                'timeout' => 300 // 5分钟超时
            ]
        ]);
        
        // 打开流
        $stream = fopen($url, 'r', false, $context);
        if (!$stream) {
            return false;
        }
        
        $content = '';
        $downloaded = 0;
        $lastProgress = 0;
        
        // 读取数据并显示进度
        while (!feof($stream)) {
            $chunk = fread($stream, 8192); // 每次读取8KB
            if ($chunk === false) {
                fclose($stream);
                return false;
            }
            
            $content .= $chunk;
            $downloaded += strlen($chunk);
            
            // 计算进度并显示（每增加5%显示一次）
            $progress = intval(($downloaded / $totalSize) * 100);
            if ($progress >= $lastProgress + 5 || $progress == 100) {
                echo "\r下载进度: $progress% (" . $this->formatBytes($downloaded) . "/" . $this->formatBytes($totalSize) . ")";
                $lastProgress = $progress;
            }
        }
        
        echo "\n";
        fclose($stream);
        return $content;
    }
    
    /**
     * 格式化字节大小显示
     */
    private function formatBytes(int $size, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($size, 1024);
        $floor = floor($base);
        $pow = pow(1024, $floor);
        $fixed = round($size / $pow, $precision);
        
        return $fixed . ' ' . $units[$floor];
    }
    
    /**
     * 检查库文件是否存在
     */
    public function isLibraryExists(): bool
    {
        $targetFileName = $this->getTargetFileName();
        return file_exists($this->libraryPath . DIRECTORY_SEPARATOR . $targetFileName);
    }
}