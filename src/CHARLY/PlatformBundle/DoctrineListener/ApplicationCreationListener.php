<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: PC
 * Date: 28/12/2018
 * Time: 11:50
 */

namespace CHARLY\PlatformBundle\DoctrineListener;


use CHARLY\PlatformBundle\Entity\Application;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use CHARLY\PlatformBundle\Email\ApplicationMailer;

class ApplicationCreationListener
{
    private $applicationMailer;

    public function __construct(ApplicationMailer $applicationMailer)
    {
        $this->applicationMailer = $applicationMailer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        //On ne veut envoyer un email que pour les entités Application
        if(!$entity instanceof Application){
            return;
        }

        $this->applicationMailer->sendNewnotification($entity);
    }
}