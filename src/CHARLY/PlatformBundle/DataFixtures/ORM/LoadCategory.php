<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: PC
 * Date: 11/12/2018
 * Time: 11:28
 */

namespace CHARLY\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CHARLY\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
    /**
     * La création (load) de cette entité et réalisé depuis la fixture de advert
     * @param ObjectManager $manager
     */
    //Dans l'argument de la méthode load, l'objet $manager est l'entityManager
    public function load(ObjectManager $manager){
        //Cette fonction et déjà appellé depuis la fixtures advert
        //self::createCategory($manager);
        //$manager->flush();
    }

    public static function createCategories($manager)
    {
        $categories = NULL;
        $names = array(
            'Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        );
        foreach ($names as $name){
            //On crée la catégorie
            $categories[$name] = new Category();
            $categories[$name]->setName($name);

            //On la persiste
            $manager->persist($categories[$name]);
        }
        return $categories;
        
    }
}