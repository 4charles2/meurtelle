<?php
/**
 * Copyright (c) 2018. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: charly
 * Date: 30/12/2018
 * Time: 17:49
 */

namespace CHARLY\PlatformBundle\Antispam;


class Antispam
{
    private $minLength;

    function __construct($minLength) {
        $this->minLength = $minLength;
    }

    /**
     * Return true si text et plus petit que 50 caractere et false sinon
     * @param $text
     *
     * @return bool
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}