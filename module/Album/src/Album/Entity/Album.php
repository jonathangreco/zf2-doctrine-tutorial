<?php
/**
 * Classe représentant l'entité Album avec Doctrine
 * 
 * @package Album
 * @author Jonathan Greco <nataniel.greco@gmail.com>  
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
    public $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=48, unique=false)
     */
    public $artist;

    /**
 	 * @ORM\Column(type="string", length=48, unique=false)
     */
    public $title;

     /**
     * @ORM\Column(type="integer", options={"default"=1})
     */
    protected $discs;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->artist = (isset($data['artist'])) ? $data['artist'] : null;
        $this->title  = (isset($data['title'])) ? $data['title'] : null;
    }
}