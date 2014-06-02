<?php
/**
 * @package Album
 * @author Jonathan Greco <jgreco@docsourcing.com>
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
        $perPage = ($this->params()->fromRoute('mode')!=null)?$this->params()->fromRoute('mode'):30;
        if($request->isPost()) {
            $perPage = $request->getPost()->get('perPage');
        }
        $paginator->setDefaultItemCountPerPage($perPage);
        $page = (int)$this->params()->fromRoute('page');
        if($page) $paginator->setCurrentPageNumber($page);

        $this->flashMessenger()->addMessage(array('success'=>'Welcome on Album module by Jonathan Greco'));

        $view->setVariable('paginator',$paginator);
        $view->setVariable('perPage', $perPage);
        $view->setVariable('flashMessages', $this->flashMessenger()->getMessages());

        return $view;
    }

    /**
     * Ajout d'un album
     */ 
    public function addAction()
    {
        /** @var AddAlbumForm $form */
        $form = $this->getServiceLocator()->get('formElementManager')->get('Album\Form\AddAlbum');
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album = $form->getData();
                $this->albumService->addAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(
            array(
                'form' => $form
            )
        );
    }

    /**
     * Editer un album
     */ 
    public function editAction()
    {
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
                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'id' => $id
        ));

    }

    /**
     * Suppression d'un album
     */ 
    public function deleteAction()
    {
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
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(
            array(
                'id'    => $id,
                'album' => $album
            )
        );
    }
}