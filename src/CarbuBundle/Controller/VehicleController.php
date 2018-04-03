<?php

namespace CarbuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CarbuBundle\Entity\Vehicle;
use CarbuBundle\Form\VehicleType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class VehicleController extends Controller
{
    /**
     * @Route("/vehicle")
     * @Method({"GET"})
     * @return Response
     */
    public function indexAction()
    {
        $obfuscator = $this->get('optimus.obfuscator');

        $user = $this->getUser();

        $vehicleM = $this->getDoctrine()->getManager()->getRepository('CarbuBundle:Vehicle');
        $vehicles = $vehicleM->findBy(array('user' => $user));

        if (count($vehicles) > 0) {
            foreach($vehicles as $vehicle) {
                $vehicle->oid = $obfuscator->encode($vehicle->getId());
             }
        }

        return $this->render('@Carbu/Vehicle/index.html.twig', array(
            'vehicles' => $vehicles,
        ));
    }

    /**
     * @Route("/vehicle/add")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function addAction(Request $request)
    {
        $vehicle = new Vehicle();
        $form = $this->get('form.factory')->create(VehicleType::class, $vehicle);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $vehicle->setUser($this->getUser());
            $em->persist($vehicle);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Véhicule enregistré.");

            $obfuscator = $this->get('optimus.obfuscator');

            return $this->redirectToRoute('carbu_vehicle_index', array('oid' => $obfuscator->encode($vehicle->getId())));
        }

        return $this->render('@Carbu/Vehicle/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/vehicle/upd/{oid}")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function updAction($oid, Request $request)
    {
        $obfuscator = $this->get('optimus.obfuscator');
        $id = $obfuscator->decode($oid);

        $vehicleM = $this->getDoctrine()->getManager()->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->find($id);

        if (null === $vehicle) {
            throw new NotFoundHttpException("Impossible de trouver le véhicule.");
        }

        $form = $this->get('form.factory')->create(VehicleType::class, $vehicle);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vehicle);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Véhicule modifié.");

            return $this->redirectToRoute('carbu_vehicle_index', array('oid' => $obfuscator->encode($vehicle->getId())));
        }

        return $this->render('@Carbu/Vehicle/upd.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/vehicle/del")
     */
    public function delAction()
    {
        return $this->render('@Carbu/Vehicle/del.html.twig', array());
    }
}
