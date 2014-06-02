<?php
/**
 * Test Simple de contrôleur
 * Pas besoin d'entityManager
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */

namespace AlbumTest\Controller;

use Zend\Http\Request as HttpRequest;
use AlbumTest\AlbumBootstrap;
use Album\Controller\AlbumController;
use Album\Service\AlbumService;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;


class AlbumControllerTest extends AbstractHttpControllerTestCase {

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../TestConfig.php.dist'
        );

        parent::setUp();
    }


    public function testAlbumActionCanBeAccessed()
    {
        /**
         * Permet de l'ancer l'url de manière fictive pour les test.
         * Ici on test album et on vérifie qu'on as bien une réponse 200
         * on vérifie aussi qu'on as bien demandé la route album
         * Le controlleur album
         * et le module Album
         */
        $this->dispatch('/album');
        $this->assertResponseStatusCode(200);
        $this->assertMatchedRouteName('album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertModuleName('Album');
    }

    /**
     * on test qu'ajouter un Album soit disponible
     */ 
    public function testAddActionCanBeAccessed()
    {
        $this->dispatch('/album/add');
        $this->assertResponseStatusCode(200);
        $this->assertActionName('add');
        $this->assertNotActionName('edit');
    }


    /**
     * On test qu'un album ne peut être inséré s'il est invalide
     */ 
    public function testAddActionCannotInsertInvalidAlbum()
    {
        $post = array(
            'artist' => 'Led Zeppelin',
            'title' => '',
            'discs' => 1
        );
        $this->dispatch('/album/add', HttpRequest::METHOD_POST, $post);
        //Avec cette assertion on controle bien que notre message d'erreur est
        //apparue (balise p) et non pas la balise ul li standard.
        $this->assertQuery('form p');
        $this->assertNotQuery('form ul li');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('album');
    }

    /**
     * On tests si l'edition du premier album est possible
     */ 
    public function testEditActionCanBeAccessed()
    {
        $this->dispatch('/album/edit/2');
        $this->assertResponseStatusCode(200);
        $this->assertActionName('edit');
        $this->assertNotActionName('add');
    }

    /**
     * On teste que sans titre, l'edition ne soit pas autorisée
     */ 
    public function testEditActionCannotUpdateWithInvalidData()
    {
        $post = array(
            'artist' => 'Dido',
            'title' => '',
            'discs' => 1
        );
        $this->dispatch('/album/edit/2', HttpRequest::METHOD_POST, $post);
        //Avec cette assertion on controle bien que notre message d'erreur est
        //apparue (balise p) et non pas la balise ul li standard.
        $this->assertQuery('form p');
        $this->assertNotQuery('form ul li');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('album');
    }

    /**
     * On teste que l'ajout d'un album peut se faire
     */ 
    public function testAddActionCanInsertNewAlbum()
    {
        $post = array(
            'id' => '',
            'artist' => 'Led Zeppelin',
            'title' => 'Led Zeppelin III',
            'discs' => 1
        );
        $this->dispatch('/album/add', HttpRequest::METHOD_POST, $post);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('add');
        $this->assertMatchedRouteName('album');
    }

    /**
     * On tests si la page de suppression de l'album 1 est possible
     * Pour le test, un id doit exister sinon erreur...
     */
    public function testDeleteActionCanBeAccessed()
    {
        $this->dispatch('/album/delete/2');
        $this->assertResponseStatusCode(200);
        $this->assertActionName('delete');
        $this->assertNotActionName('add');
    }

    /**
     * On modifie l'album 1 et on verifie qu'après l'update on soit
     * bien redirigé sur la page d'accueil
     */ 
    public function testEditActionRedirectAfterUpdate()
    {
        $post = array(
            'id' => 2,
            'artist' => 'Dido',
            'title' => 'No Angel'
        );
        $this->dispatch('/album/edit/2', HttpRequest::METHOD_POST, $post);

        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('album');
    }

    /**
     * On verifie que si l'edition n'a pas d'id dans l'url on ai une erreur 404
     */ 
    public function testEditActionBadRequestCausedByMissingId()
    {
        $this->dispatch('/album/edit');

        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('edit');
        $this->assertMatchedRouteName('album');
    }

    /**
     * On verifie que si la suppression n'a pas d'id dans l'url
     * on renvoie une erreur 404
     */ 
    public function testDeleteActionBadRequestCausedByMissingId()
    {
        $this->dispatch('/album/delete');

        $this->assertResponseStatusCode(302);
        $this->assertModuleName('Album');
        $this->assertControllerName('Album\Controller\Album');
        $this->assertControllerClass('AlbumController');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('album');
    }

}
