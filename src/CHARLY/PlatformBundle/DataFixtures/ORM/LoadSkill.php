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
        // Listes des noms de compétences
        $names = array('PHP', 'SYMFONY', 'C++', 'JAVA', 'PHOTOSHOP', 'BLENDER', 'BLOC-NOTE');

        foreach ($names as $name){
            $skill = new Skill();
            $skill->setName($name);

            $manager->persist($skill);
        }

        $manager->flush();
    }
}