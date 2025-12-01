<?php

namespace PHPTLSClient\Payload;

/**
 * TransportOptions 类用于构建传输层选项配置
 */
class TransportOptions
{
    private array $options = [];

    public function __construct()
    {
        $this->options = [
            'disableKeepAlives' => false,
            'disableCompression' => false,
            'maxIdleConns' => 100,
            'maxIdleConnsPerHost' => 2,
            'maxConnsPerHost' => 0,
            'maxResponseHeaderBytes' => 0,
            'writeBufferSize' => 0,
            'readBufferSize' => 0,
            'idleConnTimeout' => 0,
        ];
    }

    /**
     * 设置是否禁用 HTTP keep-alive 连接
     *
     * @param bool $disable
     * @return self
     */
    public function setDisableKeepAlives(bool $disable): self
    {
        $this->options['disableKeepAlives'] = $disable;
        return $this;
    }

    /**
     * 设置是否禁用自动响应解压缩（gzip, deflate 等）
     *
     * @param bool $disable
     * @return self
     */
    public function setDisableCompression(bool $disable): self
    {
        $this->options['disableCompression'] = $disable;
        return $this;
    }

    /**
     * 设置所有主机的最大空闲连接数
     *
     * @param int $max
     * @return self
     */
    public function setMaxIdleConns(int $max): self
    {
        $this->options['maxIdleConns'] = $max;
        return $this;
    }

    /**
     * 设置每个主机的最大空闲连接数
     *
     * @param int $max
     * @return self
     */
    public function setMaxIdleConnsPerHost(int $max): self
    {
        $this->options['maxIdleConnsPerHost'] = $max;
        return $this;
    }

    /**
     * 设置每个主机的最大连接数（空闲 + 活跃）
     *
     * @param int $max
     * @return self
     */
    public function setMaxConnsPerHost(int $max): self
    {
        $this->options['maxConnsPerHost'] = $max;
        return $this;
    }

    /**
     * 设置响应头最大字节数，如果为 0 则使用默认值
     *
     * @param int $max
     * @return self
     */
    public function setMaxResponseHeaderBytes(int $max): self
    {
        $this->options['maxResponseHeaderBytes'] = $max;
        return $this;
    }

    /**
     * 设置写缓冲区大小（字节），如果为 0 则使用默认值（通常为 4KB）
     *
     * @param int $size
     * @return self
     */
    public function setWriteBufferSize(int $size): self
    {
        $this->options['writeBufferSize'] = $size;
        return $this;
    }

    /**
     * 设置读缓冲区大小（字节），如果为 0 则使用默认值（通常为 4KB）
     *
     * @param int $size
     * @return self
     */
    public function setReadBufferSize(int $size): self
    {
        $this->options['readBufferSize'] = $size;
        return $this;
    }

    /**
     * 设置空闲连接超时时间（纳秒）
     *
     * @param int $timeout
     * @return self
     */
    public function setIdleConnTimeout(int $timeout): self
    {
        $this->options['idleConnTimeout'] = $timeout;
        return $this;
    }

    /**
     * 获取构建完成的传输选项
     *
     * @return array
     */
    public function build(): array
    {
        return $this->options;
    }

    /**
     * 创建高性能配置
     *
     * @return self
     */
    public static function createHighPerformance(): self
    {
        $options = new self();
        return $options->setMaxIdleConns(200)
            ->setMaxIdleConnsPerHost(4)
            ->setIdleConnTimeout(90000000000); // 90 seconds in nanoseconds
    }

    /**
     * 创建低资源使用配置
     *
     * @return self
     */
    public static function createLowResourceUsage(): self
    {
        $options = new self();
        return $options->setDisableKeepAlives(true)
            ->setMaxIdleConns(10)
            ->setMaxIdleConnsPerHost(1);
    }

    /**
     * 创建压缩优化配置
     *
     * @return self
     */
    public static function createCompressionOptimized(): self
    {
        $options = new self();
        return $options->setDisableCompression(false)
            ->setReadBufferSize(8192)
            ->setWriteBufferSize(8192);
    }
}