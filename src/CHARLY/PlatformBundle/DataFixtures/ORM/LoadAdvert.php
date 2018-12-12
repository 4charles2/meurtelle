<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: charly
 * Date: 11/12/2018
 * Time: 22:43
 */

namespace CHARLY\PlatformBundle\DataFixtures\ORM;


use CHARLY\PlatformBundle\Entity\Application;
use CHARLY\PlatformBundle\Entity\Advert;
use CHARLY\PlatformBundle\Entity\Image;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAdvert implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $adverts = array(
            [
                'title' => 'Recherche developpeur Symfony',
                'id' => '1',
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un developpeur Symfony sur Lyon',
                'date' => new \DateTime(),
                'url' => "/image/symfony.jpg",
                'alt' => "logo symfony"
            ],
            [
                'title' => 'Mission de webmaster',
                'id' => '2',
                'author' => 'Hugo',
                'content' => 'Nous recherchon un webmaster capable de maintenir notre site internet',
                'date' => new \DateTime(),
                'url' => '/image/webmaster.png',
                'alt' => 'logo webmaster'
            ],
            [
                'title' => 'Offre stage de webdesigner',
                'id' => '3',
                'author' => 'Mathieu',
                'content' => 'Nous recherchons un webdesigner',
                'date' => new \DateTime(),
                'url' => '/image/webdesigner.jpg',
                'alt' => 'logo webdesigner'
            ],
            [
                'title' => 'Developpeur Web Full Stack',
                'author' => 'Charles',
                'content' => 'Recherche un developpeur Full Stack PHP Javascript HTML5 CSS3 Androïd Java',
                'date' => new \DateTime(),
                'url' => '/image/devFullStack.jpg',
                'alt' => 'dev Full Stack',
                'applications' => [
                    [
                        'content' => 'Votre Offre correspond en tous points à ce que je recherche tous mon savoir faire à votre service',
                        'author' => 'anonymous'
                    ],
                    [
                        'content' => 'La qualite de vos services accompagner de ma motivation ferons des étincelles',
                        'author' => 'motidev'
                    ],
                    [
                        'content' => 'Je veux bien faire un stage chez vous mais je veux 1500€',
                        'author' => 'stagiaire'
                    ]
                ]
            ]
        );

        foreach ($adverts as $advert){
            //On créer les annonces
            $ad = new Advert();
            $ad->setContent($advert['content']);
            $ad->setAuthor($advert['author']);
            $ad->setTitle($advert['title']);

            $image = new Image();
            $image->setUrl($advert['url']);
            $image->setAlt($advert['alt']);

            $ad->setImage($image);
            if(isset($advert['applications'])){
                foreach ($advert['applications'] as $application){
                    $apply = new Application();
                    $apply->setContent($application['content']);
                    $apply->setAuthor($application['author']);
                    $apply->setAdvert($ad);
                    $manager->persist($apply);
                }
            }
            $manager->persist($ad);
        }
        $manager->flush();
    }
}