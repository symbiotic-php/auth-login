<?php

declare(strict_types=1);

namespace Symbiotic\Apps\AuthLogin\Http\Controllers\Frontend;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symbiotic\Apps\AuthLogin\LoginAuthenticator;
use Symbiotic\Auth\AuthServiceInterface;
use Symbiotic\Core\CoreInterface;
use Symbiotic\View\View;
use Symbiotic\View\ViewFactory;
use Symbiotic\Routing\UrlGeneratorInterface;

use function _S\redirect;


class Auth
{
    /**
     * @param AuthServiceInterface  $auth
     * @param ViewFactory           $view
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        protected AuthServiceInterface $auth,
        protected ViewFactory $view,
        protected UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @param CoreInterface $core
     *
     * @return View
     * @throws \Symbiotic\Packages\ResourceExceptionInterface
     */
    public function login(CoreInterface $core): View
    {
        return $this->view->make($this->getFormTheme($core) . '::auth/login_form');
    }

    /**
     * @param CoreInterface $core
     *
     * @return string
     */
    protected function getFormTheme(CoreInterface $core): string
    {
        return $core('config::auth.login_form_theme', 'auth_login');
    }

    /**
     * @param ServerRequestInterface $request
     * @param CoreInterface          $core
     *
     * @return View|ResponseInterface
     * @throws \Symbiotic\Packages\ResourceExceptionInterface
     * @throws \Symbiotic\Routing\RouteNotFoundException
     */
    public function auth(ServerRequestInterface $request, CoreInterface $core): View|ResponseInterface
    {
        $errors = [];
        $data = $request->getParsedBody();
        if (!empty($data['login']) && !empty($data['password'])) {
            $auth = $this->auth;

            $result = $auth->authenticate(
                new LoginAuthenticator($core('config::auth.users', []), $data['login'], $data['password'])
            );
            if ($result->isValid()) {
                return redirect($core, $this->urlGenerator->adminRoute('develop::index'), 302);
            } else {
                $errors['form'] = 'Invalid login or password!';
            }
        } else {
            $errors['form'] = 'Empty login or password!';
        }
        return $this->view->make($this->getFormTheme($core) . '::auth/login_form', ['errors' => $errors]);
    }

    /**
     * @param CoreInterface             $core
     * @param AuthServiceInterface|null $auth
     *
     * @return ResponseInterface
     * @throws \Symbiotic\Routing\RouteNotFoundException
     */
    public function logout(CoreInterface $core, AuthServiceInterface $auth = null): ResponseInterface
    {
        if (!$auth) {
            throw new \Exception('The authorization service is disabled!');
        }
        $auth->clearIdentity();
        return redirect($core, $this->urlGenerator->route('auth_login::auth.login'));
    }
}