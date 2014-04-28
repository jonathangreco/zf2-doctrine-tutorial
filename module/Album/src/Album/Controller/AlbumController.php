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
        if (!$id) {
            return $this->redirect()->toRoute(
                'album',
                array(
                    'action' => 'add'
                )
            );
        }
        // On va chercher l'album concerné par la demande de modif
        // s'il n'est pas trouvé une exception est levé et redirigé sur l'accueil
        try {
            $album = $this->albumService->getAlbum($id);
        } catch (\Exception $ex) {
            return $this->redirect()->toRoute(
                'album',
                array(
                    'action' => 'index'
                )
            );
        }
        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($this->albumService->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->albumService->saveAlbum($album);

                return $this->redirect()->toRoute('album');
            }
        }

        return new ViewModel(
            array(
                'id'   => $id,
                'form' => $form,
            )
        );
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