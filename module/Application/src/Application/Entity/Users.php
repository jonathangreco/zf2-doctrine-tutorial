<?php
/**
 * Entité en cours de mapping - Ajout de champ à prévoir
 * Encore au stade d'Ebauche
 * Entité gérant les Users
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 * @author Florent Blaison <florent.blaison@gmail.com>
 */

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users",options={"comment"="Table des Utilisateurs"})
 */
class Users
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="idUser",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
     
    /**
     * @var string
     * @ORM\Column(name="nameUsers",type="string")
     */
    private $nameUsers;

    /**
     * @var string
     * @ORM\Column(name="firstNameUsers",type="string")
     */
    private $firstNameUsers;

    /**
      * @var ArrayCollection Users_Address
      * Owning Side
      * @ORM\ManyToMany(targetEntity="Address",inversedBy="codeEntityUser",cascade={"persist", "merge"})
      * @ORM\JoinTable(name="Users_Address",
      *   joinColumns={@ORM\JoinColumn(name="Users_idUser", referencedColumnName="idUser")},
      *   inverseJoinColumns={@ORM\JoinColumn(name="Address_idAddress", referencedColumnName="idAddress")}
      * )
      */
    private $codeEntityUser;
     

    /**
     * determine si le user à un parent, excepté les user Primaire, tous en ont un.
     * @var ArrayCollection  Users_Parents
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="myParents")
     */
    private $userParent;

    /**
     * @ORM\ManyToMany(targetEntity="Users", inversedBy="userParent")
     * @ORM\JoinTable(name="Users_Parents",
     *     joinColumns={@ORM\JoinColumn(name="idUser", referencedColumnName="idUser")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="idUserParent", referencedColumnName="idUser")}
     * )
     */
    private $myParents;

    /**
     * Best practises : On initialize une nouvelle collection
     */ 
    public function __construct() {
        $this->myParents = new ArrayCollection;
        $this->codeEntityUser = new ArrayCollection;
    }

    /**
     * Gets the value of id.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the value of id.
     *
     * @param int|null $id the id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of nameUsers.
     *
     * @return string
     */
    public function getNameUsers()
    {
        return $this->nameUsers;
    }
    
    /**
     * Sets the value of nameUsers.
     *
     * @param string $nameUsers the name users
     *
     * @return self
     */
    public function setNameUsers($nameUsers)
    {
        $this->nameUsers = $nameUsers;

        return $this;
    }

    /**
     * Gets the value of firstNameUsers.
     *
     * @return string
     */
    public function getFirstNameUsers()
    {
        return $this->firstNameUsers;
    }
    
    /**
     * Sets the value of firstNameUsers.
     *
     * @param string $firstNameUsers the first name users
     *
     * @return self
     */
    public function setFirstNameUsers($firstNameUsers)
    {
        $this->firstNameUsers = $firstNameUsers;

        return $this;
    }

    /**
     * Gets the Owning Side
     * joinColumns={@ORM\JoinColumn(name="Users_idUser", referencedColumnName="idUser")},
     * inverseJoinColumns={@ORM\JoinColumn(name="Address_idAddress", referencedColumnName="idAddress")}
     * ).
     *
     * @return ArrayCollection Users_Address
     */
    public function getCodeEntityUser()
    {
        return $this->codeEntityUser->getValues();
    }
    
    /**
     * Sets the Owning Side
     * joinColumns={@ORM\JoinColumn(name="Users_idUser", referencedColumnName="idUser")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="Address_idAddress", referencedColumnName="idAddress")}
     *  ).
     *
     * @param ArrayCollection Users_Address $codeEntityUser the code entity user
     *
     * @return self
     */
    public function setCodeEntityUser($codeEntityUser)
    {
        $this->codeEntityUser = $codeEntityUser;

        return $this;
    }

    /**
     * Gets the determine si le user à un parent, excepté les user Primaire, tous en ont un..
     *
     * @return integer
     */
    public function getUserParent()
    {
        return $this->userParent;
    }
    
    /**
     * Sets the determine si le user à un parent, excepté les user Primaire, tous en ont un..
     *
     * @param integer $userParent the user parent
     *
     * @return self
     */
    public function setUserParent($userParent)
    {
        $this->userParent = $userParent;

        return $this;
    }

    /**
     * Gets the joinColumns={@ORM\JoinColumn(name="idUser", referencedColumnName="idUser")},
     * inverseJoinColumns={@ORM\JoinColumn(name="idUserParent", referencedColumnName="idUser")}
     * ).
     *
     * @return mixed
     */
    public function getMyParents()
    {
        return $this->myParents->getValues();
    }
    
    /**
     * Sets the joinColumns={@ORM\JoinColumn(name="idUser", referencedColumnName="idUser")},
     * inverseJoinColumns={@ORM\JoinColumn(name="idUserParent", referencedColumnName="idUser")}
     * ).
     *
     * @param mixed $myParents the my parents
     *
     * @return self
     */
    public function setMyParents($myParents)
    {
        $this->myParents = $myParents;

        return $this;
    }
}