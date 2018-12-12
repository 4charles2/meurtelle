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
    //Dans l'argument de la méthode load, l'objet $manager est l'entityManager
    public function load(ObjectManager $manager){
        //Liste des noms de catégorie à ajouter
        $names = array(
            'Développement web',
            'Développement mobile',
            'Graphisme',
            'Intégration',
            'Réseau'
        );
        foreach ($names as $name){
            //On crée la catégorie
            $category = new Category();
            $category->setName($name);

            //On la persiste
            $manager->persist($category);
        }
        $manager->flush();
    }

}