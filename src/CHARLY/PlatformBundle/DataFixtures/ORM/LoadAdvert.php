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


use CHARLY\PlatformBundle\Entity\AdvertSkill;
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
                foreach($advert['skills'] as $key=>$skill) {
                    $advtSkill = new AdvertSkill();
                    $advtSkill->setSkill($skills[$key]);
                    $advtSkill->setLevel($skill);
                    $ad->addSkill($advtSkill);
                }
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
                'skills' => [
                    'PHP' => 'EXPERT',
                    'SYMFONY' => 'EXPERT',
                    'C++' => 'EXPERT',
                    'JAVA' => 'EXPERT',
                    'PHOTOSHOP' => 'EXPERT',
                    'BLENDER' => 'EXPERT',
                    'BLOC-NOTE' => 'EXPERT'
                ]
            ],
            [
                'title' => 'Dev Mobile Switf',
                'author' => 'you',
                'email' =>  'charly.lean@gmail.com',
                'content' => 'Vous savez developper des applications Swift Vous êtes au bon endroit',
                'date' => new \DateTime('2005-02-15'),
                'url' => '/image/headImg2.jpg',
                'alt' => 'expert swift',
                'applications' => [
                    [
                        'content' => 'Je suis expert en Swift',
                        'author' => 'devSwift'
                    ],
                    [
                        'content' => 'dev senior Swift',
                        'author' => 'seniorDev'
                    ],
                    [
                        'content' => 'developper junior Swift avec un avenir très prometteur',
                        'author' => 'junior Dev'
                    ]
                ],
                'categories' => ['Développement mobile'],
                'skills' => [
                    'SWIFT' => 'EXPERT'
                ]
            ],
            [
                'title' => 'DATA SCIENTIST',
                'author' => 'SQL',
                'email' =>  'SQL@mysql.fr',
                'content' => 'Créer des requêtes SQL manipuler les bases de données et savoir lire les informations',
                'date' => new \DateTime(),
                'url' => '/image/headImg2.jpg',
                'alt' => 'expert SQL',
                'applications' => [
                    [
                        'content' => 'JE passe ma vie dans une console SQL',
                        'author' => 'sql'
                    ],
                    [
                        'content' => "requête SQL c'est mon deuxieme prenom",
                        'author' => 'requestSQL'
                    ],
                    [
                        'content' => "mySql it's my father",
                        'author' => 'mysql Junior'
                    ]
                ],
                'categories' => ['BIG DATA'],
                'skills' => [
                    'SQL' => 'EXPERT'
                ]
            ],
            [
                'title' => 'Application Java',
                'author' => 'JavaTeur',
                'email' =>  'java@oracle.fr',
                'content' => "Création d'application java multi-plateforme",
                'date' => new \DateTime(),
                'url' => '/image/headImg2.jpg',
                'alt' => 'app java',
                'applications' => [
                    [
                        'content' => 'Je connais toutes les api et lib java ainsi que tous les designs pattern ',
                        'author' => 'javadit'
                    ],
                    [
                        'content' => "Je suis passionné par le langage Java",
                        'author' => 'javalike'
                    ],
                    [
                        'content' => "the java it's my life",
                        'author' => 'javalife'
                    ]
                ],
                'categories' => ['Application'],
                'skills' => [
                    'JAVA' => 'EXPERT'
                ]
            ],
            [
                'title' => 'Developpeur Web',
                'author' => 'siteFR',
                'email' => 'contact@site.fr',
                'content' => "Je recherche un developpeur Web pour créer le site de l'entreprise puis ensuite le maintenir et y créer le contenu",
                'date' => new \DateTime("2019-01-01"),
                'url' => '/image/headImg2.jpg',
                'alt' => 'wed dev',
                'applications' => [
                    [
                        'content' => "J'ai à mon actif la création d'une dizaine de site. Je remplie humblement vos critères",
                        'author' => 'webdev'
                    ],
                    [
                        'content' => 'Je maintiens plusieurs site quotidienement ',
                        'author' => 'webmaster'
                    ]
                ],
                'categories' => ['Développement web', 'Graphisme', 'Intégration'],
                'skills' => [
                    'PHP' => 'DEBUTANT',
                    'PHOTOSHOP' => 'INTERMEDIAIRE',
                ]
            ],
            [
                'title' => 'integrateur',
                'author' => 'integrator',
                'email' => 'integrator@site.fr',
                'content' => "Je recherche un integrateur qui integrera les outils developper par l'équipe des developpeur",
                'date' => new \DateTime("2019-01-01"),
                'url' => '/image/headImg2.jpg',
                'alt' => 'integrateur',
                'applications' => [
                    [
                        'content' => "J'ai une expérience d'integrateur assez significative et je pense convenir à vos besoins",
                        'author' => 'integ auto didacte'
                    ],
                    [
                        'content' => "Cela fait déjà 5 années que j'integre des outils sur diffèrents site",
                        'author' => 'integrateur pro'
                    ]
                ],
                'categories' => ['Développement web', 'Intégration'],
                'skills' => [
                    'PHP' => 'INTERMEDIAIRE',
                    'SYMFONY' => 'INTERMEDIAIRE'
                ]
            ]

        );
    }
}