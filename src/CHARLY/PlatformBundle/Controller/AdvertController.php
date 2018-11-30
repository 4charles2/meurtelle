<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 09/11/2018
 * Time: 21:52
 */

namespace CHARLY\PlatformBundle\Controller;


use CHARLY\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * Class AdvertController
 * @author tognol charles
 *
 * @package CHARLY\PlatformBundle\Controller
 */
class AdvertController extends Controller
{
    protected $arrayOut = array('nom' => 'Charles');
    protected $listAdverts = null;

    function __construct(){
        //https://emploi.alsacreations.com/offres.xml
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

    /**
     * Page d'acceuil de la partie offer d'emplois
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function indexAction(){

        return $this->render('CHARLYPlatformBundle:Advert:index.html.twig',
            array('listAdverts' => $this->listAdverts)
            );
    }

    /**
     * Afficher la description complète d'une annonce
     *
     * @param $id id de l'anonce à afficher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function viewAction($id){
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CHARLYPlatformBundle:Advert')
        ;
        $advert = $repository->find($id);
        if(null === $advert)
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        
        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $advert)
        );
    }

    /**
     * modifier une annonce
     *
     * @param Request $request
     * @param         $id id de l'annonce à modifier
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    function editAction(Request $request, $id){

        if($request->isMethod('POST')){
            $this->addFlash('info', 'Votre annonce à bien été modifié');

            return $this->redirectToRoute('charly_platform_view', array('id' => $id));
        }
        return $this->render('CHARLYPlatformBundle:Advert:edit.html.twig', array('advert' => $this->listAdverts[$id-1]));
    }

    /**
     * Supression d'une annonce
     *
     * @param $id id de l'annonce à suprimer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function deleteAction($id){
        $this->addFlash('info', 'Votre annonce à bien été suprimer');
        unset($this->listAdverts[$id-1]);
        return $this->render('CHARLYPlatformBundle:Advert:delete.html.twig', array('id' => $id));
    }

    /**
     * Ajouter une nouvelle annonce
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    function addAction(Request $request){
        //Création de l'entité
        $advert = new Advert();
        $advert->setTitle("Recherche développeur Symfony.");
        $advert->setAuthor("Alexandre");
        $advert->setContent("Nous recherchons un developpeur symfony débutant sur Lyon. Blabla... ");
        //On ne peut définir ni la date ni la publication; ??
        //car ces attributs sont définis automatiquement dans le constructeur

        //On récupere l'entityManager
        $em = $this->getDoctrine()->getManager();

        //Étape 1 : on persiste l'entité
        $em->persist($advert);

        //Étape 2 : On flush tout ce qui a été persité avant
        $em->flush();

        //Reste de la méthode qu'on avait déjà écrit

        //Si requete est en POST c'est que l'user a up the Form
        if($request->isMethod('POST')) {
            $this->addFlash('info', "Votre annonce à bien été enregistrer");

            //ON affiche la page de l'annonce créer
            return $this->redirectToRoute('charly_platform_view');
        }
        //Si la requete n'est pas en POST alors on affiche le formulaire
        return $this->render('CHARLYPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

    /**
     * création du menu pour la gestion des annonces
     * @author tognol charles
     *
     * @param $limit limit du nombre d'annnce à afficher
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function menuAction($limit){
        $anytime =  $limit;

        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche developper symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre Stage de webdesigner')
        );

        return $this->render('CHARLYPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
}