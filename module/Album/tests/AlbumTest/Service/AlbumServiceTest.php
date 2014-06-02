<?php

/**
 * Class permettant de tester notre Service Album
 * On récupère l'entityManager de notre factory directmeent en étandant notre classe de Test perso
 * C'est cette classe de test qui étends PHPUnit_Framework_TestCase. Donc cette classe peut
 * utiliser correctement toutes les méthodes de tests disponibles.
 * 
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
 */


namespace AlbumTest\Service;

use AlbumTest\Framework\TestCase;
use Album\Entity\Album;

class AlbumServiceTest extends TestCase
{

    public function setUp()
    {
        $this->em = $this->getEntityManager();
        parent::setUp();
    }

    /**
     * [On vérifie qu'a la déclaration d'un nouvel album on est bien les champs vide]
     */
    public function testAlbumInitialState()
    {
        $album = new Album();

        $this->assertNull($album->getArtist(), '"artist" should initially be null');
        $this->assertNull($album->getId(), '"id" should initially be null');
        $this->assertNull($album->getTitle(), '"title" should initially be null');
    }

    /**
    * Le test créée un mock de la table Album on essaye d'y récupérer les album par le mock
    * et tester que l'on obtien bien un array et on teste également que le premier de cet array soit bien
    * une instance de Album, ceci nous permet de déduire que c'est une collection
    */
    public function testCanFetchAllAlbum()
    {
        //On fait un findAll sur l'entité Album en utilisant l'entityManager
        $Album = $this->em->getRepository('Album\Entity\Album')->findAll();
        //On récupère le premier élement de l'array
        $first = $Album[0];
        //On controle que ce qu'on récupère est bien un array
        $this->assertInternalType('array', $Album);
        //On controle que ce premier élement est de type Album, ce qui nous donne une collection
        $this->assertInstanceOf('Album\Entity\Album', $first);
    }

    public function testCanFetchOneAlbum()
    {
        $Album =  $this->em->getRepository('Album\Entity\Album')->find(1);
        $this->assertInstanceOf('Album\Entity\Album', $Album);
    }

    public function testCantFetchOneAlbumBecauseInvalidId()
    {
        $Album =  $this->em->getRepository('Album\Entity\Album')->find(600000);
        $this->assertNotInstanceOf('Album\Entity\Album', $Album);
    }
}