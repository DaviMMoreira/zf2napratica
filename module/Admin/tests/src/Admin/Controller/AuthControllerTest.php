<?php

use Core\Test\ControllerTestCase;
use Admin\Controller\AuthController;
use Admin\Model\User;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * Description of AuthControllerTest
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */
class AuthControllerTest extends ControllerTestCase
{
    /**
     * @var string
     */
    protected $controllerFQCN = 'Admin\Controller\AuthController';
    /**
     * @var string
     */
    protected $controllerRoute = 'admin';
    
    public function test404()
    {
        $this->routeMatch->setParam('action', 'action_to_nowhere');
        $result = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndexActionLoginForm()
    {
        $this->routeMatch->setParam('action', 'index');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $variables = $result->getVariables();
        $this->assertArrayHasKey('form', $variables);
        
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        $form = $variables['form'];
        
        $username = $form->get('username');
        $this->assertEquals('username', $username->getName());
        $this->assertEquals('text',     $username->getAttribute('type'));
        
        $password = $form->get('password');
        $this->assertEquals('password', $password->getName());
        $this->assertEquals('password', $password->getAttribute('type'));        
    }
    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Acesso inválido
     */
    public function testLoginInvalidMethod()
    {
        $user = $this->addUser();
        
        $this->routeMatch->setParam('action', 'login');
        $result = $this->controller->dispatch(
            $this->request,
            $this->response
        );
    }
    
    public function testLogin()
    {
        $user = $this->addUser();
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('username', $user->username);
        $this->request->getPost()->set('password', 'wakawaka');
        
        $this->routeMatch->setParam('action', 'login');
        $result = $this->controller->dispatch(
            $this->request,
            $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $headers = $responde->getHeaders();
        $this->assertEquals('Location : /', $headers->get('Location'));
    }
    
    public function testLogout()
    {
        $user = $this->addUser();
        
        $this->routeMatch->setParam('action', 'logout');
        $result = $this->controller->dispatch(
            $this->request,
            $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
        
        $headers = $responde->getHeaders();
        $this->assertEquals('Location : /', $headers->get('Location'));
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
