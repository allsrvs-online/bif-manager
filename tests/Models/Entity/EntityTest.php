<?php

namespace Tests\Models\Entity;

use Local\Models\Entity\Entity;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function testSet()
    {
        $entity = new Entity([]);
        $entity->set(['name' => 'John']);
        $this->assertEquals('John', $entity->name);
    }

    public function testSetAssociativeArray()
    {
        $entity = new Entity([]);
        $entity->set(['address' => ['city' => 'New York']]);
        $this->assertInstanceOf(Entity::class, $entity->address);
        $this->assertEquals('New York', $entity->address->city);
    }

    public function testSetIndexedArray()
    {
        $entity = new Entity([]);
        $entity->set(['addresses' => [['city' => 'New York'], ['city' => 'Los Angeles']]]);
        $this->assertIsArray($entity->addresses);
        $this->assertInstanceOf(Entity::class, $entity->addresses[0]);
        $this->assertEquals('New York', $entity->addresses[0]->city);
        $this->assertInstanceOf(Entity::class, $entity->addresses[1]);
        $this->assertEquals('Los Angeles', $entity->addresses[1]->city);
    }

    public function testGet()
    {
        $entity = new Entity(['name' => 'John']);
        $this->assertEquals('John', $entity->name);
    }

    public function testSetAndGet()
    {
        $entity = new Entity([]);
        $entity->name = 'John';
        $this->assertEquals('John', $entity->name);
    }

    public function testJsonSerialize()
    {
        $entity = new Entity(['name' => 'John']);
        $this->assertEquals(['name' => 'John'], $entity->jsonSerialize());
    }
}