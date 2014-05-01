<?php

use Core\Test\ControllerTestCase;
use Admin\Controller\IndexController;
use Application\Model\Post;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * Description of IndexControllerTest
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */

/**
 * @group Controller
 */
class IndexControllerTest extends ControllerTestCase
{
    /**
     * @var string
     */    
    protected $controllerFQDN = 'Admin\Controller\IndexController';
    /**
     * @var string
     */    
    protected $controllerRoute = 'admin';
    
    /*
     * 
     */
    public function test404()
    {
        $this->routeMatch->setParam('action', 'nowhere_to_go');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    /**
     * @return void
     */
    public function testSaveActionNewRequest()
    {
        $this->routeMatch->setParam('action','save');
        $result   = $this->controller->dispatch(
            $this->request,
            $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        
        $form = $variables['form'];        
        $id   = $form->get('id');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals('hidden', $id->getAttribute());        
    }
    
    /**
     * 
     */
    public function testSaveActionUpdateFormRequest()
    {
        $postA = $this->addPost();
        
        $this->routeMatch->setParams('action', 'save');
        $this->routeMatch->setParams('id', $postA->id);
        $result = $this->controller->dispatch(
            $this->request,
            $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        
        $variables = $result->getVariables();
        $this->assertInstanceOf('Zend\Form\Form', $variables['form']);
        
        $form  = $variables['form'];        
        $id    = $form->get('id');
        $title = $form->get('title');
        $this->assertEquals('id', $id->getName());
        $this->assertEquals($postA->id, $id->getValue());
        $this->assertEquals($postA->title, $title->getValue());        
    }
    
    /**
     * 
     */
    public function testSaveActionPostRequest()
    {
        $this->routeMatch->setParams('action', 'save');
        
        $this->request->setMethod('post');
        $this->request->getPost()->set('title', 'A Apple compra a Coderockr');
    }
    
    private function addPost(){
        $post = new Post();
        
        $post->title        = 'A Apple compra a Coderockr';
        $post->description  = 'A Apple compra a <b>Coderockr</b><br> ';
        $post->post_date    = date('Y-m-d H:i:s');
        
        return $post;
    }
}
