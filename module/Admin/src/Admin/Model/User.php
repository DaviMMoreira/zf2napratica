<?php

namespace Admin\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Core\Model\Entity;

/**
 * Description of User
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 * @category Admin
 */
class User extends Entity
{
    /**
     * @var string
     */
    protected $tableName = 'users';    
    /**
     * @var int
     */
    protected $id;    
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $valid;
    /**
     * @var string
     */
    protected $role;
    
    /**
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'id',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'Int' )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'username',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'StripTags' ),
                    array ( 'name' => 'StringTrim' ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 50
                        )
                    )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'password',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'StripTags' ),
                    array ( 'name' => 'StringTrim' ),
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'name',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'StripTags' ),
                    array ( 'name' => 'StringTrim' ),
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'valid',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'Int' )
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name' => 'role',
                'required' => true,
                'filters' => array(
                    array ( 'name' => 'StripTags' ),
                    array ( 'name' => 'StringTrim' ),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 20
                        )
                    )
                )
            )));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }
    
}
