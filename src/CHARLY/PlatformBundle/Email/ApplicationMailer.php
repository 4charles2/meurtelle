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
            $message = (new \Swift_Message('Nouvelle candidature'))
                ->addTo($application->getAdvert()->getEmail())
                ->addFrom('admin@charles-tognol.fr')
                ->setBody(
                    '<div>
                        <h1>Vous avez reçu une nouvelle candidature</h1>'
                        .'<ul>
                            <li> de : '.$application->getAuthor().'</li>
                            <li>Email : '.$application->getEmail().'</li>
                        </ul>
                        <h3> Voici sa réponse : </h3>
                        <div style="border: 1px solid black; padding: 10px; text-align: centers;">
                            <p>'.$application->getContent().'</p>
                        </div>
                        <h3> Cette réponse concerne votre annonce : </h3>
                        <div style="padding: 10px; border: 1px solid black;">
                            <p>'.$application->getAdvert()->getTitle().'</p>
                            <p> '.$application->getAdvert()->getContent().'</p>
                        </div>
                        <cite>Mise en ligne le '.$application->getDate()->format('d-m-y \à h:i:s').'</cite>
                    </div>',
                    'text/html')
                ->setReadReceiptTo('charly.learn@gmail.com');


            $this->mailer->send($message);
        }
    }
}