<?php

declare(strict_types=1);

namespace Symbiotic\Apps\AuthLogin;

use Symbiotic\Auth\Authenticator\AbstractAuthenticator;
use Symbiotic\Auth\AuthResult;
use Symbiotic\Auth\ResultInterface;


class LoginAuthenticator extends AbstractAuthenticator
{

    /**
     * @param array  $users    From core config  $core['config']['auth'][users']
     *                         [
     *                         ['login' => 'test1','password' => '$2$s32....','access_group' => 69],
     *                         /// .....
     *                         ]
     *
     * @param string $login    Email or string login
     *
     * @param string $password encrypted string CRYPT_BLOWFISH
     *
     * @see  https://www.php.net/manual/ru/function.password-hash.php
     * @info Please use CRYPT_BLOWFISH and length > 7
     */
    public function __construct(protected array $users, protected string $login, protected string $password)
    {
    }

    /**
     * Performs an authentication attempt
     *
     * @return ResultInterface
     * @throws \Exception If authentication cannot be performed
     */
    public function authenticate(): ResultInterface
    {
        foreach ($this->users as $user) {
            if ($user['login'] === $this->login) {
                if (true === \password_verify($this->password, $user['password'])) {
                    return new AuthResult($this->initUser($user));
                } else {
                    return (new AuthResult())->setError('Invalid password!');
                }
            }
        }
        return (new AuthResult())->setError('User not found!');
    }
}