<?php
/**
 * 
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */
 
namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Entity\Album;
use Album\Service\AlbumService;
use Album\Form\AlbumForm;

class AlbumController extends AbstractActionController
{
    /**
     * @var Album\Service\AlbumService
     */ 
    private $album;

    public function indexAction()
    {
        $this->album = $this->getServiceLocator()->get('AlbumService');
        return new ViewModel(
            array(
                'albums' => $this->album->fetchAll(),
            )
        );
    }

    public function addAction()
    {
        $this->album = $this->getServiceLocator()->get('AlbumService');
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $album = new Album();
            $form->setInputFilter($this->album->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $album->exchangeArray($form->getData());
                $this->album->saveAlbum($album);

                // Redirect to list of albums
                return $this->redirect()->toRoute('album');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        
        $this->album = $this->getServiceLocator()->get('AlbumService');

        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'add'
            ));
        }
        // On va chercher l'album concerné par la demande de modif
        // s'il n'est pas trouvé une exception est levé et redirigé sur l'accueil
        try {
            $Album = $this->album->getAlbum($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('album', array(
                'action' => 'index'
            ));
        }
        $form  = new AlbumForm();
        $form->bind($Album);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($this->album->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->album->saveAlbum($Album);
                return $this->redirect()->toRoute('album');
            }
        }
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $this->album = $this->getServiceLocator()->get('AlbumService');
        
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->album->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return array(
            'id'    => $id,
            'album' => $this->album->getAlbum($id)
        );
    }
}