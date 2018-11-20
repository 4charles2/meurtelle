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
    static public $flux = null;

    public function indexAction(){
        return $this->render('CHARLYNewsBundle:News:index.html.twig', array('url' => self::$flux));
    }
    /**
     * Renvoie un rendu des news de flux rss
     *
     * @param int nbNew Indique la limite si NULL pour le flux complet
     * @param string url Url du flux à aller chercher
     * @param Bool description TRUE show description FALSE not show description
     *
     * @return Response template twig fluxRss.html.twig
     */
    public function ReadRssAction($flux, $nbNew, $description, $rss){

        return $this->render('CHARLYNewsBundle:News:fluxRss.html.twig',array('flux' => $flux->getFlux() , 'limit' => $nbNew, 'description' => $description, 'rss' => $rss));
    }
    public function viewAction($id, $rss){
        return $this->render('CHARLYNewsBundle:News:view.html.twig', array('flux' => self::$flux[$rss]->getArticle($id)));
    }
    /*
     *
     public static function readFlux($url){
        $fluxRss = simplexml_load_file($url,"SimpleXMLElement",LIBXML_NOCDATA);
        if($fluxRss === false)
            throw new \exception("LE fichier n'a pas été trouver erreur dans la procedure - CHARLYNewsBundle:News:readflux");

        return $fluxRss;
    }
    */
}