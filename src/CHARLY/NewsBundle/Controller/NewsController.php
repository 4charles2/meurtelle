<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 17/11/2018
 * Time: 23:13
 */

namespace CHARLY\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Tests\DependencyInjection\RendererService;

class NewsController extends Controller
{

    public function indexAction(){
        return $this->render('CHARLYNewsBundle:News:index.html.twig', array('url' => $this->get('charly_news.fluxRss')->getFlux()));
    }
    /**
     * Renvoie un rendu des news de flux rss
     *
     * @param int nbNew Indique la limite si NULL pour le flux complet
     * @param string flux id du flux à aller chercher
     * @param Bool description TRUE show description FALSE not show description
     * @param Int id article du flux
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ReadRssAction($flux, $nbNew, $description, $article){

        return $this->render('CHARLYNewsBundle:News:fluxRss.html.twig',array('flux' => $flux , 'limit' => $nbNew, 'description' => $description, 'article' => $article));
    }
    public function viewAction($id){
        return $this->render('CHARLYNewsBundle:News:view.html.twig', array('flux' => $this->get('charly_news.fluxRss')->getActualite($id)));
    }

}