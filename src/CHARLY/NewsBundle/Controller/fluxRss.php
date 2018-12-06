<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 20/11/2018
 * Time: 14:37
 */

namespace CHARLY\NewsBundle\Controller;

/**
 * Class fluxRss
 * @package CHARLY\NewsBundle\Controller
 *
 * Creer une instance d'un flux Rss
 * contiendra tous les articles d'un flux
 *
 * pourras retoruner title descritpion et date de publication
 *
 */
class fluxRss
{
    private $urls;
    private $flux;

    /**
     * fluxRss constructor.
     *
     * @param $url url du flux rss a construire
     * @throws \exception
     */
    function __construct($url){
        $this->urls = $url;
        $this->setflux();
    }

    /**
     * Creer le flux dans la propriete $this->flux
     *
     * @throws \exception
     */
    public function setflux(){
        $i = 0;
        foreach ($this->urls as $item) {
            $flux[$i] = simplexml_load_file($item, "SimpleXMLElement", LIBXML_NOCDATA);
            if($flux[$i] == false)
                throw new \exception("Le flux [ ".$item." ] n'à pas pu être chargé !");

            $this->flux[$i]['title'] = $flux[$i]->channel->title;
            $this->flux[$i]['description'] = $flux[$i]->channel->description;
            $this->flux[$i]['link'] = $flux[$i]->channel->link;
            $this->flux[$i]['item'] = $flux[$i]->channel->item;
            $i++;
        }
    }

    /**
     * @return mixed $this->flux
     */
    public function getFlux(){
        return $this->flux;
    }
    /**
     * @param $id
     * @param $article
     *
     * @return mixed
     */
    public function getArticle($id, $article){
        return $this->flux[$id]['item'][$article];
    }
    public function getActualite($id){
        return $this->flux[$id];
    }
}