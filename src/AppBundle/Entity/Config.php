<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity
 */
class Config
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
     * @ORM\Column(name="headline", type="text", nullable=false)
     * @Assert\NotBlank(message = "Headline cannot be blank")
     */
    private $headline;

    /**
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=false)
     * @Assert\NotBlank(message = "About cannot be blank")
     */
    private $about;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message = "Email cannot be blank")
     * @Assert\Email(
     *     message = "'{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="analytics", type="text", nullable=false)
     */
    private $analytics;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="text", nullable=false)
     */
    private $quote;


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
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param string $headline
     *
     * @return self
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $about
     *
     * @return self
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnalytics()
    {
        return $this->analytics;
    }

    /**
     * @param string $analytics
     *
     * @return self
     */
    public function setAnalytics($analytics)
    {
        $this->analytics = $analytics;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * @param string $quote
     *
     * @return self
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;

        return $this;
    }
}
