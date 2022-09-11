# Symbiotic Base Login auth
### Description

The package provides simple user authorization, users are added to the main configuration of the framework.
If you use a framework together with another, it is recommended to make an adapter and authorize it through session variables.

According to the ideology of the framework , all users are divided into three types:
- Site user (unauthorized, has no rights)
- Manager (has access to the admin area and can use all applications except admin ones)
- Admin (full access to the admin panel and rights to all applications)

Example of creating users and their settings:
```php

// A section is added to the main array of the framework configuration
$config['auth'] =  [
    'users' =>[
        [
            'login' => 'admin_login',
            /** @see \Symbiotic\Auth\UserInterface::GROUP_ADMIN  and other groups **/
            'access_group' => \Symbiotic\Auth\UserInterface::GROUP_ADMIN, //admin
            // Password in https://www.php.net/manual/ru/function.password-hash.php
            // algo - PASSWORD_BCRYPT
            'password' => '$2y$10$fblGNBFYBjC9a3L6d0.lle1BoVFdMlMOzN6/NWjqBb8wFlJZt9P8C'//
        ]
    ],
    'base_login' => true, // enabling and disabling login authorization
];

```
