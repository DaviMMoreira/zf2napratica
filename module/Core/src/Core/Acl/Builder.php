<?php

namespace Core\Acl;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Description of Builder
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */
class Builder implements ServiceManagerAwareInterface
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;
    
    /**
     * @param \Zend\ServiceManager\ServiceManager $serviceManager
     */
    public function setServiceManager(Zend\ServiceManager\ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return \Zend\ServiceManager\ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @return \Zend\Permissions\Acl\Acl
     */
    public function build()
    {
        $config = $this->getServiceManager()->get('Config');        
        $acl    = new Acl();
        // Roles
        foreach ($config['acl']['roles'] as $role => $parent)
        {
            $acl->addRole(new Role($role), $parent);
        }
        // Resources
        foreach ($config['acl']['resources'] as $resource)
        {
            $acl->addResource(new Role($resource));
        }
        // Privileges
        foreach ($config['acl']['privilege'] as $role => $privilege)
        {
            if ( isset($privilege['allow']) )
            {
                foreach ($privilege['allow'] as $allow)
                {
                    $acl->allow($role, $allow);
                }
            }
            if ( isset($privilege['deny']) )
            {
                foreach ($privilege['deny'] as $deny)
                {
                    $acl->deny($role, $deny);
                }               
            }
        }
        
        return $acl;
    }
}
