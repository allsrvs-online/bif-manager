<?php
namespace Local\Modules\Session;

use Local\Modules\Providers\UserProvider\UserProvider;
use Psr\Log\LoggerInterface;

class Session
{
    private $logger;
    private $userProvider;
    public function __construct(LoggerInterface $logger, UserProvider $userProvider)
    {
        $logger->debug('Session::__construct');
        $this->logger = $logger;
        $this->userProvider = $userProvider;
    }

    public function create(string $username, string $password): bool
    {
        $this->logger->debug('Session::create', ['username' => $username]);
        if (!$this->userProvider->validateCredentials($username, $password)) {
            self::destroy();
            return false;
        }
        self::set('user', $this->userProvider->getUser($username));
        return true;
    }

    public static function set(string $key, $value): void
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[$key] = $value;
    }

    public static function get(string $key)
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION[$key] ?? null;
    }

    public static function remove(string $key): void
    {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
    }
}