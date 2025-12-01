<?php

namespace PHPTLSClient\Payload;

/**
 * PriorityFrames 类用于表示HTTP/2优先级帧
 */
class PriorityFrames
{
    private int $streamID = 0;
    private ?array $priorityParam = null;

    /**
     * 设置流ID
     *
     * @param int $streamID
     * @return self
     */
    public function setStreamID(int $streamID): self
    {
        $this->streamID = $streamID;
        return $this;
    }

    /**
     * 设置优先级参数
     *
     * @param PriorityParam|array|null $priorityParam
     * @return self
     */
    public function setPriorityParam(PriorityParam|array|null $priorityParam): self
    {
        if ($priorityParam instanceof PriorityParam) {
            $this->priorityParam = $priorityParam->build();
        } else {
            $this->priorityParam = $priorityParam;
        }
        return $this;
    }

    /**
     * 获取流ID
     *
     * @return int
     */
    public function getStreamID(): int
    {
        return $this->streamID;
    }

    /**
     * 获取优先级参数
     *
     * @return array|null
     */
    public function getPriorityParam(): ?array
    {
        return $this->priorityParam;
    }

    /**
     * 构建优先级帧数组
     *
     * @return array
     */
    public function build(): array
    {
        return [
            'streamID' => $this->streamID,
            'priorityParam' => $this->priorityParam,
        ];
    }
}