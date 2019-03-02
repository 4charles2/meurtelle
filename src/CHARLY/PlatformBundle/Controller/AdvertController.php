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
     * Page d'acceuil de la partie offre d'emplois
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function indexAction($page)
    {
        if($page < 1)
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");

        $nbPerPage = 4;
        //todo faire un test avec une annonce état published false
        //Recupere toutes les annonces et renvoie un object paginator avec une nombre d'annonces limit de 5 par page
        $listAdverts = $this->getDoctrine()
            ->getManager()
            ->getRepository('CHARLYPlatformBundle:Advert')
            ->getAdverts($page, $nbPerPage);
        //getAdvertsWithImages();

        //Calcul le nombre total de pages grâce au count($listAdverts) qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAdverts)/$nbPerPage);

        if($page > $nbPages)
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");

        return $this->render('CHARLYPlatformBundle:Advert:index.html.twig',
                             array('listAdverts' => $listAdverts,
                                   'nbPages'     => $nbPages,
                                   'page'        => $page
                                  )
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

        $advert = $em->getRepository('CHARLYPlatformBundle:Advert')->getAdvertAllInfos($id);
            //->find($id);

        if(null === $advert)
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");

        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $advert));
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

        $advert = $em->getRepository('CHARLYPlatformBundle:Advert')->getAdvertAllInfos($id);

        if($advert === null)
            throw new NotFoundHttpException("L'annonce ".$id." n'héxiste pas !");


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

        if($advert === NULL)
            throw new NotFoundHttpException("Cette Annonce n'est pas présente en base de données ! ");

        /*
         * Je n'ai plus besoin de ce code mais present dans le cours OC je laisse au cas ou
         * pour une utilité ultérieur !
         *
        if ($advert->getCategories() ===  Null)
            throw new NotFoundHttpException('Cette annonce ne possede pas de category !');

        foreach ($advert->getCategories() as $category)
            $advert->removeCategory($category);
        */
        $em->remove($advert);
        $em->flush();

        $this->addFlash('info', 'Votre annonce à bien été suprimer');


        //Redirection vers la page d'acceuil des offre d'emplois
        return $this->redirectToRoute('charly_platform_homepage');
        //forward est utile pour appeller un autre contrôler ou une autre méthode de this controller
        //return $this->forward('CHARLYPlatformBundle:Advert:index');
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
        $advert->setContent('Mon annonce creer dans le controleur je ne suis plus un spam hahaha hihihi');
        $advert->setTitle('Test du service Application Mailler');

        $advert->setEmail('charly.learn@gmail.com');


        $advert->addCategory($category[0]);

        $application = new Application();
        $application->setEmail('me@mail.fr');
        $application->setAuthor('me');
        $application->setContent('Je suis la pour le test');
        $application->setAdvert($advert);


        $advert->addApplication($application);

        //GET SERVICE ANTISPAM Si le text fait moins de 50 caractères alors l'annonce est considéré comme un spam
        $antiSpam = $this->container->get('charly_platform.antispam');

        if($antiSpam->isSpam($advert->getContent())) {
            $em->detach($advert);
            $em->detach($application);

            throw new \Exception('Votre Annonce à été detecté comme un spam');
        }else{
            $em->persist($advert);
            $em->persist($application);
        }

        $em->flush();

        //Si requete est en POST c'est que l'user a up the Form
        if($request->isMethod('POST')) {
            $this->addFlash('info', "Votre annonce à bien été enregistrer");

            //ON affiche la page de l'annonce créer
            return $this->redirectToRoute('charly_platform_view');
        }
        //Si la requête n'est pas en POST alors on affiche le formulaire
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
                array('date' => 'desc'), //Par date decroissante
                $limit,
                0 //A partir du premier
            );

        return $this->render('CHARLYPlatformBundle:Advert:menu.html.twig', array('listAdverts' => $listAdverts));
    }
    function purgeAction($days){
        $list_delete_adverts = null;
        if($days > 0) {
            $advertPurge = $this->get('charly_platform.purger.advert');
            $list_delete_adverts = $advertPurge->purge($days);
        }
        $date_limit = date('Y-m-d H:i:s',strtotime('-'.$days." day"));
        return $this->render('CHARLYPlatformBundle:Advert:purge.html.twig',
            array("limit_date" => $date_limit,
                "adverts" => $list_delete_adverts));
    }
}