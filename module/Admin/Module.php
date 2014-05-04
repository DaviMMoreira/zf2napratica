<?php

namespace Admin;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap($e)
    {
        $moduleManager = $e->getApplication()
                            ->getServiceManager()
                            ->get('modulemanager');
        $sharedEvents = $moduleManager->getEventManager()
                            ->getSharedManager();
        
        $sharedEvents->attach(
            'Zend\Mvc\Controller\AbstractActionController',
            \Zend\Mvc\MvcEvent::EVENT_DISPATCH,
            array ( $this, 'mvcPreDispatch' ),
            100
        );
    }
    
    /**
     * @param MvcEvent $event
     * @return boolean
     */
    public function mvcPreDispatch($event)
    {
        $di         = $event->getTarget()->getServiceLocator();
        $routeMatch = $event->getRouteMatch();
        
        $moduleName     = $routeMatch->getParam('module');
        $controllerName = $routeMatch->getParam('controller');
        
        if ( $moduleName == 'admin' && $controllerName != 'Admin\Controller\Auth' )
        {
            $authService = $di->get('Admin\Service\Auth');
            if ( !$authService->authorize() )
            {
                $redirect = $event->getTarget()->redirect();
                $redirect->toUrl('/admin/auth');
            }
        }
        
        return true;
    }
}