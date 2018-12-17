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
use CHARLY\PlatformBundle\Entity\Image;
use mysql_xdevapi\Exception;
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

        $skills = $em->getRepository('CHARLYPlatformBundle:AdvertSkill')
                    ->findBy(array('advert' => $advert));
        return $this->render(
            'CHARLYPlatformBundle:Advert:view.html.twig',
            array("advert" => $advert, 'listApplication' => $candidatures, 'listSkills' => $skills)
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

        $img = new Image();
        $img->setAlt("expert all");
        $img->setUrl('/image/headImg2.jpg');

        $advert = new Advert();
        $advert->setTitle("Developpeur Expert en tous :-)");
        $advert->setImage($img);
        $advert->setAuthor("GOD");
        $advert->setContent("Si vous savez tous faire sans aucun bug et en fermant les yeux alors vous etes fait pour travailler chez nous (Le tout gratuitement ...)");

        $listCategories = $em->getRepository('CHARLYPlatformBundle:Category')->findAll();
        foreach ($listCategories as $category)
            $advert->addCategory($category);

        $listApplis = [
            [
                'author' => 'Mahomet prophete',
                'content' => 'Je pense correspondre à votre demande'
            ],
            [
                'author' => 'Jesus de nazarethe',
                'content' => 'Encore debutant mais très compétent'
            ],
            [
                'author' => 'Moise',
                'content' => 'Je peux le faire sans les mains'
            ],
            [
                'author' => "Abraham",
                'content' => "J'ai appris à tous les autres engagez moi !"
            ]
        ];
        foreach ($listApplis as $application) {
            $appli = new Application();
            $appli->setAdvert($advert);
            $appli->setContent($application['content']);
            $appli->setAuthor($application['author']);

            $em->persist($appli);
        }


        $em->persist($advert);

        $listSkills = $em->getRepository('CHARLYPlatformBundle:Skill')->findAll();

        foreach ($listSkills as $skill) {
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel("EXPERT");
            $em->persist($advertSkill);
        }

        $em->flush();

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