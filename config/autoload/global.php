<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'db' => array (
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=zf2napratica;host=localhost',
        'driver_options' => array (
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        )
    ),
    'acl' => array(
        'roles' => array(
            'visitor' => null,
            'writer'  => 'visitor',
            'admin'   => 'writer'
        ),
        'resources' => array(
            'Application\Controller\Index.index',
            'Admin\Controller\Index.save',
            'Admin\Controller\Index.delete',
            'Admin\Controller\Auth.index',
            'Admin\Controller\Auth.login',
            'Admin\Controller\Auth.logout',
        ),
        'privilege' => array(
            'visitor' => array(
                'allow' => array(
                    'Application\Controller\Index.index',
                    'Admin\Controller\Auth.index',
                    'Admin\Controller\Auth.login',
                    'Admin\Controller\Auth.logout',
                )
            ),
            'writer' => array(
                'allow' => array(
                    'Admin\Controller\Index.save',
                )
            ),
            'admin' => array(
                'allow' => array(
                    'Admin\Controller\Index.delete',
                )
            ),
        )
    )
);