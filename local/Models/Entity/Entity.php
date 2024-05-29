<?php

namespace Local\Models\Entity;

class Entity implements \JsonSerializable
{
    protected array $properties = [];

    private function isAssociativeArray(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function setProperty(string $name, $value): void
    {
        if( is_array($value)) {
            if($this->isAssociativeArray($value)) {
                $this->properties[$name] = new Entity($value);
                return;
            } else {
                $entities = [];
                foreach($value as $entity) {
                    $entities[] = new Entity($entity);
                }
                $this->properties[$name] = $entities;
                return;
            }
        }
        $this->properties[$name] = $value;
    }

    public function __construct(array $properties)
    {
        foreach($properties as $name => $value) {
            $this->setProperty($name, $value);
        }
    }

    public function set(array $properties): void
    {
        $this->properties = [];
        foreach($properties as $name => $value) {
            $this->setProperty($name, $value);
        }
    }

    public function __get(string $name)
    {
        return $this->properties[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->properties[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->properties;
    }}