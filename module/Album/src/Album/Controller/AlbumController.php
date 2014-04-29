<?php
/**
 *
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Album\Controller;

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

    public function __construct(AlbumService $albumService)
    {
        $this->albumService = $albumService;
    }

    public function indexAction()
    {
        $albums = $this->albumService->getAll();

        return new ViewModel(
            array(
                'albums' => $albums,
            )
        );
    }

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

    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if(!$id) {
            return $this->redirect()->toRoute('album');
        }
        $album = $this->albumService->getAlbum($id);

        $form = $this->getServiceLocator()->get('formElementManager')->get('Album\Form\AddAlbum');
        $form->get('submit')->setAttribute('value', 'Edit');

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

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->albumService->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return new ViewModel(
            array(
                'id'    => $id,
                'album' => $this->albumService->getAlbum($id)
            )
        );
    }
}