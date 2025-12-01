<?php

namespace PHPTLSClient\Payload;

/**
 * CustomTlsClient 类用于构建自定义 TLS 客户端配置
 */
class CustomTlsClient
{
    // TLS 版本常量
    const TLS_VERSION_GREASE = "GREASE";
    const TLS_VERSION_1_3 = "1.3";
    const TLS_VERSION_1_2 = "1.2";
    const TLS_VERSION_1_1 = "1.1";
    const TLS_VERSION_1_0 = "1.0";
    
    // 签名算法常量
    const SIGNATURE_ALGO_PKCS1_WITH_SHA256 = "PKCS1WithSHA256";
    const SIGNATURE_ALGO_PKCS1_WITH_SHA384 = "PKCS1WithSHA384";
    const SIGNATURE_ALGO_PKCS1_WITH_SHA512 = "PKCS1WithSHA512";
    const SIGNATURE_ALGO_PSS_WITH_SHA256 = "PSSWithSHA256";
    const SIGNATURE_ALGO_PSS_WITH_SHA384 = "PSSWithSHA384";
    const SIGNATURE_ALGO_PSS_WITH_SHA512 = "PSSWithSHA512";
    const SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256 = "ECDSAWithP256AndSHA256";
    const SIGNATURE_ALGO_ECDSA_WITH_P384_AND_SHA384 = "ECDSAWithP384AndSHA384";
    const SIGNATURE_ALGO_ECDSA_WITH_P521_AND_SHA512 = "ECDSAWithP521AndSHA512";
    const SIGNATURE_ALGO_PKCS1_WITH_SHA1 = "PKCS1WithSHA1";
    const SIGNATURE_ALGO_ECDSA_WITH_SHA1 = "ECDSAWithSHA1";
    const SIGNATURE_ALGO_ED25519 = "Ed25519";
    
    // 密钥共享曲线常量
    const KEY_SHARE_CURVE_GREASE = "GREASE";
    const KEY_SHARE_CURVE_P256 = "P256";
    const KEY_SHARE_CURVE_P384 = "P384";
    const KEY_SHARE_CURVE_P521 = "P521";
    const KEY_SHARE_CURVE_X25519 = "X25519";
    const KEY_SHARE_CURVE_P256_KYBER768 = "P256Kyber768";
    const KEY_SHARE_CURVE_X25519_KYBER512D = "X25519Kyber512D";
    const KEY_SHARE_CURVE_X25519_KYBER768 = "X25519Kyber768";
    
    // 证书压缩算法常量
    const CERT_COMPRESSION_ALGO_ZLIB = "zlib";
    const CERT_COMPRESSION_ALGO_BROTLI = "brotli";
    const CERT_COMPRESSION_ALGO_ZSTD = "zstd";
    
    private array $config = [];

    public function __construct()
    {
        $this->config = [
            'ja3String' => '',
            'h2Settings' => null,
            'h2SettingsOrder' => null,
            'pseudoHeaderOrder' => null,
            'connectionFlow' => 0,
            'priorityFrames' => null,
            'headerPriority' => null,
            'certCompressionAlgos' => null,
            'supportedVersions' => null,
            'supportedSignatureAlgorithms' => null,
            'keyShareCurves' => null,
            'alpnProtocols' => null,
            'alpsProtocols' => null,
            'headerOrder' => null,
            'ECHCandidatePayloads' => null,
            'ECHCandidateCipherSuites' => null,
            'supportedDelegatedCredentialsAlgorithms' => null,
            'recordSizeLimit' => null,
        ];
    }

    /**
     * 设置 JA3 指纹字符串
     *
     * @param string $ja3String
     * @return self
     */
    public function setJa3String(string $ja3String): self
    {
        $this->config['ja3String'] = $ja3String;
        return $this;
    }

    /**
     * 设置 HTTP/2 设置
     *
     * @param array|null $h2Settings
     * @return self
     */
    public function setH2Settings(?array $h2Settings): self
    {
        $this->config['h2Settings'] = $h2Settings;
        return $this;
    }

    /**
     * 设置 HTTP/2 设置顺序
     *
     * @param array|null $h2SettingsOrder
     * @return self
     */
    public function setH2SettingsOrder(?array $h2SettingsOrder): self
    {
        $this->config['h2SettingsOrder'] = $h2SettingsOrder;
        return $this;
    }

