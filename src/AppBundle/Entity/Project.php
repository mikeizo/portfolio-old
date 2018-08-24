<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="projects", uniqueConstraints={@ORM\UniqueConstraint(name="slug", columns={"slug"})})
 * @ORM\Entity
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message = "Name cannot be blank")
     * @Assert\Length(
     *     min = 2,
     *     max = 50,
     *     minMessage = "Name must be at least {{ limit }} characters long",
     *     maxMessage = "Name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="short_description", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message = "Short Description cannot be blank")
     * @Assert\Length(
     *     min = 5,
     *     max = 50,
     *     minMessage = "Short Description must be at least {{ limit }} characters long",
     *     maxMessage = "Short Description cannot be longer than {{ limit }} characters"
     * )
     */
    private $shortDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank(message = "Description cannot be blank")
     * @Assert\Length(
     *     min = 5,
     *     max = 500,
     *     minMessage = "Short Description must be at least {{ limit }} characters long",
     *     maxMessage = "Short Description cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;   

    /**
     * @var string
     * @ORM\Column(name="resources", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message = "Resources cannot be blank")
     */
    private $resources;

    /**
     * @var string
     * @ORM\Column(name="url", type="string", length=100, nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=100, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=30, nullable=false)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer", nullable=false)
     */
    private $position;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;


    public function __construct()
    {
        $this->date = new \DateTime();
    }


    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     *
     * @return self
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @param string $resources
     *
     * @return self
     */
    public function setResources($resources)
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     *
     * @return self
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return self
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param integer $position
     *
     * @return self
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime
     *
     * @return self
     */
    public function setDate()
    {
        $this->date = $date;

        return $this;
    }
}

