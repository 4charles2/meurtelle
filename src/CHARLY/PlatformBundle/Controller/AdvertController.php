<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 09/11/2018
 * Time: 21:52
 */

namespace CHARLY\PlatformBundle\Controller;


use CHARLY\PlatformBundle\Entity\Advert;
use CHARLY\PlatformBundle\Entity\Application;
use CHARLY\PlatformBundle\Entity\Image;
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

        /*$this->listAdverts = array(
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
        );*/

    }

    /**
     * Page d'acceuil de la partie offer d'emplois
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function indexAction(){
        //Decommenter pour persister les annonces dans la base sql avec les images
        //$this->sendAdvert();

        $adverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('CHARLYPlatformBundle:Advert')
            ->findAll();

        return $this->render('CHARLYPlatformBundle:Advert:index.html.twig',
            array('listAdverts' => $adverts)
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
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('CHARLYPlatformBundle:Advert')->find($id);

        if(null === $advert)
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");

        //ON recupere la liste des candidatures de cette annoncez
        $candidatures = $em->getRepository('CHARLYPlatformBundle:Application')
                    ->findBy(array('advert' => $advert));

        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $advert, 'listApplication' => $candidatures)
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
        $advert->setTitle("Developpeur Web full Stack");
        $advert->setAuthor("Charles");
        $advert->setContent("recherche un developpeur full stack PHP Javascript Html5 Css3");
        //On ne peut définir ni la date ni la publication; ??
        //car ces attributs sont définis automatiquement dans le constructeur

        //On crer notre entities image
        $image = new Image();
        $image->setUrl("/image/devFullStack.jpg");
        $image->setAlt("dev Full Stack");

        //On lie l'image a l'entities advert
        $advert->setImage($image);

        //On récupere l'entityManager
        $em = $this->getDoctrine()->getManager();

        //Étape 1 : on persiste l'entité
        $em->persist($advert);

        //Étape 2 : On flush tout ce qui a été persité avant
        $em->flush();
        //Création de personne qui ont repondu aux offre d'emplois
        $reponse[0] =  new Application();
        $reponse[0]->setContent("Votre Offre correspond en tous points à ce que je recherche Tous mon savoir faire à votre service ");
        $reponse[0]->setAuthor("anonymous");

        $reponse[1] = new Application();
        $reponse[1]->setContent("la qualité de vos services accompagner de ma motivation ferons des etincelles");
        $reponse[1]->setAuthor("motidev");

        $reponse[2] = new Application();
        $reponse[2]->setAuthor("stagiaire");
        $reponse[2]->setContent("Je veus bien faire un stage chez vous mais je veux 1500 €");

        for($i = 0; $i < sizeof($reponse); $i++) {
            $em->persist($reponse[$i]);
            $reponse[$i]->setAdvert($advert);
        }
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

        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('CHARLYPlatformBundle:Advert')
            ->findAll();
        $adverts = null;
        //Parcour les annonces en partant de la fin pour ne garder que les trois dernières
        for($i = 0, $x = sizeof($listAdverts)-1; $i < $limit; $i++, $x--)
            $adverts[] = $listAdverts[$x];

        return $this->render('CHARLYPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $adverts));

    }

    function sendAdvert(){
        $this->repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('CHARLYPlatformBundle:Advert')
        ;
        $i = 0;
        $em = $this->getDoctrine()->getManager();
        $ad = $this->listAdverts;
        $urlImgs = [
            [
                'url' => "/image/symfony.jpg",
                'alt' => 'logo symfony'
            ],
            [
                "url" => "/image/webmaster.png",
                'alt' => "logo webmaster"
            ],
            [
                'url' => "/image/webdesigner.jpg",
                'alt' => 'logo webdesigner'
            ]
        ];
        while($i <= 2) {
            $image = new Image();
            $image->setUrl($urlImgs[$i]['url']);
            $image->setAlt($urlImgs[$i]['alt']);

            $advert[$i] = new Advert();
            $advert[$i]->setAuthor($ad[$i]['author']);
            $advert[$i]->setContent($ad[$i]['content']);
            $advert[$i]->setTitle($ad[$i]['title']);
            $advert[$i]->setImage($image);


            $em->persist($advert[$i]);
            $i++;
        }
        $em->flush();
    }
}