    /**
     * 设置伪头部顺序
     *
     * @param array|null $pseudoHeaderOrder
     * @return self
     */
    public function setPseudoHeaderOrder(?array $pseudoHeaderOrder): self
    {
        $this->config['pseudoHeaderOrder'] = $pseudoHeaderOrder;
        return $this;
    }

    /**
     * 设置连接流控制窗口大小
     *
     * @param int $connectionFlow
     * @return self
     */
    public function setConnectionFlow(int $connectionFlow): self
    {
        $this->config['connectionFlow'] = $connectionFlow;
        return $this;
    }

    /**
     * 设置优先级帧
     *
     * @param array|null $priorityFrames
     * @return self
     */
    public function setPriorityFrames(?array $priorityFrames): self
    {
        $this->config['priorityFrames'] = $priorityFrames;
        return $this;
    }

    /**
     * 设置头部优先级
     *
     * @param array|null $headerPriority
     * @return self
     */
    public function setHeaderPriority(?array $headerPriority): self
    {
        $this->config['headerPriority'] = $headerPriority;
        return $this;
    }

    /**
     * 设置证书压缩算法
     *
     * @param array $certCompressionAlgos 可选值: zlib, brotli, zstd
     * @return self
     */
    public function setCertCompressionAlgos(array $certCompressionAlgos): self
    {
        $this->config['certCompressionAlgos'] = $certCompressionAlgos;
        return $this;
    }

    /**
     * 设置支持的 TLS 版本
     *
     * @param array $supportedVersions 可选值: GREASE, 1.3, 1.2, 1.1, 1.0
     * @return self
     */
    public function setSupportedVersions(array $supportedVersions): self
    {
        $this->config['supportedVersions'] = $supportedVersions;
        return $this;
    }

    /**
     * 设置支持的签名算法
     *
     * @param array $supportedSignatureAlgorithms
     * @return self
     */
    public function setSupportedSignatureAlgorithms(array $supportedSignatureAlgorithms): self
    {
        $this->config['supportedSignatureAlgorithms'] = $supportedSignatureAlgorithms;
        return $this;
    }

    /**
     * 设置密钥共享曲线
     *
     * @param array $keyShareCurves
     * @return self
     */
    public function setKeyShareCurves(array $keyShareCurves): self
    {
        $this->config['keyShareCurves'] = $keyShareCurves;
        return $this;
    }

    /**
     * 设置 ALPN 协议
     *
     * @param array $alpnProtocols
     * @return self
     */
    public function setAlpnProtocols(array $alpnProtocols): self
    {
        $this->config['alpnProtocols'] = $alpnProtocols;
        return $this;
    }

    /**
     * 设置 ALPS 协议
     *
     * @param array $alpsProtocols
     * @return self
     */
    public function setAlpsProtocols(array $alpsProtocols): self
    {
        $this->config['alpsProtocols'] = $alpsProtocols;
        return $this;
    }

    /**
     * 设置请求头顺序
     *
     * @param array $headerOrder
     * @return self
     */
    public function setHeaderOrder(array $headerOrder): self
    {
        $this->config['headerOrder'] = $headerOrder;
        return $this;
    }

    /**
     * 设置 ECH 候选载荷
     *
     * @param array|null $ECHCandidatePayloads
     * @return self
     */
    public function setECHCandidatePayloads(?array $ECHCandidatePayloads): self
    {
        $this->config['ECHCandidatePayloads'] = $ECHCandidatePayloads;
        return $this;
    }

    /**
     * 设置 ECH 候选密码套件
     *
     * @param array|null $ECHCandidateCipherSuites
     * @return self
     */
    public function setECHCandidateCipherSuites(?array $ECHCandidateCipherSuites): self
    {
        $this->config['ECHCandidateCipherSuites'] = $ECHCandidateCipherSuites;
        return $this;
    }

    /**
     * 设置支持的委派凭证算法
     *
     * @param array|null $supportedDelegatedCredentialsAlgorithms
     * @return self
     */
    public function setSupportedDelegatedCredentialsAlgorithms(?array $supportedDelegatedCredentialsAlgorithms): self
    {
        $this->config['supportedDelegatedCredentialsAlgorithms'] = $supportedDelegatedCredentialsAlgorithms;
        return $this;
    }

