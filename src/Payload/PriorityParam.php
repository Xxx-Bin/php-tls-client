<?php

namespace PHPTLSClient\Payload;

/**
 * PriorityParam 类用于表示HTTP/2流的优先级参数
 */
class PriorityParam
{
    private int $streamDep = 0;
    private bool $exclusive = false;
    private int $weight = 0;

    /**
     * 设置依赖的流ID
     *
     * @param int $streamDep
     * @return self
     */
    public function setStreamDep(int $streamDep): self
    {
        $this->streamDep = $streamDep;
        return $this;
    }

    /**
     * 设置是否独占
     *
     * @param bool $exclusive
     * @return self
     */
    public function setExclusive(bool $exclusive): self
    {
        $this->exclusive = $exclusive;
        return $this;
    }

    /**
     * 设置权重
     *
     * @param int $weight
     * @return self
     */
    public function setWeight(int $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * 获取流依赖ID
     *
     * @return int
     */
    public function getStreamDep(): int
    {
        return $this->streamDep;
    }

    /**
     * 获取是否独占标志
     *
     * @return bool
     */
    public function getExclusive(): bool
    {
        return $this->exclusive;
    }

    /**
     * 获取权重
     *
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }

    /**
     * 构建优先级参数数组
     *
     * @return array
     */
    public function build(): array
    {
        return [
            'streamDep' => $this->streamDep,
            'exclusive' => $this->exclusive,
            'weight' => $this->weight,
        ];
    }
}