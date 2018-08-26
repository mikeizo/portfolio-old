<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * About
 *
 * @ORM\Table(name="about")
 * @ORM\Entity
 */
class About
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
     * @var \DateTime
     *
     * @ORM\Column(name="year_from", type="datetime", nullable=false)
     * @Assert\NotBlank(message = "Year From cannot be blank")
     */
    private $yearFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="year_to", type="datetime", nullable=true)
     */
    private $yearTo;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     * @Assert\NotBlank(message = "Description cannot be blank")
     * @Assert\Length(
     *     min = 5,
     *     max = 500,
     *     minMessage = "Description must be at least {{ limit }} characters long",
     *     maxMessage = "Description cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;



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
     * @return integer
     */
    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    /**
     * @param integer $yearFrom
     *
     * @return self
     */
    public function setYearFrom($yearFrom)
    {
        $this->yearFrom = $yearFrom;

        return $this;
    }

    /**
     * @return integer
     */
    public function getYearTo()
    {
        return $this->yearTo;
    }

    /**
     * @param integer $yearTo
     *
     * @return self
     */
    public function setYearTo($yearTo)
    {
        $this->yearTo = $yearTo;

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
}

