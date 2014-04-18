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
}
