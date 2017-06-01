<?php

namespace Blog\Controller;

use Blog\Model\BlogTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Form\BlogForm;
use Blog\Model\Blog;

class ListController extends AbstractActionController {

    private $table;

    public function __construct(BlogTable $table)
    {
        $this->table = $table;
    }

    public function indexAction()
    {
        return new ViewModel([
            'blogs' => $this->table->fetchAll(),
        ]);
    }

    public function createAction()
    {
        $form = new BlogForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $blog = new Blog();

        $form->setInputFilter($blog->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $blog->exchangeArray($form->getData());
        $this->table->saveBlog($blog);

        return $this->redirect()->toRoute('blog');
    }

    public function updateAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if(0 === $id) {
            return $this->redirect()->toRoute('blog', ['action' => 'create']);
        }

        try {
            $blog = $this->table->getBlog($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('blog', ['action' => 'index']);
        }

        $form = new BlogForm();
        $form->bind($blog);
        $form->get('submit')->setAttribute('value', 'Update');

        $request = $this->getRequest();

        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($blog->getInputFilter());
        $form->setData($request->getPost());

        if(!$form->isValid()) {
            return $viewData;
        }

        $this->table->saveBlog($blog);

        return $this->redirect()->toRoute('blog', ['action' => 'index']);

    }

    public function deleteAction()
    {
        return new ViewModel();
    }
}