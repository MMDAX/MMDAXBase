<?php

/**
 * Mateus Macedo Dos Anjos
 *
 * @copyright Copyright (c) 2013-2015 MMDAX Sofware BRA Inc.
 */

namespace MMDAXBase\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\ArrayAdapter;
use Zend\View\Helper\AbstractHelper;

/**
 * Description of AbstractCrudController
 *
 * @author mateus
 */
abstract class AbstractCrudController extends AbstractActionController
{

    /**
     *
     * @var EntityManager 
     */
    protected $em;

    /**
     *
     * @var array 
     */
    protected $namespaces = array(
        'service' => null,
        'entity' => null,
        'form' => null
    );

    /**
     *
     * @var array
     */
    protected $route = array(
        'name' => null,
        'controller' => null
    );

    /**
     * 
     * @return EntityManager
     */
    public function getEm()
    {
        if ($this->em == null) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }

        return $this->em;
    }

    /**
     * 
     * @param type $helperName
     * @return AbstractHelper
     */
    protected function getViewHelper($helperName)
    {
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
    }

    protected function getPartialObjKey()
    {
        $objectKey = array_reverse(explode('\\', $this->namespaces['entity']));

        return strtolower($objectKey[0]);
    }

    /**
     * 
     * @return ViewModel
     */
    public function indexAction()
    {
        $placeholder = $this->getServiceLocator()->get('viewhelpermanager')
                ->get('Placeholder');
        $placeholder('url')->urlEdit = $this->url()->fromRoute($this->route['name'], array('controller' => $this->route['controller'], 'action' => 'edit'));
        $placeholder('url')->urlDelete = $this->url()->fromRoute($this->route['name'], array('controller' => $this->route['controller'], 'action' => 'delete'));
        $list = $this->getEm()
                ->getRepository($this->namespaces['entity'])
                ->findAll();
        $page = $this->params()->fromRoute('page', 1);
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
                ->setDefaultItemCountPerPage(10);
        $this->getServiceLocator()->get('viewhelpermanager')
                ->get('partialLoop')->setObjectKey($this->getPartialObjKey());
        $urlNew = $this->url()->fromRoute($this->route['name'], array('controller' => $this->route['controller'], 'action' => 'new'));
        return new ViewModel(array(
            'paginator' => $paginator,
            'page' => $page,
            'urlNew' => $urlNew,
        ));
    }

    /**
     * 
     * @return ViewModel
     */
    public function newAction()
    {
        $form = $this->getServiceLocator()->get($this->namespaces['form']);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get($this->namespaces['service']);
                $service->insert($this->getRequest()->getPost()->toArray());

                return $this->redirect()->toRoute($this->route['name'], array('controller' => $this->route['controller'], 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    /**
     * 
     * @return ViewModel
     */
    public function editAction()
    {
        $form = $this->getServiceLocator()->get($this->namespaces['form']);
        $request = $this->getRequest();

        $repository = $this->getEm()->getRepository($this->namespaces['entity']);
        $entity = $repository->find($this->params()->fromRoute('id', 0));

        if ($this->params()->fromRoute('id', 0)) {
            $form->setData($entity->toArray());
        }

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $service = $this->getServiceLocator()->get($this->namespaces['service']);
                $service->update($request->getPost()->toArray());

                return $this->redirect()->toRoute($this->route['name'], array('controller' => $this->route['controller'], 'action' => 'index'));
            }
        }

        return new ViewModel(array('form' => $form));
    }

    /**
     * 
     * 
     */
    public function deleteAction()
    {
        $service = $this->getServiceLocator()->get($this->namespaces['service']);
        if ($service->delete($this->params()->fromRoute('id', 0))) {
            return $this->redirect()->toRoute($this->route['name'], array('controller' => $this->route['controller']));
        }
    }

}
