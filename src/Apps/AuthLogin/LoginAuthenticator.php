<?php

namespace Symbiotic\Apps\AuthLogin;

use Symbiotic\Auth\Authenticator\AbstractAuthenticator;
use Symbiotic\Auth\AuthResult;
use Symbiotic\Auth\ResultInterface;


class LoginAuthenticator extends AbstractAuthenticator
{

    const CONFIG_USERS_KEY = 'auth.users';

    /**
     * @uses $core['config']['auth'][users'] in core config {@see \Symbiotic\Core\Core}
     * @var array |array[] = [['login' => 'test1','password' => '$2$s32d23dd2332f3f54de32d','access_group' => 69],//....]
     */
    protected $users = [];

    /**
     * @var string email or string login
     */
    protected $login = '';

    /**
     * https://www.php.net/manual/ru/function.password-hash.php
     * @info Please use CRYPT_BLOWFISH and length > 7
     * @var string
     */
    protected $password = '';


    /**
     *
     * @param array $users нет смысла строить репозиторий,поэтому примем массив
     * по умолчанию будет всего три пользователя в конфиге
     *
     * @param string $login email or string login
     * @param string $password raw password
     */
    public function __construct(array $users, string $login, string $password)
    {
        $this->users = $users;
        $this->login = $login;
        $this->password = $password;

    }

    /**
     * Performs an authentication attempt
     *
     * @return ResultInterface
     * @throws \Exception If authentication cannot be performed
     */
    public function authenticate():ResultInterface
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