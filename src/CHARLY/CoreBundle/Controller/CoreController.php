<?php

namespace CHARLY\CoreBundle\Controller;

use CHARLY\NewsBundle\Controller\NewsController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction(){
       return $this->render('CHARLYCoreBundle:Core:index.html.twig', array('flux' => $this->get('charly_news.fluxRss')->getFlux() ));
    }
    public function contactAction(){
        $this->addFlash('info', 'La page de contact n\'est pas encore disponible. Merci de revenir plus tard');
        return $this->redirectToRoute('charly_core_homepage');
    }
}
