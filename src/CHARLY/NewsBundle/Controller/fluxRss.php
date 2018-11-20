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
    private $url;
    private $flux;

    /**
     * fluxRss constructor.
     *
     * @param $url url du flux rss a construire
     * @throws \exception
     */
    function __construct($url){
        $this->url = $url;
        $this->setflux();
    }
    public function setflux(){

        $flux = simplexml_load_file($this->url, "SimpleXMLElement", LIBXML_NOCDATA);

        if($flux == false)
            throw new \exception("Le flux ".$this->url." n'à pas pu etre chargé !");


        $this->flux['title'] = $flux->channel->title;
        $this->flux['description'] = $flux->channel->description;
        $this->flux['link'] = $flux->channel->link;
        $this->flux['item'] = $flux->channel->item;
    }

    public function getTitleFlux(){
        return $this->flux['title'];
    }
    public function getDescriptionFlux(){
        return $this->flux["description"];
    }
    public function getLink(){
        return $this->flux['link'];
    }
    public function getFlux(){
        return $this->flux;
    }
    public function getAllArticle(){
        return $this->flux['item'];
    }
    public function getArticle($id){
        return $this->flux['item'][$id];
    }
    public function upadte(){
        $this->setflux();
    }
    public function getNbArticle(){
        return count($this->getAllArticle());;
    }
}