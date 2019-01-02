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
use CHARLY\PlatformBundle\Entity\Skill;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadAdvert implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $adverts = $this->dataAdvert();

        $categories = LoadCategory::createCategories($manager);

        $skills = LoadSkill::createSkills($manager);

        foreach ($adverts as $advert){
            //On créer les annonces
            $ad = new Advert();
            $ad->setContent($advert['content']);
            $ad->setAuthor($advert['author']);
            $ad->setTitle($advert['title']);
            $ad->setDate($advert['date']);
            isset($advert['email']) ? $ad->setEmail($advert['email']) : '';

            $image = new Image();
            $image->setUrl($advert['url']);
            $image->setAlt($advert['alt']);

            if(isset($advert['categories'])){
                foreach($advert['categories'] as $category)
                    $ad->addCategory($categories[$category]);
            }
            if(isset($advert['skills'])){
                foreach($advert['skills'] as $skill)
                    $skill;
                    //todo apres avoir fait la relation bidirectionnel advertSkill
                    //$ad->addSkill($skills[$skill]);
            }
            $ad->setImage($image);
            if(isset($advert['applications'])){
                foreach ($advert['applications'] as $application){
                    $apply = new Application();
                    $apply->setContent($application['content']);
                    $apply->setAuthor($application['author']);
                    isset($application['email']) ? $apply->setEmail($application['email']) : '';

                    $ad->addApplication($apply);
                    $manager->persist($apply);

                    
                }
            }
            $manager->persist($ad);
        }
        $manager->flush();
    }

    function dataAdvert(){
        return array(
            [
                'title' => 'Recherche developpeur Symfony',
                'id' => '1',
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un developpeur Symfony sur Lyon',
                'date' => new \DateTime('2018-09-10'),
                'url' => "/image/symfony.jpg",
                'alt' => "logo symfony",
                'categories' => ['Développement web']
            ],
            [
                'title' => 'Mission de webmaster',
                'id' => '2',
                'author' => 'Hugo',
                'content' => 'Nous recherchon un webmaster capable de maintenir notre site internet',
                'date' => new \DateTime('2018-10-15'),
                'url' => '/image/webmaster.png',
                'alt' => 'logo webmaster',
                'categories' =>['Intégration', 'Développement web']
            ],
            [
                'title' => 'Offre stage de webdesigner',
                'id' => '3',
                'author' => 'Mathieu',
                'content' => 'Nous recherchons un webdesigner',
                'date' => new \DateTime('2018-08-02'),
                'url' => '/image/webdesigner.jpg',
                'alt' => 'logo webdesigner',
                'categories' => ['Développement web', 'Graphisme']
            ],
            [
                'title' => 'Developpeur Web Full Stack',
                'author' => 'Charles',
                'email' => 'contact@charles-tognol.fr',
                'content' => 'Recherche un developpeur Full Stack PHP Javascript HTML5 CSS3 Androïd Java',
                'date' => new \DateTime('2018-12-22'),
                'url' => '/image/devFullStack.jpg',
                'alt' => 'dev Full Stack',
                'applications' => [
                    [
                        'content' => 'Votre Offre correspond en tous points à ce que je recherche tous mon savoir faire à votre service',
                        'author' => 'anonymous',
                        'email' => 'contact@charles-tognol.fr'
                    ],
                    [
                        'content' => 'La qualite de vos services accompagner de ma motivation ferons des étincelles',
                        'author' => 'motidev'
                    ],
                    [
                        'content' => 'Je veux bien faire un stage chez vous mais je veux 1500€',
                        'author' => 'stagiaire'
                    ]
                ],
                'categories' => ['Développement web', 'Développement mobile', 'Graphisme', 'Intégration', 'Réseau']
            ],
            [
                'title' => 'Developpeur Expert en tous',
                'author' => 'GOD',
                'email' => 'contact@charles-tognol.fr',
                'content' => 'Si vous savez tous faire sans aucun bug et en fermant les yeux alors vous etes fait pour travailler chez nous (le tout gratuitement ...)',
                'date' => new \DateTime(),
                'url' => '/image/headImg2.jpg',
                'alt' => 'expert all',
                'applications' => [
                    [
                        'content' => 'Je pense correspondre à votre demande',
                        'author' => 'Mahomet prophete'
                    ],
                    [
                        'content' => 'Encore debutant mais très compétent',
                        'author' => 'Jesus de nazareth'
                    ],
                    [
                        'content' => 'Je peux le faire sans les mains',
                        'author' => 'Moise'
                    ],
                    [
                        'content' => "J'ai appris à tous les autres engagez moi !",
                        'author' => 'Abraham',
                        'email' => 'contact@charles-tognol.fr'
                    ]
                ],
                'categories' => ['Développement web', 'Développement mobile', 'Graphisme', 'Intégration', 'Réseau'],
                'skills' => ['PHP', 'SYMFONY', 'C++', 'JAVA', 'PHOTOSHOP', 'BLENDER', 'BLOC-NOTE']
            ]
        );
    }
}