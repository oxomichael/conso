<?php

namespace CarbuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mileage
 *
 * @ORM\Table(name="mileage")
 * @ORM\Entity(repositoryClass="CarbuBundle\Repository\MileageRepository")
 */
class Mileage
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
     * @var int
     *
     * @ORM\Column(name="start", type="integer")
     */
    private $start;

    /**
     * @var int
     *
     * @ORM\Column(name="end", type="integer")
     */
    private $end;

    /**
     * @var Fuel
     *
     * @ORM\OneToOne(targetEntity="CarbuBundle\Entity\Fuel")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fuel;


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
     * Set start
     *
     * @param integer $start
     *
     * @return Mileage
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param integer $end
     *
     * @return Mileage
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param Fuel $fuel
     * @return $this
     */
    public function setFuel(Fuel $fuel)
    {
        $this->fuel = $fuel;

        return $this;
    }

    /**
     * @return Fuel
     */
    public function getFuel()
    {
        return $this->fuel;
    }
}

