<?php

use Core\Test\ControllerTestCase;
use Application\Controller\IndexController;
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
    protected $controllerFQDN = 'Application\Controller\IndexController';
    /**
     * @var string
     */
    protected $controllerRoute = 'application';
    
    /**
     * Testando action inexistente.
     */
    public function test404()
    {
        $this->routeMatch->setParam('action','action_que_nao_existe');
        $result   = $this->controller->dispatch($this->request);
        $response = $this->controller->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    public function testIndexAction()
    {
        $postA = $this->addPost();
        $postB = $this->addPost();
        
        $this->routeMatch->setParam('action','index');
        $result = $this->controller->dispatch(
            $this->request, $this->response
        );
        
        $response = $this->controller->getResponse();
        $this->assertEquals(200,$response->getStatusCode());
        
        $this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
        
        $variables = $result->getVariables();
        $this->assertArrayHasKey('posts', $variables);
        
        $controllerData = $variables['posts'];
        $this->assertEquals(
            $postA->title, $controllerData[0]['title']
        );
        
        $this->assertEquals(
            $postB->title, $controllerData[1]['title']
        );
    }
    
    private function addPost()
    {
        $post = new Post();
        $post->title = 'New Post Title';
        $post->description = 'This is the New Post description. <br/>No unicorns here.';
        $post->post_date = date('Y-m-d H:i:s');
        $saved = $this->getTable('Application\Model\Post')->save($post);
        
        return $saved;    
    }
}
