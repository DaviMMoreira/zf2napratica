<?php

namespace Admin\Model;

use Core\Test\ModelTestCase;
use Admin\Model\User;
use Zend\InputFilter\InputFilterInterface;

/**
 * Description of UserTest
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */

/**
 * @group Model
 */
class UserTest extends ModelTestCase
{
    public function testGetInputFilter()
    {
        $user = new User();
        $if = $user->getInputFilter();
        $this->assertInstanceOf("Zend\InputFilter\InputFilter", $if);
        
        return $if;
    }
    
    /**
     * @depends testGetInputFilter
     */
    public function testInputFilterValid($if)
    {
        $this->assertEquals(6, $if->count());
        
        $this->assertTrue($if->has('id'));
        $this->assertTrue($if->has('username'));
        $this->assertTrue($if->has('password'));
        $this->assertTrue($if->has('name'));
        $this->assertTrue($if->has('valid'));
        $this->assertTrue($if->has('role'));
    }
    
    /**
     * @expectedException Core\Model\EntityException
     */
    public function testInputFilterInvalidoUsername()
    {
        $user = new User();
        $user->username = 'Zombies reversus ab inferno, nam malum cerebro. '
                        . 'De carne animata corpora quaeritis. Summus sit, '
                        . 'morbo vel maleficia? De Apocalypsi undead dictum '
                        . 'mauris. Hi mortuis soulless creaturas, imo monstra '
                        . 'adventus vultus comedat cerebella viventium.';
    }
    
    /**
     * @expectedException Core\Model\EntityException
     */
    public function testInputFilterInvalidoRole()
    {
        $user = new User();
        $user->role = 'Zombies reversus ab inferno, nam malum cerebro. '
                    . 'De carne animata corpora quaeritis. Summus sit, '
                    . 'morbo vel maleficia? De Apocalypsi undead dictum '
                    . 'mauris. Hi mortuis soulless creaturas, imo monstra '
                    . 'adventus vultus comedat cerebella viventium.';
    }
    
    public function testInsert()
    {
        $user = $this->addUser();
        
        $this->assertEquals('João das Couves', $user->name);
        $this->assertEquals(1, $user->id);
    }
    
    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Input inválido: username = 
     */
    public function testInsertInvalido()
    {
        $user = new User();
        $user->name = 'Teste';
        $user->username = '';
        
        $saved = $this->getTable('Admin\Model\User')->save($user);
    }
    
    public function testUpdate()
    {
        $tableGateway = $this->getTable('Admin\Model\User');
        
        $user = $this->addUser();        
        $id   = $user->id;
        $this->assertEquals(1, $id);
        
        $user = $tableGateway->get($id);
        
        $this->assertEquals('João das Couves', $user->name);
        
        $user->name = 'José <br>das Couves';
        $updated = $tableGateway->save($user);
        
        
        $user = $tableGateway->get($id);
        $this->assertEquals('José das Couves', $user->name);
    }
    
    /**
     * @expectedException Core\Model\EntityException
     * @expectedExceptionMessage Could not find row 1
     */
    public function testDelete()
    {
        $tableGateway = $this->getTable('Admin\Model\User');
        
        $user = $this->addUser();
        $id = $user->id;
        
        $deleted = $tableGateway->delete($id);
        
        $this->assertEquals(1, $deleted);
        $user = $tableGateway->get($id);
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
