<?php
/**
 * Entité en cours de mapping - Ajout de champ à prévoir
 * Encore au stade d'Ebauche
 * Entité gérant les status des facture, des commandes, etc...
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 */

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Status",options={"comment"="Table des Statuts commandes, factures etc..."})
 */
class Status
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="idStatus",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Traduction", cascade="persist")
     * @ORM\JoinColumn(name="translateId", referencedColumnName="translateId")
     */
    private $translateId;

    /**
     * @var string
     * @ORM\Column(name="statusColor",type="string",options={"comment"="Couleur suggérée du statut"})
     */
    private $statusColor;

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

    /**
     * Gets the value of statusColor.
     *
     * @return string
     */
    public function getStatusColor()
    {
        return $this->statusColor;
    }
    
    /**
     * Sets the value of statusColor.
     *
     * @param string $statusColor the status color
     *
     * @return self
     */
    public function setStatusColor($statusColor)
    {
        $this->statusColor = $statusColor;

        return $this;
    }
}