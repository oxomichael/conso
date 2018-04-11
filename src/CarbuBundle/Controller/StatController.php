<?php

namespace CarbuBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class StatController extends Controller
{
    /**
     * @Route("/stat/index/vehicle/{vehicle_oid}")
     * @Method({"GET"})
     * @return Response
     */
    public function indexAction()
    {
        // List year stat
        return $this->render('CarbuBundle:Stat:index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/stat/detail")
     * @Method({"GET"})
     * @return Response
     */
    public function detailAction()
    {
        return $this->render('Carbu:Stat:detail', array(
            // ...
        ));
    }
}
