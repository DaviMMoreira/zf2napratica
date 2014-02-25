<?php

namespace Application\Model;

use Core\Test\ModelTestCase;
use Application\Model\Post;
use Application\Model\Comment;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of CommentTest
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */

class CommentTest extends ModelTestCase
{
    public function testGetInputFilter(){
        $comment = new Comment();
        $if      = $comment->getInputFilter();
        
        $this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
        
        return $if;
    }
    
    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if){
        $this->assertEquals(7, $if->count());
        
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('post_id'));
        $this->assertTrue($if->has('description'));
        $this->assertTrue($if->has('name'));
        $this->assertTrue($if->has('email'));
        $this->assertTrue($if->has('webpage'));
        $this->assertTrue($if->has('comment_date'));
    }
    
    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Input inválido: email = 
     */
    public function testInputFilterValido(){
        $comment        = new Comment();
        $comment->email = 'yadayada';
    }
    
    /**
     * 
     */
    public function testInsert(){
        $comment = $this->addComment();
        $saved   = $this->getTable('Application\Model\Comment')->save($comment);
        
        $this->assertEquals(
            'Zombies reversus ab inferno, nam malum ebro. De c alert("ok!");.',
            $saved->description
        );
        
        $this->assertEquals(1, $saved->id);
    }
    
    /**
     * @expectedException Zend\Db\Adapter\Exception\InvalidQueryException
     */
    public function testInsertInvalido(){
        $comment = new Comment();
        $comment->description = 'teste';
        $comment->post_id     = 0;
        
        $saved = $this->getTable('Application\Model\Comment')->save($comment);
    }
    
    public function testUpdate(){
        $tableGateway = $this->getTable('Application\Model\Comment');
        $comment      = $this->addComment();
        $saved        = $tableGateway->save($comment);
        $id           = $saved->id;
        $this->assertEquals(1, $id);
        
        $comment = $tableGateway->get($id);
        $this->assertEquals('teste@teste.com', $comment->email);
        
        $comment->email = 'teste@teste.com.br';
        $updated        = $tableGateway->save($comment);
        
        $comment = $tableGateway->get($id);
        $this->assertEquals('teste@teste.com.br', $comment->email);
    }
    
    /**
     * @expectedException Zend\Db\Adapter\Exception\InvalidQueryException
     * @expectedExceptionMessage Statement could not be executed
     */
    public function testUpdateInvalido(){
        $tableGateway = $this->getTable('Application\Model\Comment');        
        $comment  = $this->addComment();
        $saved = $tableGateway->save($comment);
        $id = $saved->id;
        
        $comment = $tableGateway->get($id);
        $comment->post_id = 10;
        $updated = $tableGateway->save($comment);        
    }
    
    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Could not find row 1
     */
    public function testDelete(){
        $tableGateway = $this->getTable('Application\Model\Comment');        
        $comment  = $this->addComment();
        $saved = $tableGateway->save($comment);
        $id = $saved->id;
        
        $deleted = $tableGateway->delete($id);
        $this->assertEquals(1, $deleted);
        
        $comment = $tableGateway->get($id);
    }
    
    private function addPost(){
        $post = new Post();
        
        $post->title        = 'OMG The King is nude!';
        $post->description  = 'OMG The King is nude! Again!';
        $post->post_date    = date('Y-m-d H:i:s');
        
        $saved = $this->getTable('Application\Model\Post')->save($post);
        return $saved;
    }
    
    private function addComment(){
        $post = $this->addPost();
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->description = 'Zombies reversus ab inferno, nam malum ebro. De c <script>alert("ok!");<script>.<br>';
        $comment->name = 'Teste Testado';
        $comment->email = 'teste@teste.com';
        $comment->webpage = 'http://www.teste.com';
        $comment->comment_date = date('Y-m-d H:i:s');
        
        return $comment;
    }
}
