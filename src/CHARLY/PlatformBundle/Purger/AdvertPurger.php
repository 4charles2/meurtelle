<?php
/**
 * Copyright (c) 2019. Toute reproduction ou utilisation est interdite sans l'accord de l'auteur
 */

/**
 * Created by PhpStorm.
 * User: 4charles2
 * Date: 2019-02-23
 * Time: 18:26
 */

namespace CHARLY\PlatformBundle\Purger;


class AdvertPurger
{
    private $mailer;
    private $qb;

    public function __construct(\Swift_Mailer $mailer, \Doctrine\ORM\EntityManager $qb)
    {
        $this->mailer = $mailer;
        $this->qb = $qb;
    }
    //Nettoyer les annonces sans candidatures qui ont été créer avant la date $date

    /**
     * Fait une requête à l'aide de entityManager et de la méthode du repo advert
     * getDateLT($limit_date)
     *
     * Si     les annonces ont été crées avant la $limit_date
     * ET     que les annonces n'ont pas de candidatures
     * ALORS  les supprimer
     *
     * @param $days Nombre de jours à décrémenter de la date actuel
     *
     * @return array Retourne la liste des annonces qui ont été supprimées
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function purge($days)
    {

        $list_adverts_deletes = array();
        $limit_date = strftime("Y-m-d", strtotime('-' . $days . " day"));

        $adverts = $this->qb->getRepository('CHARLYPlatformBundle:Advert')->getDateLT($limit_date);

        foreach ($adverts as $advert) {
            $list_adverts_deletes[] = $advert;
            $this->notification($advert->getEmail());
            $this->qb->remove($advert);
            $this->qb->flush();
        }
        //Retourne la liste ds annonces qui ont été suprimé
        return $list_adverts_deletes;
    }

    private function notification($email)
    {
        $message = $this->mailer->createMessage()
            ->setSubject("Supretion de votre annonce")
            ->setFrom('admin@platformBundle')
            ->setTo($email)
            ->setBody(
                "<div style=\"border: 1px solid black; padding: 10px; text-align: centers;\"><h1>Votre annonce à été surpimé !</h1>
                    <p>Votre annonce depuis se création n'à reçu aucune candidature.</p>
                    <p>Elle à donc été suprimé de la base de données.</p>
                    <p>Si vous souhaitez publié de nouveau une annonce penser à l'adapté pour qu'elle corresponde au marché actuel.</p>
                    <p>Veuillez accepté les sincères salutations de toutes l'équipe de charly Platform</p>
                </div>",
                'text/html'
            )
            ->setReadReceiptTo('charly.learn@gmail.com');
        $this->mailer->send($message);
    }
}