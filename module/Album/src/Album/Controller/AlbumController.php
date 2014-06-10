<?php
/**
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 */

namespace Album\Controller;
use Zend\Paginator\Paginator;

use Album\Form\AddAlbumForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Entity\Album;
use Album\Service\AlbumService;
use Album\Form\AlbumForm;

class AlbumController extends AbstractActionController
{
    /**
     * @var AlbumService
     */
    private $albumService;

    /**
     * AlbumService est injecté en tant que dependance par une factory
     * cette factory est appelée dans la config
     */ 
    public function __construct(AlbumService $albumService)
    {
        $this->albumService = $albumService;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $view =  new ViewModel();
        $adapter = $this->albumService->getAdapter();
        $paginator = new Paginator($adapter);
        $perPage = ($this->params()->fromRoute('mode') != null) ? $this->params()->fromRoute('mode') : 30;
        if($request->isPost()) {
            $perPage = $request->getPost()->get('perPage');
        }
        $paginator->setDefaultItemCountPerPage($perPage);
        $page = (int)$this->params()->fromRoute('page');
        if($page) $paginator->setCurrentPageNumber($page);

        $view->setVariable('paginator',$paginator);
        $view->setVariable('perPage', $perPage);
        

        return $view;
    }

    /**
     * Ajout d'un album
     */ 
    public function addAction()
    {
        $view =  new ViewModel();
        /** @var AddAlbumForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('Album\Form\AddAlbum');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album = $form->getData();
                $this->albumService->addAlbum($album);
                $this->flashMessenger()->setNamespace('success')->addMessage('Your album has been added.');
                // Redirect to list of albums
                $this->redirect()->toRoute('album');
            }
        }
        $view->setVariable('form', $form);
        return $view;
    }

    /**
     * Editer un album
     */ 
    public function editAction()
    {
        $view =  new ViewModel();
        $id = (int)$this->params()->fromRoute('id', 0);
        $album = $this->albumService->getAlbum($id);

        if(!$album) {
            return $this->redirect()->toRoute('album');
        }

        $form = $this->getServiceLocator()->get('formElementManager')->get('Album\Form\EditAlbum');
        $form->bind($album);
        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $album = $form->getData();
                $this->albumService->updateAlbum($album);
                 $this->flashMessenger()->setNamespace('success')->addMessage('Your album has been edited.');
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        $view->setVariable('form', $form);
        $view->setVariable('id', $id);
        return $view;

    }

    /**
     * Suppression d'un album
     */ 
    public function deleteAction()
    {
        $view =  new ViewModel();
        $id = (int)$this->params()->fromRoute('id', 0);

        $album = $this->albumService->getAlbum($id);
        if (!$album) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('response', 'no');
            if ($del == 'yes') {
                $this->albumService->deleteAlbum($album);
                 $this->flashMessenger()->setNamespace('success')->addMessage('Your album has been deleted.');
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        $view->setVariable('album', $album);
        $view->setVariable('id', $id);

        return $view;
    }
}