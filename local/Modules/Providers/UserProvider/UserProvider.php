<?php

namespace Local\Modules\Providers\UserProvider;

use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class UserProvider
{
    private LoggerInterface $logger;
    private array $users;

    public function __construct(LoggerInterface $logger, string $usersFile)
    {
        $this->logger = $logger;
        $this->logger->debug('Location', ['usersFile' => $usersFile]);
        $this->logger->debug('Initializing UserProvider', ['usersFile' => $usersFile, 'file_exists' => file_exists($usersFile)]);
        $this->users = Yaml::parse(file_get_contents($usersFile));
        $this->logger->debug('UserProvider initialized');
    }

    public function validateCredentials(string $username, string $password): bool
    {
        $this->logger->debug('User', [
            'found' => array_key_exists($username, $this->users),
            'password_correct' => $this->users[$username]['password'] === $password
        ]);
        return isset($this->users[$username]) && $this->users[$username]['password'] === $password;
    }
    public function getUser(string $username): array
    {
        $user = $this->users[$username];
        unset($user['password']);
        $this->logger->debug('User', ['user' => $user]);
        return $user;
    }
}