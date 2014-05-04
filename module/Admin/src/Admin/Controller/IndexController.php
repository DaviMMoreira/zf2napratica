<?php

namespace Admin\Controller;

use Zend\View\Model\ViewModel;
use Core\Controller\ActionController;
use Application\Model\Post;
use Application\Form\Post as PostForm;

/**
 * Description of IndexController
 *
 * @author Davi Marcondes Moreira <davi.marcondes.moreira@gmail.com>
 */
class IndexController extends ActionController
{
    /**
     * @return \Zend\View\Model\ViewModel|Response
     */
    public function saveAction()
    {
        $form = new PostForm();
        
        $request = $this->getRequest();
        if ( $request->isPost() )
        {
            $post = new Post();
            
            $form->setInputFilter($post->getInputFilter());
            $form->setData($request->getPost());
            if ( $form->isValid() )
            {
                $data = $form->getData();
                unset ( $data['submit'] );
                $data['post_date'] = date ( 'Y-m-d H:i:s' );
                
                $post->setData($data);
                
                $saved = $this->getTable('Application\Model\Post')->save($post);
                
                return $this->redirect()->toUrl('/');
            }            
        }
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if ( $id > 0 )
        {
            $post = $this->getTable('Application\Model\Post')->get($id);
            
            $form->bind($post);
            $form->get('submit')->setAttribute('value', 'Edit');
        }
        
        return new ViewModel(
            array('form' => $form)
        );
    }
    
    /**
     * 
     * @return Response
     * @throws \Exception
     */
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ( $id == 0 )
        {
            throw new \Exception("Código obrigatório");
        }
        
        $this->getTable('Application\Model\Post')->delete($id);
        
        return $this->redirect()->toUrl('/');
    }
}
