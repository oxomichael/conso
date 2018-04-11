<?php

namespace CarbuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CarbuBundle\Entity\Fuel;
use CarbuBundle\Form\FuelType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FuelController extends Controller
{
    /**
     * @Route("/fuel/vehicle/{vehicle_oid}")
     * @Method({"GET"})
     * @return Response
     */
    public function indexAction($vehicle_oid)
    {
        $obfuscator = $this->get('optimus.obfuscator');

        $user = $this->getUser();

        $vehicleId = $obfuscator->decode($vehicle_oid);
        $vehicleM = $this->getDoctrine()->getManager()->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));
        $vehicle->oid = $obfuscator->encode($vehicle->getId());

        $fuelM = $this->getDoctrine()->getRepository('CarbuBundle:Fuel');
        $fuels = $fuelM->findBy(array('vehicle' => $vehicle), array('dateAdd' => 'desc'));
        if (count($fuels) > 0) {
            foreach($fuels as $fuel) {
                $fuel->oid = $obfuscator->encode($fuel->getId());
            }
        }

        return $this->render('@Carbu/Fuel/index.html.twig', array(
            'vehicle' => $vehicle,
            'fuels' => $fuels,
        ));
    }

    /**
     * @Route("/fuel/add/vehicle/{vehicle_oid}")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function addAction($vehicle_oid, Request $request)
    {
        $obfuscator = $this->get('optimus.obfuscator');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $vehicleId = $obfuscator->decode($vehicle_oid);
        $vehicleM = $em->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));

        // Distance calculation
        $fuelM = $em->getRepository('CarbuBundle:Fuel');

        // Previous
        $previousDistance = 0;
        $fuelPrevious = $fuelM->findOneBy(array('vehicle' => $vehicle), array('dateAdd' => 'desc'));
        if ($fuelPrevious !== null) {
            $previousDistance = $fuelPrevious->getDistance();
        }

        $fuel = new Fuel();
        $fuel->setVehicle($vehicle);
        $fuel->setDistance($previousDistance);
        $form = $this->get('form.factory')->create(FuelType::class, $fuel);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            if ($fuelPrevious !== null) {
                $previousDistance = $fuelPrevious->getDistance();
                $fuel->setDistance($fuel->getDistance() - $previousDistance);
            } else {
                $fuel->setDistance(0);
            }

            // Save data
            $em->persist($fuel);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Plein enregistré.");

            return $this->redirectToRoute('carbu_full_index', array(
                'vehicle_oid' => $obfuscator->encode($vehicle->getId()))
            );
        }

        return $this->render('@Carbu/Fuel/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/fuel/edit/vehicle/{vehicle_oid}/id/{id}")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function editAction($id, $vehicle_oid, Request $request)
    {
        $obfuscator = $this->get('optimus.obfuscator');

        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $vehicleId = $obfuscator->decode($vehicle_oid);
        $vehicleM = $em->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));

        $fuelM = $em->getRepository('CarbuBundle:Fuel');
        $fuel = $fuelM->find($id);

        if (null === $fuel) {
            throw new NotFoundHttpException("Impossible de trouver le plein.");
        }

        $fuelPrevious = $fuelM->findPrevious($fuel->getDate(), $vehicle);
        $previousDistance = 0;
        if ($fuelPrevious !== null) {
            $previousDistance = $fuelPrevious->getDistance();
        }

        $fuel->setVehicle($vehicle);
        $form = $this->get('form.factory')->create(FuelType::class, $fuel);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            if ($previousMeter != 0) {
                $fuel->setDistance($fuel->getDistance() - $previousDistance);
            } else {
                $fuel->setDistance(0);
            }

            // Save data
            $em->persist($fuel);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Plein modifié.");

            return $this->redirectToRoute('carbu_fuel_index', array(
                'vehicle_oid' => $obfuscator->encode($vehicle->getId()))
            );
        } else {
            $fuel->setMeter($previousDistance);
        }

        return $this->render('@Carbu/Fuel/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/fuel/del/id/{id}")
     * @Method({"GET"})
     */
    public function delAction($id){}
}
