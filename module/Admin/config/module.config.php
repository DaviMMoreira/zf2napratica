<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Index' => 'Admin\Controller\IndexController',
            'Admin\Controller\Auth' => 'Admin\Controller\AuthController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Admin\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'module'        => 'admin'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                        'child_routes' => array(
                            'wildcard' => array(
                                'type' => 'Wildcard'
                            ),
                        ),
                    ),
                    
                ),
            ),
        ),
    ),
    'view_manager' => array( 
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Session' => function($sm){
                return new Zend\Session\Container('ZF2napratica');
            },
            'Admin\Service\Auth' => function($sm){
                $dbAdapter = $sm->get('dbAdapter');
                return new Admin\Service\Auth($dbAdapter);
            },
            'Cache' => function($sm){
                $config = include __DIR__ . '/../../../config/application.config.php';
                
                $cache = Zend\Cache\StorageFactory::factory(
                    array(
                        'adapter' => $config['cache']['adapter'],
                        'plugins' => array(
                            'exception_handler' => array(
                                'throw_exceptions' => false
                            ),
                            'Serializer'
                        ),
                    )
                );
                
                return $cache;
            },
        )
    )
);