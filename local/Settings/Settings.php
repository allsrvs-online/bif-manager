<?php

namespace Local\Settings;

class Settings implements \JsonSerializable
{
    private array $settings;

    public function __construct(array $settings)
    {
        foreach ($settings as $key => $value) {
            if (is_array($value) && $this->isAssoc($value)) {
                $settings[$key] = new Settings($value);
            }
        }
        $this->settings = $settings;
    }

    private function isAssoc(array $arr): bool
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function __get($name)
    {
        return $this->settings[$name] ?? null;
    }

    public function getKeys() {
        return array_keys($this->settings);
    }

    public function jsonSerialize()
    {
        return json_encode($this->settings, JSON_PRETTY_PRINT);
    }
}