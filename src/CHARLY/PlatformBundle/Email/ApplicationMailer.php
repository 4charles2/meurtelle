<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: charly
 * Date: 26/12/2018
 * Time: 09:32
 */

namespace CHARLY\PlatformBundle\Email;

use CHARLY\PlatformBundle\Entity\application;


class ApplicationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * ApplicationMailer constructor.
     *
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer) {
        $this->mailer = $mailer;
    }

    /**
     * @param application $application
     */
    public function sendNewNotification(Application $application){
        if($application->getAdvert()->getEmail() != NULL ) {
            $message = new \Swift_Message(
                'Nouvelle candidature',
                'Vous avez reçu une nouvelle candidature de '
                .$application->getAuthor()
                .$application->getEmail()
                .' Voici sa réponse : '
                .$application->getContent()
                .' Cette réponse concerne votre annonce : '
                .$application->getAdvert()->getTitle()
                .' '.$application->getContent()
                .' Mise en ligne le '.$application->getDate()->getOffset()
            );
            //correction du
            $message
                ->addTo($application->getAdvert()->getEmail())
                ->addFrom('admin@charles-tognol.fr');
            $this->mailer->send($message);
        }
    }
}