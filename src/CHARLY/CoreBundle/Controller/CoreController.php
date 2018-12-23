<?php

namespace CHARLY\CoreBundle\Controller;

use CHARLY\NewsBundle\Controller\NewsController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction(){
        /*
         *
         $rss = null;
        $i = 0;
        foreach( NewsController::flux as $key => $url)
            $rss[$i++] = $url->getFlux() ? : $rss[$i] = "Le Flux Rss : ".$url." n'à pas pu être chargé ";
*/
phpinfo();
        return $this->render('CHARLYCoreBundle:Core:index.html.twig', array('flux' => $this->get('charly_news.fluxRss')->getFlux() ));
    }
    public function contactAction(){
        $testXdebug = O;
        $this->addFlash('info', 'La page de contact n\'est pas encore disponible. Merci de revenir plus tard');
        return $this->redirectToRoute('charly_core_homepage');
    }
}
