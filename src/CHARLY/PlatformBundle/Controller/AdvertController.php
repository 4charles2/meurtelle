<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 09/11/2018
 * Time: 21:52
 */

namespace CHARLY\PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class AdvertController extends Controller
{
    protected $arrayOut = array('nom' => 'Charles');
    protected $listAdverts = null;

    function __construct(){
        $this->listAdverts = array(
            array(
                'title' => 'Recherche developpeur Symfony',
                'id' => '1',
                'author' => 'Alexandre',
                'content' => 'Nous recherchons un developpeur Symfony sur Lyon',
                'date' => new \DateTime()),
            array(
                'title' => 'Mission de webmaster',
                'id' => '2',
                'author' => 'Hugo',
                'content' => 'Nous recherchon un webmaster capable de maintenir notre site internet',
                'date' => new \DateTime()),
            array(
                'title' => 'Offre stage de webdesigner',
                'id' => '3',
                'author' => 'Mathieu',
                'content' => 'Nous recherchons un webdesigner',
                'date' => new \DateTime())
        );
    }
    function indexAction($page){

        if($page < 1)
            throw New NotFoundHttpException("La page ".$page." n'existe pas");

        return $this->render('CHARLYPlatformBundle:Advert:index.html.twig',
            array('listAdverts' => $this->listAdverts)
            );
    }
    function viewAction($id){

        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $this->listAdverts[$id-1])
        );
    }
    function editAction(Request $request, $id){

        if($request->isMethod('POST')){
            $this->addFlash('info', 'Votre annonce à bien été modifié');

            return $this->redirectToRoute('charly_platform_view', array('id' => $id));
        }
        return $this->render('CHARLYPlatformBundle:Advert:edit.html.twig', array('advert' => $this->listAdverts[$id-1]));
    }
    function deleteAction($id){
        $this->addFlash('info', 'Votre annonce à bien été suprimer');
        unset($this->listAdverts[$id-1]);
        return $this->render('CHARLYPlatformBundle:Advert:delete.html.twig', array('id' => $id));
    }
    function addAction(Request $request){

        //array('id' => $id)
        //Si requete est en POST c'est que l'user a up the Form
        if($request->isMethod('POST')) {
            $this->addFlash('info', "Votre annonce à bien été enregistrer");

            //ON affiche la page de l'annonce créer
            return $this->redirectToRoute('charly_platform_view');
        }
        //Si la requete n'est pas en POST alors on affiche le formulaire
        return $this->render('CHARLYPlatformBundle:Advert:add.html.twig');
    }
    function menuAction($limit){

        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche developper symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre Stage de webdesigner')
        );

        return $this->render('CHARLYPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}