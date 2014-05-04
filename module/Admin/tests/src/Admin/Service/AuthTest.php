<?php

namespace Admin\Service;

use DateTime;
use Core\Test\ServiceTestCase;
use Admin\Model\User;
use Core\Model\EntityException;
use Zend\Authentication\AuthenticationService;

/**
 * Description of AuthTest
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */

/**
 * @group Service
 */
class AuthTest extends ServiceTestCase
{
    /**
     * @expectedException \Exception
     * @return void
     */
    public function testAuthenticateWithoutParams()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate();
    }
    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Parâmetros inválidos
     * @return void
     */
    public function testAuthenticateEmptyParams()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array());
    }
    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos
     * @return void
     */
    public function testAuthenticateInvalidParameters()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        $authService->authenticate(array(
            'username' => 'invalid',
            'password' => 'invalid'
        ));
    }
    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Login ou senha inválidos
     * @return void
     */
    public function testAuthenticateInvalidPassword()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        
        $user = $this->addUser();
        
        $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'nopenope'
        ));
    }
    
    /**
     * @return void
     */
    public function testAuthenticateValidParams()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        
        $user = $this->addUser();
        
        $result = $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'wakawaka'
        ));
        $this->assertTrue($result);
        
        $auth = new AuthenticationService();
        $this->assertEquals($auth->getIdentity(), $user->username);
        
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertEquals($user->id, $savedUser->id);
    }
    
    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        
        $auth = new AuthenticationService();
        $auth->clearIdentity();
    }
    
    /**
     * @return void
     */
    public function testLogout()
    {
        $authService = $this->serviceManager->get('Admin\Service\Auth');
        
        $user = $this->addUser();
        
        $result = $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'wakawaka'
        ));
        $this->assertTrue($result);
        
        $result = $authService->logout();
        $this->assertTrue($result);
        
        $auth = new AuthenticationService();
        $this->assertNull($auth->getIdentity());
        
        $session = $this->serviceManager->get('Session');
        $savedUser = $session->offsetGet('user');
        $this->assertNull($savedUser);
    }
    
    /**
     * @return void
     */
    public function testAuthorize()
    {
        $authService = $this->getService('Admin\Service\Auth');
        
        $result = $authService->authorize();
        $this->assertFalse($result);
        
        $user = $this->addUser();
        
        $result = $authService->authenticate(array(
            'username' => $user->username,
            'password' => 'wakawaka'
        ));
        $this->assertTrue($result);
        
        $result = $authService->authorize();
        $this->assertTrue($result);        
    }
    
    private function addUser()
    {
        $user = new User();
        $user->username = 'wakawaka';
        $user->password = md5('wakawaka');
        $user->name = 'João das Couves';
        $user->valid = 1;
        $user->role = 'admin';
        
        $saved = $this->getTable('Admin\Model\User')->save($user);
        
        return $saved;
    }
}