    /**
     * 设置记录大小限制
     *
     * @param int|null $recordSizeLimit
     * @return self
     */
    public function setRecordSizeLimit(?int $recordSizeLimit): self
    {
        $this->config['recordSizeLimit'] = $recordSizeLimit;
        return $this;
    }

    /**
     * 设置 H2 配置（合并配置）
     *
     * @param H2Config|array|null $h2Config
     * @return self
     */
    public function setH2Config(H2Config|array|null $h2Config): self
    {
        if ($h2Config instanceof H2Config) {
            $config = $h2Config->build();
        } else {
            $config = $h2Config;
        }

        // 合并 H2 配置项
        if (isset($config['h2Settings'])) {
            $this->config['h2Settings'] = $config['h2Settings'];
        }
        
        if (isset($config['h2SettingsOrder'])) {
            $this->config['h2SettingsOrder'] = $config['h2SettingsOrder'];
        }
        
        if (isset($config['pseudoHeaderOrder'])) {
            $this->config['pseudoHeaderOrder'] = $config['pseudoHeaderOrder'];
        }
        
        if (isset($config['priorityFrames'])) {
            $this->config['priorityFrames'] = $config['priorityFrames'];
        }
        
        if (isset($config['headerPriority'])) {
            $this->config['headerPriority'] = $config['headerPriority'];
        }
        
        if (isset($config['connectionFlow'])) {
            $this->config['connectionFlow'] = $config['connectionFlow'];
        }

        return $this;
    }

    /**
     * 获取构建完成的配置
     *
     * @return array
     */
    public function build(): array
    {
        return $this->config;
    }

    /**
     * 创建预设的 Chrome 124 配置
     *
     * @return self
     */
    public static function createChrome124(): self
    {
        $client = new self();
        return $client->setJa3String('771,4865-4866-4867-49195-49199-49196-49200-52393-52392-49171-49172-156-157-47-53,0-23-65281-10-11-35-16-5-13-18-51-45-43-27-17513-2570-21,2570-29-23-24,0')
            ->setH2Settings([
                'HEADER_TABLE_SIZE' => 65536,
                'MAX_CONCURRENT_STREAMS' => 1000,
                'INITIAL_WINDOW_SIZE' => 6291456,
                'MAX_HEADER_LIST_SIZE' => 262144
            ])
            ->setH2SettingsOrder([
                'HEADER_TABLE_SIZE',
                'MAX_CONCURRENT_STREAMS',
                'INITIAL_WINDOW_SIZE',
                'MAX_HEADER_LIST_SIZE'
            ])
            ->setPseudoHeaderOrder([':method', ':authority', ':scheme', ':path'])
            ->setConnectionFlow(15663105)
            ->setPriorityFrames([])
            ->setHeaderPriority([
                'streamDep' => 0,
                'exclusive' => false,
                'weight' => 0,
            ])
            ->setCertCompressionAlgos([self::CERT_COMPRESSION_ALGO_BROTLI])
            ->setSupportedVersions([
                self::TLS_VERSION_GREASE,
                self::TLS_VERSION_1_3,
                self::TLS_VERSION_1_2
            ])
            ->setSupportedSignatureAlgorithms([
                self::SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256,
                self::SIGNATURE_ALGO_PSS_WITH_SHA256,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA256,
                self::SIGNATURE_ALGO_ECDSA_WITH_P384_AND_SHA384,
                self::SIGNATURE_ALGO_PSS_WITH_SHA384,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA384,
                self::SIGNATURE_ALGO_PSS_WITH_SHA512,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA512,
            ])
            ->setKeyShareCurves([
                self::KEY_SHARE_CURVE_GREASE,
                self::KEY_SHARE_CURVE_X25519
            ])
            ->setAlpnProtocols(['h2', 'http/1.1'])
            ->setAlpsProtocols(['h2']);
    }

