<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: charly
 * Date: 13/12/2018
 * Time: 11:53
 */

namespace CHARLY\PlatformBundle\DataFixtures\ORM;


use CHARLY\PlatformBundle\Entity\Skill;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSkill implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //Decommenter lorsque la fixture advert ne sera plus necessaire
        /*
        // Listes des noms de compétences
        $names = array('PHP', 'SYMFONY', 'C++', 'JAVA', 'PHOTOSHOP', 'BLENDER', 'BLOC-NOTE');

        foreach ($names as $name){
            $skill = new Skill();
            $skill->setName($name);

            $manager->persist($skill);
        }

        $manager->flush();
        */
    }
    public static function createSkills(ObjectManager $manager){
        //Utile afin d'utiliser les skills dans la fixture advert
        //Car sinon les skills n'etais pas encore créer
        $skills = null;
        $names = [
            'PHP',
            'SYMFONY',
            'C++',
            'JAVA',
            'PHOTOSHOP',
            'BLENDER',
            'BLOC-NOTE'
        ];
        foreach ($names as $name){
            $skills[$name] = new Skill();
            $skills[$name]->setName($name);

            $manager->persist($skills[$name]);
        }
        return $skills;
    }
}