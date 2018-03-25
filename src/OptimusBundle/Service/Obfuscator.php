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

    public function encode($id)
    {
        return $this->optimus->encode($id);
    }

    public function decode($id)
    {
        return $this->optimus->decode($id);
    }
}