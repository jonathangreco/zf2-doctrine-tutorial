<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * 
 * Cette classe regroupe toutes les constantes de l'application
 * Elles sont affichable comme cela :
 * Si on est dans un namespace différent d'Application :
 * @example echo \Application\Service\ConstantService::[nomDeLaConstante]
 * 
 */

namespace Application\Service;

class ConstantService 
{
	const ENCODING = 'UTF-8';
	const MAIL_SERVER = 'nataniel.greco@laposte.net';
	const MAIL_DEV_LOG_1 = "nataniel.greco@laposte.net";
	const MAIL_DEV_LOG_2 = "nataniel.greco@laposte.net";
}