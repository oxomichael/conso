<?php

namespace CarbuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Full
 *
 * @ORM\Table(name="full")
 * @ORM\Entity(repositoryClass="CarbuBundle\Repository\FullRepository")
 */
class Full
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="decimal", precision=5, scale=2)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=5, scale=2)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="distance", type="integer")
     */
    private $distance;

    /**
     * @var int
     *
     * @ORM\Column(name="meter", type="integer")
     */
    private $meter = 0;

    /**
     * @var Vehicle
     *
     * @ORM\ManyToOne(targetEntity="CarbuBundle\Entity\Vehicle")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vehicle;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
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
     * @return Full
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
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Full
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Full
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set distance
     *
     * @param integer $distance
     *
     * @return Full
     */
    public function setDistance($distance)
    {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get meter
     *
     * @return int
     */
    public function getMeter()
    {
        return $this->meter;
    }

    /**
     * Set meter
     *
     * @param integer $meter
     *
     * @return Full
     */
    public function setMeter($meter)
    {
        $this->meter = $meter;

        return $this;
    }

    /**
     * Get distance
     *
     * @return int
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param Vehicle $vehicle
     * @return Full
     */
    public function setVehicle(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }
}


