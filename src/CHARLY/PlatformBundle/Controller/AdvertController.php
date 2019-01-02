<?php
/**
 * Created by PhpStorm.
 * User: charly
 * Date: 09/11/2018
 * Time: 21:52
 */

namespace CHARLY\PlatformBundle\Controller;


use CHARLY\PlatformBundle\Entity\Advert;
use CHARLY\PlatformBundle\Entity\AdvertSkill;
use CHARLY\PlatformBundle\Entity\Application;
use CHARLY\PlatformBundle\Entity\Category;
use CHARLY\PlatformBundle\Entity\Image;

use CHARLY\PlatformBundle\Repository\AdvertRepository;
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

    /**
     * Page d'acceuil de la partie offer d'emplois
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function indexAction()
    {

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


        $skills = $em->getRepository('CHARLYPlatformBundle:AdvertSkill')
                    ->findBy(array('advert' => $advert));

        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $advert, 'listSkills' => $skills));

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
        $em = $this->getDoctrine()->getManager();

        $advert = $em->getRepository('CHARLYPlatformBundle:Advert')->find($id);
        $listCategorys = $em->getRepository('CHARLYPlatformBundle:Category')->findAll();

        if($advert === null)
            throw new NotFoundHttpException("L'annonce ".$id." n'héxiste pas !");

        foreach ($listCategorys as $category){
            $advert->addCategory($category);
        }
        $em->flush();
        if($request->isMethod('POST')){
            $this->addFlash('info', 'Votre annonce à bien été modifié');

            return $this->redirectToRoute('charly_platform_view', array('id' => $id));
        }
        return $this->render('CHARLYPlatformBundle:Advert:edit.html.twig', array('advert' => $advert));
    }

    /**
     * Supression d'une annonce
     *
     * @param $id id de l'annonce à suprimer
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('CHARLYPlatformBundle:Advert')->find($id);

        if ($advert->getCategories() ===  Null)
            throw new NotFoundHttpException('Cette annonce ne possede pas de category !');

        foreach ($advert->getCategories() as $category)
            $advert->removeCategory($category);

        $em->flush();

        $this->addFlash('info', 'Votre annonce à bien été suprimer');

        return $this->forward('CHARLYPlatformBundle:Advert:index');
        return $this->render('CHARLYPlatformBundle:Advert:index.html.twig', array('id' => $id));
    }

    /**
     * Ajouter une nouvelle annonce
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('CHARLYPlatformBundle:Category')->findByName('Développement web');
        $advert = new Advert();

        $advert->setAuthor('TOGNOL Charles');
        $advert->setContent('Mon annonce creer dans le controleur');
        $advert->setTitle('Test du service Application Mailler');

        $advert->setEmail('charly.learn@gmail.com');


        $advert->addCategory($category[0]);

        $application = new Application();
        $application->setEmail('me@mail.fr');
        $application->setAuthor('me');
        $application->setContent('Je suis la pour le test');
        $application->setAdvert($advert);


        $advert->addApplication($application);

        //GET SERVICE ANTISPAM
        $antiSpam = $this->container->get('charly_platform.antispam');

        $em->persist($advert);
        $em->persist($application);

        if($antiSpam->isSpam($advert->getContent())){
            $em->refresh($advert);
            $em->refresh($application);
            throw new \Exception('Votre Annonce à été detecté comme un spam');
        }


        $em->flush();

        //Si requete est en POST c'est que l'user a up the Form
        if($request->isMethod('POST')) {
            $this->addFlash('info', "Votre annonce à bien été enregistrer");

            //ON affiche la page de l'annonce créer
            return $this->redirectToRoute('charly_platform_view');
        }
        //Si la requete n'est pas en POST alors on affiche le formulaire
        return $this->render('CHARLYPlatformBundle:Advert:add.html.twig');
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
            ->findby(
                array('published' => true),
                array('date' => 'desc'),
                $limit,
                0
            );

        return $this->render('CHARLYPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));

    }

}