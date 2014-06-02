<?php
/**
 * Entité en cours de mapping - Ajout de champ à prévoir
 * Encore au stade d'Ebauche
 * Entité gérant les adresses de tous les utilisateur
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Address",options={"comment"="Table des Adresse de facturation, livraison ou travail
 * de tous nos utilisateurs/Clients"})
 */
class Address
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="idAddress",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection Address $codeEntityUser
     * Inverse Side
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="codeEntityUser", cascade={"persist", "merge"})
     */
    private $codeEntityUser;

    /**
     * @var string
     * @ORM\Column(name="nameAddress",type="string")
     */
    private $nameAddress;

    /**
     * @var string
     * @ORM\Column(name="firstNameAddress",type="string")
     */
    private $firstNameAddress;

    /**
     * @var string
     * @ORM\Column(name="AddressAdress",type="string")
     */
    private $AddressAddress;

    /**
     * @var string
     * @ORM\Column(name="cityAddress",type="string")
     */
    private $cityAddress;

    /**
     * @var string
     * @ORM\Column(name="postalAddress",type="string")
     */
    private $postalAddress;

    /**
     * @var string
     * @ORM\Column(name="countryAddress",type="string")
     */
    private $countryAddress;

    /**
     * @var string
     * @ORM\Column(name="flagAddress",type="string",options={"comments"="Si c\'est une Adresse de facturation F, livraison L ou Travail T"})
     */
    private $flagAddress;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Application\Entity\Traduction", cascade="persist")
     * @ORM\JoinColumn(name="translateId", referencedColumnName="translateId")
     */ 
    private $translateId;

    /**
     * Best practises : On initialize une nouvelle collection
     */ 
    public function __construct() {
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
     * Gets the Inverse Side.
     *
     * @return ArrayCollection Address $codeEntityUser
     */
    public function getCodeEntityUser()
    {
        return $this->codeEntityUser->getValues();
    }
    
    /**
     * Sets the Inverse Side.
     *
     * @param ArrayCollection Address $codeEntityUser $codeEntityUser the code entity user
     *
     * @return self
     */
    public function setCodeEntityUser($codeEntityUser)
    {
        $this->codeEntityUser = $codeEntityUser;

        return $this;
    }

    /**
     * Gets the value of nameAddress.
     *
     * @return string
     */
    public function getNameAddress()
    {
        return $this->nameAddress;
    }
    
    /**
     * Sets the value of nameAddress.
     *
     * @param string $nameAddress the name address
     *
     * @return self
     */
    public function setNameAddress($nameAddress)
    {
        $this->nameAddress = $nameAddress;

        return $this;
    }

    /**
     * Gets the value of firstNameAddress.
     *
     * @return string
     */
    public function getFirstNameAddress()
    {
        return $this->firstNameAddress;
    }
    
    /**
     * Sets the value of firstNameAddress.
     *
     * @param string $firstNameAddress the first name address
     *
     * @return self
     */
    public function setFirstNameAddress($firstNameAddress)
    {
        $this->firstNameAddress = $firstNameAddress;

        return $this;
    }

    /**
     * Gets the value of AddressAddress.
     *
     * @return string
     */
    public function getAddressAddress()
    {
        return $this->AddressAddress;
    }
    
    /**
     * Sets the value of AddressAddress.
     *
     * @param string $AddressAddress the address address
     *
     * @return self
     */
    public function setAddressAddress($AddressAddress)
    {
        $this->AddressAddress = $AddressAddress;

        return $this;
    }

    /**
     * Gets the value of cityAddress.
     *
     * @return string
     */
    public function getCityAddress()
    {
        return $this->cityAddress;
    }
    
    /**
     * Sets the value of cityAddress.
     *
     * @param string $cityAddress the city address
     *
     * @return self
     */
    public function setCityAddress($cityAddress)
    {
        $this->cityAddress = $cityAddress;

        return $this;
    }

    /**
     * Gets the value of postalAddress.
     *
     * @return string
     */
    public function getPostalAddress()
    {
        return $this->postalAddress;
    }
    
    /**
     * Sets the value of postalAddress.
     *
     * @param string $postalAddress the postal address
     *
     * @return self
     */
    public function setPostalAddress($postalAddress)
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    /**
     * Gets the value of countryAddress.
     *
     * @return string
     */
    public function getCountryAddress()
    {
        return $this->countryAddress;
    }
    
    /**
     * Sets the value of countryAddress.
     *
     * @param string $countryAddress the country address
     *
     * @return self
     */
    public function setCountryAddress($countryAddress)
    {
        $this->countryAddress = $countryAddress;

        return $this;
    }

    /**
     * Gets the value of flagAddress.
     *
     * @return string
     */
    public function getFlagAddress()
    {
        return $this->flagAddress;
    }
    
    /**
     * Sets the value of flagAddress.
     *
     * @param string $flagAddress the flag address
     *
     * @return self
     */
    public function setFlagAddress($flagAddress)
    {
        $this->flagAddress = $flagAddress;

        return $this;
    }

    /**
     * Gets the value of translateId.
     *
     * @return integer
     */
    public function getTranslateId()
    {
        return $this->translateId;
    }
    
    /**
     * Sets the value of translateId.
     *
     * @param integer $translateId the translate id
     *
     * @return self
     */
    public function setTranslateId($translateId)
    {
        $this->translateId = $translateId;

        return $this;
    }
}