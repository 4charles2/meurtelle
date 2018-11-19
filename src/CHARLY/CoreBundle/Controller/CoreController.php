<?php

namespace CHARLY\CoreBundle\Controller;

use CHARLY\NewsBundle\Controller\NewsController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction(){
        $rss = null;
        $i = 0;
        foreach( NewsController::url() as $key => $url)
            $rss[$i++] = NewsController::readFlux($url) ? : $rss[$i] = "Le Flux Rss : ".$url." n'à pas pu être chargé ";

        return $this->render('CHARLYCoreBundle:Core:index.html.twig', array('flux' => $rss ));
    }
}
