<?php
/**
 * Entité en cours de mapping - Ajout de champ à prévoir
 * Encore au stade d'Ebauche
 * Entité permettant de lister toutes les langues de notre application
 * En rajouter une devient facile
 * @package Application
 * @author Jonathan Greco <nataniel.greco@gmail.com>
 */
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Traduction",options={"comment"="Table des Traduction de notre application
 * (tout ce qui concerne les traductions des chaines stocké en base"})
 */
class Traduction
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(name="translateId",type="integer",options={"comment"="clé étrangère permettant d\'ajouter cette ligne de traduction dans une autre table"})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $translateId;

    /**
     * @var string
     * @ORM\Column(name="en_US",type="string",options={"comment"="Traduction d\'une chaine langue(par défaut) : Anglais"})
     */
    private $en_US;

    /**
     * @var string
     * @ORM\Column(name="fr_FR",type="string",options={"comment"="Traduction d\'une chaine langue Français"})
     */
    private $fr_FR;

    /**
     * Aide pour accéder aux traductions selon la locale
     */
    public function get($locale)
    {
        return $this->$locale;
    }


    /**
     * Gets the value of translateId.
     *
     * @return int|null
     */
    public function getTranslateId()
    {
        return $this->translateId;
    }
    
    /**
     * Gets the value of en_US.
     *
     * @return string
     */
    public function getEn_US()
    {
        return $this->en_US;
    }
    
    /**
     * Gets the value of fr_FR.
     *
     * @return string
     */
    public function getFr_FR()
    {
        return $this->fr_FR;
    }
}