    /**
     * 创建预设的 Safari 16.0 配置
     *
     * @return self
     */
    public static function createSafari160(): self
    {
        $client = new self();
        return $client->setJa3String('771,4865-4867-49195-49199-52393-52392-49196-49200-156-157-47-53,0-23-65281-10-11-35-16-5-51-43-13-45-27-17513-2570-21,29-23-24,0')
            ->setH2Settings([
                'HEADER_TABLE_SIZE' => 4096,
                'MAX_CONCURRENT_STREAMS' => 100,
                'INITIAL_WINDOW_SIZE' => 2097152,
                'MAX_HEADER_LIST_SIZE' => 262144
            ])
            ->setH2SettingsOrder([
                'HEADER_TABLE_SIZE',
                'MAX_CONCURRENT_STREAMS',
                'INITIAL_WINDOW_SIZE',
                'MAX_HEADER_LIST_SIZE'
            ])
            ->setPseudoHeaderOrder([':method', ':scheme', ':path', ':authority'])
            ->setConnectionFlow(10485760)
            ->setPriorityFrames([])
            ->setHeaderPriority([
                'streamDep' => 0,
                'exclusive' => false,
                'weight' => 0,
            ])
            ->setCertCompressionAlgos([self::CERT_COMPRESSION_ALGO_ZLIB])
            ->setSupportedVersions([
                self::TLS_VERSION_GREASE,
                self::TLS_VERSION_1_3,
                self::TLS_VERSION_1_2
            ])
            ->setSupportedSignatureAlgorithms([
                self::SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256,
                self::SIGNATURE_ALGO_ECDSA_WITH_P384_AND_SHA384,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA256,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA384,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA1,
            ])
            ->setKeyShareCurves([
                self::KEY_SHARE_CURVE_GREASE,
                self::KEY_SHARE_CURVE_P256,
                self::KEY_SHARE_CURVE_X25519
            ])
            ->setAlpnProtocols(['h2', 'http/1.1'])
            ->setAlpsProtocols(['h2']);
    }

    /**
     * 创建预设的 Firefox 132 配置
     *
     * @return self
     */
    public static function createFirefox132(): self
    {
        $client = new self();
        return $client->setJa3String('771,4865-4867-49195-49199-52393-52392-49196-49200-159-158-47-53,0-23-65281-10-11-35-16-5-51-43-13-45-27-17513-2570-21,29-23-24-25-256-257,0')
            ->setH2Settings([
                'HEADER_TABLE_SIZE' => 65536,
                'MAX_CONCURRENT_STREAMS' => 1000,
                'INITIAL_WINDOW_SIZE' => 131072,
                'MAX_HEADER_LIST_SIZE' => 262144
            ])
            ->setH2SettingsOrder([
                'HEADER_TABLE_SIZE',
                'MAX_CONCURRENT_STREAMS',
                'INITIAL_WINDOW_SIZE',
                'MAX_HEADER_LIST_SIZE'
            ])
            ->setPseudoHeaderOrder([':method', ':path', ':authority', ':scheme'])
            ->setConnectionFlow(12472321)
            ->setPriorityFrames([])
            ->setHeaderPriority([
                'streamDep' => 0,
                'exclusive' => false,
                'weight' => 0,
            ])
            ->setCertCompressionAlgos([self::CERT_COMPRESSION_ALGO_ZSTD])
            ->setSupportedVersions([
                self::TLS_VERSION_GREASE,
                self::TLS_VERSION_1_3,
                self::TLS_VERSION_1_2
            ])
            ->setSupportedSignatureAlgorithms([
                self::SIGNATURE_ALGO_ECDSA_WITH_P256_AND_SHA256,
                self::SIGNATURE_ALGO_ECDSA_WITH_P384_AND_SHA384,
                self::SIGNATURE_ALGO_PSS_WITH_SHA256,
                self::SIGNATURE_ALGO_PSS_WITH_SHA384,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA256,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA384,
                self::SIGNATURE_ALGO_PKCS1_WITH_SHA1,
            ])
            ->setKeyShareCurves([
//                self::KEY_SHARE_CURVE_GREASE,
                self::KEY_SHARE_CURVE_P256,
                self::KEY_SHARE_CURVE_X25519
            ])
            ->setAlpnProtocols(['h2', 'http/1.1'])
            ->setAlpsProtocols(['h2']);
    }
}