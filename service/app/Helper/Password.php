<?php declare(strict_types=1);

namespace app\Helper;

/**
 * @author Ilya Zelenin <wyster@make.im>
 */
class Password
{
    /**
     * Create a password hash for a given plain text password
     * @param string $password
     * @return string
     */
    public static function create(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify a password hash against a given plain text password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
