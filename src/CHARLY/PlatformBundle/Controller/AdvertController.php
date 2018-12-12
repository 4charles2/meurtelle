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

}