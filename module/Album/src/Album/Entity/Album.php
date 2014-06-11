<?php
/**
 * Classe représentant l'entité Album avec Doctrine
 * Une entité ne dois contenir que :
 * les propriété de la table 
 * les getters
 * les setters
 * Et rien d'autre
 * 
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Album\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Album")
 */
class Album
{    
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=100, unique=false)
     */
    private $artist;

    /**
      * @ORM\Column(type="string", length=100, unique=false)
     */
    private $title;

    /**
     * @return null|string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param null|string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}