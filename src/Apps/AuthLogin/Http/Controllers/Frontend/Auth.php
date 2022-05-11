<?php

namespace Symbiotic\Apps\AuthLogin\Http\Controllers\Frontend;

use Psr\Http\Message\ServerRequestInterface;
use Symbiotic\Apps\AuthLogin\LoginAuthenticator;
use Symbiotic\Auth\AuthServiceInterface;
use Symbiotic\Core\CoreInterface;
use Symbiotic\Core\View\View;
use function _S\redirect;
use function _S\route;

class Auth
{
    /**
     * @var AuthServiceInterface
     */
    protected $auth;

    /**
     * Auth constructor.
     * @param AuthServiceInterface $auth
     */
    public function __construct(AuthServiceInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param CoreInterface $core
     * @return View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function login(CoreInterface $core)
    {
        return View::make($this->getFormTheme($core) . '::auth/login_form');
    }

    /**
     * @param CoreInterface $core
     * @return mixed
     */
    protected function getFormTheme(CoreInterface $core)
    {
        return $core('config::auth.login_form_theme', 'auth_login');
    }

    /**
     * @param ServerRequestInterface $request
     * @param CoreInterface $core
     * @return \Psr\Http\Message\ResponseInterface|View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function auth(ServerRequestInterface $request, CoreInterface $core)
    {
        $errors = [];
        $data = $request->getParsedBody();
        if (!empty($data['login']) && !empty($data['password'])) {

            $auth = $this->auth;
            if ($auth) {
                $result = $auth->authenticate(new LoginAuthenticator($core('config::auth.users', []), $data['login'], $data['password']));
                if ($result->isValid()) {
                    return redirect(route('backend:develop::index'));
                } else {
                    $errors['form'] = 'Invalid login or password!';
                }
            } else {
                $errors['form'] = 'The authorization service is disabled!';
            }
        } else {
            $errors['form'] = 'Empty login or password!';

        }
        return View::make($this->getFormTheme($core) . '::auth/login_form', ['errors' => $errors]);
    }

    public function logout(AuthServiceInterface $auth = null)
    {
        if (!$auth) {
            throw new \Exception('The authorization service is disabled!');
        }
        $auth->clearIdentity();
        return redirect(route('auth_login::auth.login'));
    }
}