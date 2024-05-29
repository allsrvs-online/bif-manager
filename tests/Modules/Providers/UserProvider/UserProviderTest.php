<?php

namespace Tests\Modules\Providers\UserProvider;

use Local\Modules\Providers\UserProvider\UserProvider;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\Logger\LoggerHelper;

const ROOT_DIR = __DIR__ . '/../../../..';

class UserProviderTest extends TestCase
{

    private UserProvider $userProvider;

    public function __construct()
    {
        parent::__construct();
        $this->userProvider = new UserProvider(LoggerHelper::getLogger(), ROOT_DIR . '/config/users/users.yml');
    }

    public function testGetOne()
    {
        $user = $this->userProvider->getUser('admin');
        $this->assertEquals('Admin', $user['name']);
    }

    public function testValidateCredentials()
    {
        $this->assertTrue($this->userProvider->validateCredentials('admin', 'admin'));
        $this->assertFalse($this->userProvider->validateCredentials('admin', 'wrong'));
    }
}
