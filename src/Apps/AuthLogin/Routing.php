<?php


namespace Symbiotic\Apps\AuthLogin;


use Symbiotic\Routing\AppRouting;
use Symbiotic\Routing\RouterInterface;


class Routing extends AppRouting
{
    public function frontendRoutes(RouterInterface $router)
    {

        $router->group(['namespace' => 'Frontend'], function (RouterInterface $router) {

            // todo: csrf link hash
            $router->get('/logout', [
                'uses' => 'Auth@logout',
                'as' => 'auth.logout'
            ]);
            $router->get('login', [
                'uses' => 'Auth@login',
                'as' => 'auth.login'
            ]);
            $router->post('/login/', [
                'uses' => 'Auth@auth',
                'as' => 'auth.auth'
            ]);
        });
    }
}