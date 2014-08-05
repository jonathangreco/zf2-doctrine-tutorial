<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * Ce Trait sert a envoyer des logs de toutes sortes à des 
 * base de donnée, fichiers, etc..
 */
namespace Application\Service;

trait LogTrait
{
    /**
     * Ecriture de log lisible par un humain sous forme de fichier
     * $string : Contenu textuel du log
     * $dir : false ou une chaine qui sera utilisée pour donner le nom du dossier dans lequel sera écrit le fichier de log
     * $sendMail : Si true, le log est envoyé par mail
     * 
     * @example :
     * $this->getServiceLocator()->get('Application\Service\ToolBoxService')
     *      ->log("Requête en échec -> {$sql}, paramètres ".print_r($params, true));
     * 
     * @param string $string
     * @param mixed $dir
     * @param bool $sendMail
     */
    public function log($string, $dir = false, $sendMail = false)
    {
        $log_dir = 'data/logs/';
        if (!$dir)
            $dir = $log_dir.'application/';
        else
            $dir = $log_dir.$dir.'/';
        $dir .= date('Y').'/';
        if (!$this->createDir($dir))
            $this->sendMail(ContantService::MAIL_SERVER, array(ConstantService::MAIL_DEV_LOG_1, ConstantService::MAIL_DEV_LOG_2), "/!\ WARNING /!\ Log Mylink", "Le dossier de stockage des logs est introuvable ou ne dispose pas des droits en écriture : ".$dir, "Log Mylink");
        if (isset($_SERVER['REQUEST_URI'])) 
            $string .= ' [URI] '.$_SERVER['REQUEST_URI'];
        $ip = '';
        if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];
        if (!empty($ip)) 
            $string .= ' [IP] '.$ip;
        if (isset($_SERVER['REMOTE_HOST']))
            $string .= ' [FAI] '.$_SERVER['REMOTE_HOST'];
        $nom_fichier = '.'.date("Ymd").".log";
        $date = date("H:i:s");
        $string = preg_replace('#\s+#', ' ', $date.' : '.$string).PHP_EOL;
        $this->writeFile($string, $nom_fichier, $dir, false);
        if ($sendMail)
            $this->sendMail("[LOG]", $string, array(ConstantService::MAIL_DEV_LOG_1, ConstantService::MAIL_DEV_LOG_2));
    }

}
