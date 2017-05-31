<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel();
    }

    public function createAction()
    {
        return new ViewModel();
    }

    public function updateAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        return new ViewModel();
    }
}