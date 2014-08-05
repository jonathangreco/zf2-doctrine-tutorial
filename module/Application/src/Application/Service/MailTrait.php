<?php
/**
 * @package Application
 * @author Jonathan Greco <jgreco@docsourcing.com>
 * @author Bastien Hérault <bherault@docsourcing.com> 
 * Ce Trait sert à opérer des envois de mail
 */
namespace Application\Service;

trait MailTrait
{
    /**
     * Envoi de mail via le module AcMailer
     * $subject : Sujet de l'email
     * $content : Contenu de l'email
     * $address : adresse email ou tableau d'adresses email du ou des destinataires
     * $mailFrom : adresse email du sender
     * $fromName : false ou nom affiché du sender (si false, reprend l'adresse email du sender)
     * $replyTo : false ou adresse email de réponse (si false, reprend l'adresse email du sender)
     * $attachment : false ou tableau contenant le(s) chemin(s) vers le(s) fichier(s) depuis la racine
     * $bcc : true si on veut que les adresses figurant dans $address soient en copie cachées
     * 
     * @example :
     * $this->getServiceLocator()->get('Application\Service\ToolBoxService')
     *     ->sendMail(
     *         'Un test de mail en local', 
     *         'Un test pour voir si sa part',
     *         array(
     *             \Application\Service\ConstantService::MAIL_DEV_LOG_1,
     *             \Application\Service\ConstantService::MAIL_DEV_LOG_2,
     *         ),
     *         'jgreco@docsourcing.com',
     *         'Jonathan'
     *     );
     * @param string $subject 
     * @param string $content 
     * @param array $address
     * @param string $mailFrom
     * @param string $fromName
     * @param array $replyTo 
     * @param array $attachment 
     * @param bool $bcc 
     * @return bool
     */
    public function sendMail($subject, $content, $address, $mailFrom = '', $fromName = '', $replyTo = array(), $attachment = array(), $bcc = false)
    {
        if (!is_array($address)) 
            $address = array($address);
        if (empty($mailFrom)) 
        {
            $mailFrom = ConstantService::MAIL_SERVER;
            if (empty($fromName)) 
                $fromName = 'Your project';
        }
        if (empty($fromName)) 
            $fromName = $mailFrom;
        if (empty($replyTo)) 
            $replyTo = array($mailFrom => $fromName);
        if (!is_array($replyTo)) 
            $replyTo = array($replyTo);
        $msg = \Swift_Message::newInstance();
        $msg->setSubject($subject);
        $msg->setFrom(array($mailFrom => $fromName));
        $msg->setSender(array($mailFrom => $fromName));
        $msg->setReplyTo($replyTo);
        // Pour tests en local
        if (!getenv('APPLICATION_ENV') || getenv('APPLICATION_ENV')=='development') 
            $address = array(ConstantService::MAIL_DEV_LOG_1, ConstantService::MAIL_DEV_LOG_2);
        foreach ($address as $k => $v) 
        {
            if ($k == 0)
            {
                if ($bcc) 
                    $msg->setBcc($v);
                else 
                    $msg->setTo($v);
            }
            else
            {
                if ($bcc) 
                    $msg->addBcc($v);
                else 
                    $msg->addTo($v);
            }
        }
        if (is_string($content) && $content != strip_tags($content)) 
        {
            $msg->setBody($content, 'text/html');
            $msg->addPart(strip_tags($content), 'text/plain');
        } else {
            $msg->addPart($content, 'text/plain');
        }
        if (!empty($attachment))
        {
            if (!is_array($attachment)) 
                $attachment = array($attachment);
            foreach ($attachment as $file) 
                $msg->attach(\Swift_Attachment::fromPath($file));
        }
        $result = $this->getServiceLocator()->get('Application\Service\MailService')->send($msg);
        if ($result!=count($address)) 
        {
            $this->log("Echec d'envoi de mail : ".(count($address)-$result)." adresse(s) email en échec / ".count($address)." !");
            return false;
        }
        return true;
    }
}
