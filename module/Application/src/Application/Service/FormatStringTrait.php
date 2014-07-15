<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * Ce Trait sert a opérer des formatages sur les chaines de caractères
 */
namespace Application\Service;

trait FormatStringTrait
{
    
    /**
     * Renvoit une chaine sécurisée pour l'affichage
     * $html : Indique si on conserve ou pas le HTML
     * 
     * @param string $str
     * @param bool $html
     * @return string
     */
    public function safeOutput($str, $html = false)
    {
        $str = stripslashes($str);
        if (!$html)
            $str = strip_tags($str);
        return htmlentities($str, ENT_QUOTES, ConstantService::ENCODING);
    }
}