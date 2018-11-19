<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 17/11/2018
 * Time: 23:13
 */

namespace CHARLY\NewsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsController extends Controller
{

    public static function url(){
        //'https://www.alsacreations.com/rss/apprendre.xml',
        return ['https://web.developpez.com/rss.php',
                'https://www.alsacreations.com/rss/actualites.xml'
        ];
    }
    public function indexAction(){
        return $this->render('CHARLYNewsBundle:News:index.html.twig', array('url' => self::url()));
    }
    /**
     * Renvoie un rendu des news de flux rss
     *
     * @param nbNew Indique la limite si NULL pour le flux complet
     * @param url Url du flux à aller chercher
     *
     * @return Response template twig fluxRss.html.twig
     */
    public function fluxRssAction($url, $nbNew ){
        //$fluxRss = simplexml_load_file('https://web.developpez.com/rss.php');

        $fluxRss = self::readFlux($url);

        return $this->render('CHARLYNewsBundle:News:fluxRss.html.twig',array('flux' => $fluxRss, 'limit' => $nbNew));
    }
    public static function readFlux($url){
        $fluxRss = simplexml_load_file($url,"SimpleXMLElement",LIBXML_NOCDATA);
        if($fluxRss === false)
            $fluxRss = "LE fichier n'a pas été trouver erreur dans la procedure";

        return $fluxRss;
    }
}