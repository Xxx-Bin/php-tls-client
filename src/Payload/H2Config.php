<?php

namespace PHPTLSClient\Payload;

/**
 * H2Settings 类用于构建HTTP/2相关配置
 */
class H2Config
{
    // HTTP/2 设置参数常量 (h2Settings 前缀)
    const H2SETTINGS_HEADER_TABLE_SIZE = "HEADER_TABLE_SIZE";
    const H2SETTINGS_ENABLE_PUSH = "ENABLE_PUSH";
    const H2SETTINGS_MAX_CONCURRENT_STREAMS = "MAX_CONCURRENT_STREAMS";
    const H2SETTINGS_INITIAL_WINDOW_SIZE = "INITIAL_WINDOW_SIZE";
    const H2SETTINGS_MAX_FRAME_SIZE = "MAX_FRAME_SIZE";
    const H2SETTINGS_MAX_HEADER_LIST_SIZE = "MAX_HEADER_LIST_SIZE";
    const H2SETTINGS_UNKNOWN_SETTING_7 = "UNKNOWN_SETTING_7";
    const H2SETTINGS_UNKNOWN_SETTING_8 = "UNKNOWN_SETTING_8";
    const H2SETTINGS_UNKNOWN_SETTING_9 = "UNKNOWN_SETTING_9";
    
    // HTTP/2 设置参数 ID 映射
    const H2_SETTING_ID_MAP = [
        1 => self::H2SETTINGS_HEADER_TABLE_SIZE,
        2 => self::H2SETTINGS_ENABLE_PUSH,
        3 => self::H2SETTINGS_MAX_CONCURRENT_STREAMS,
        4 => self::H2SETTINGS_INITIAL_WINDOW_SIZE,
        5 => self::H2SETTINGS_MAX_FRAME_SIZE,
        6 => self::H2SETTINGS_MAX_HEADER_LIST_SIZE,
        7 => self::H2SETTINGS_UNKNOWN_SETTING_7,
        8 => self::H2SETTINGS_UNKNOWN_SETTING_8,
        9 => self::H2SETTINGS_UNKNOWN_SETTING_9,
    ];

    private array $config = [];

    public function __construct()
    {
        $this->config = [
            'h2Settings' => null,
            'h2SettingsOrder' => null,
            'pseudoHeaderOrder' => null,
            'priorityFrames' => null,
            'headerPriority' => null,
            'connectionFlow' => 0,
        ];
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
     * 设置优先级帧
     *
     * @param array|PriorityFrames[]|null $priorityFrames
     * @return self
     */
    public function setPriorityFrames(array|PriorityFrames|null $priorityFrames): self
    {
        // 如果传入的是 PriorityFrames 对象数组，则构建每个对象
        if (is_array($priorityFrames) && !empty($priorityFrames)) {
            $builtFrames = [];
            foreach ($priorityFrames as $frame) {
                if ($frame instanceof PriorityFrames) {
                    $builtFrames[] = $frame->build();
                } else {
                    $builtFrames[] = $frame;
                }
            }
            $this->config['priorityFrames'] = $builtFrames;
        } else {
            $this->config['priorityFrames'] = $priorityFrames;
        }
        return $this;
    }

    /**
     * 设置头部优先级
     *
     * @param array|PriorityParam|null $headerPriority
     * @return self
     */
    public function setHeaderPriority(array|PriorityParam|null $headerPriority): self
    {
        if ($headerPriority instanceof PriorityParam) {
            $this->config['headerPriority'] = $headerPriority->build();
        } else {
            $this->config['headerPriority'] = $headerPriority;
        }
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
     * 从 h2fp 格式字符串设置配置
     * 格式: 1:65536;4:131072;5:16384|12517377|3:0:0:201,5:0:0:101,7:0:0:1,9:0:7:1,11:0:3:1,13:0:0:241|m,p,a,s
     *
     * @param string $h2fp
     * @return self
     */
    public function fromH2FP(string $h2fp): self
    {
        $parts = explode('|', $h2fp);
        
        if (count($parts) !== 4) {
            throw new \InvalidArgumentException('Invalid h2fp format');
        }
        
        // 解析 H2 设置
        $h2SettingsStr = $parts[0];
        $connectionFlow = (int)$parts[1];
        $priorityFramesStr = $parts[2];
        $pseudoHeaderOrderStr = $parts[3];
        
        // 解析 H2 设置参数
        $h2Settings = [];
        $h2SettingsOrder = [];
        
        if (!empty($h2SettingsStr)) {
            $settingsPairs = explode(';', $h2SettingsStr);
            foreach ($settingsPairs as $pair) {
                $kv = explode(':', $pair);
                if (count($kv) === 2) {
                    $settingId = (int)$kv[0];
                    $settingValue = (int)$kv[1];
                    
                    // 映射设置 ID 到设置名称
                    if (isset(self::H2_SETTING_ID_MAP[$settingId])) {
                        $settingName = self::H2_SETTING_ID_MAP[$settingId];
                        $h2Settings[$settingName] = $settingValue;
                        $h2SettingsOrder[] = $settingName;
                    }
                }
            }
        }
        
        // 解析优先级帧
        $priorityFrames = [];
        if (!empty($priorityFramesStr)) {
            $frames = explode(',', $priorityFramesStr);
            foreach ($frames as $frame) {
                $components = explode(':', $frame);
                if (count($components) === 4) {
                    $streamId = (int)$components[0];
                    $priorityParam = (new PriorityParam())
                        ->setStreamDep((int)$components[1])
                        ->setExclusive((bool)$components[2])
                        ->setWeight((int)$components[3]-1);//Corrections are required to be consistent with the set returns
                    
                    $priorityFrame = (new PriorityFrames())
                        ->setStreamID($streamId)
                        ->setPriorityParam($priorityParam);
                    
                    $priorityFrames[] = $priorityFrame;
                }
            }
        }
        
        // 解析伪头部顺序
        $pseudoHeaderOrder = [];
        if (!empty($pseudoHeaderOrderStr)) {
            $headers = explode(',', $pseudoHeaderOrderStr);
            foreach ($headers as $header) {
                switch (trim($header)) {
                    case 'm':
                        $pseudoHeaderOrder[] = ':method';
                        break;
                    case 'a':
                        $pseudoHeaderOrder[] = ':authority';
                        break;
                    case 's':
                        $pseudoHeaderOrder[] = ':scheme';
                        break;
                    case 'p':
                        $pseudoHeaderOrder[] = ':path';
                        break;
                }
            }
        }
        
        // 设置解析后的配置
        $this->setH2Settings($h2Settings);
        $this->setH2SettingsOrder($h2SettingsOrder);
        $this->setPseudoHeaderOrder($pseudoHeaderOrder);
        $this->setConnectionFlow($connectionFlow);
        
        // 设置优先级帧
        if (!empty($priorityFrames)) {
            $this->setPriorityFrames($priorityFrames);
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
}