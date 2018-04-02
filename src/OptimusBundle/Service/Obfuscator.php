<?php
namespace OptimusBundle\Service;

use Jenssegers\Optimus\Optimus;

class Obfuscator
{
    private $optimus;

    private $prime;
    private $inverted;
    private $random;

    public function __construct($prime, $inverted, $random)
    {
        $this->prime = $prime;
        $this->inverted = $inverted;
        $this->random = $random;

        $this->optimus = new Optimus($this->prime, $this->inverted, $this->random);
    }

    /**
     * Encode ID
     *
     * @param number $id
     * @return number
     */
    public function encode($id)
    {
        return $this->optimus->encode($id);
    }

    /**
     * Decode ID
     *
     * @param number $id
     * @return number
     */
    public function decode($id)
    {
        return $this->optimus->decode($id);
    }
}