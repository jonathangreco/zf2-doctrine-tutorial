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
     * [On vérifie qu'a la déclaration d'un nouel album on est bien les champs vide]
     */
	public function testAlbumInitialState()
	{
		$album = new Album();

		$this->assertNull($album->artist, '"artist" should initially be null');
		$this->assertNull($album->id, '"id" should initially be null');
        $this->assertNull($album->title, '"title" should initially be null');
	}

    /**
     * On vérifie que si l'id n'existe pas on ai bien une erreur
     */ 
    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $album = new Album();
        $data  = array('artist' => 'some artist',
                       'id'     => 123,
                       'title'  => 'some title');

        $album->exchangeArray($data);

        $this->assertSame($data['artist'], $album->artist, '"artist" was not set correctly');
        $this->assertSame($data['id'], $album->id, '"id" was not set correctly');
        $this->assertSame($data['title'], $album->title, '"title" was not set correctly');
    }

    public function testExchangeArraySetsPropertiesToNullIfKeysAreNotPresent()
    {
        $album = new Album();

        $album->exchangeArray(array('artist' => 'some artist',
                                    'id'     => 123,
                                    'title'  => 'some title'));
        $album->exchangeArray(array());

        $this->assertNull($album->artist, '"artist" should have defaulted to null');
        $this->assertNull($album->id, '"id" should have defaulted to null');
        $this->assertNull($album->title, '"title" should have defaulted to null');
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


}