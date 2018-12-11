<?php

namespace CHARLY\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="CHARLY\PlatformBundle\Repository\AdvertRepository")
 */
class Advert
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="CHARLY\PlatformBundle\Entity\Image", cascade={"persist"})
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="CHARLY\PlatformBundle\Entity\Category", cascade={"persist"})
     * @ORM\JoinTable(name="CHARLY_advert_category")
     */
    private $categories;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="published", type="boolean")
     */
    private $published = true;

    /**
     * Advert constructor.
     * @throws \Exception
     */

    public function __construct(){
        //Par default la date de l'annonce et la date d'aujourd'hui
        $this->published = True;
        $this->date = new \Datetime();
        $this->categories = new ArrayCollection();
}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Advert
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Advert
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Advert
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
    /**
     * Set Image
     *
     * @param Imagem image
     */
    function setImage(Image $image = NULL){
        $this->image = $image;
    }
    /**
     * Get image
     *
     * @return image
     */
    public function getImage(){
        return $this->image;
    }

    /**
     * Add category
     *
     * @param \CHARLY\PlatformBundle\Entity\Category $category
     *
     * @return Advert
     */
    public function addCategory(Category $category)
    {
        //On ajoute une seul category a la fois
        $this->categories[] = $category;
    
        return $this;
    }

    /**
     * Remove category
     *
     * @param \CHARLY\PlatformBundle\Entity\Category $category
     */
    public function removeCategory(Category $category)
    {
        //Ici on utilise une méthode de l'arrayCollection, pour supprimer la catégorie en argument
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        //On à mit Catégories aux pluriels car on récupére une liste de catégories ici !
        return $this->categories;
    }
}
