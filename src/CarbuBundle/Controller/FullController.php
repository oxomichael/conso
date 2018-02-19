<?php

namespace CarbuBundle\Controller;

use CarbuBundle\Entity\Full;
use CarbuBundle\Form\FullType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FullController extends Controller
{
    /**
     * @Route("/full/vehicle/{vehicleId}")
     * @Method({"GET"})
     * @return Response
     */
    public function indexAction($vehicleId)
    {
        $user = $this->getUser();
        $vehicleM = $this->getDoctrine()->getManager()->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));

        $fullM = $this->getDoctrine()->getRepository('CarbuBundle:Full');
        $fulls = $fullM->findBy(array('vehicle' => $vehicle), array('date' => 'desc'));

        return $this->render('CarbuBundle:Full:index.html.twig', array(
            'vehicle' => $vehicle,
            'fulls' => $fulls,
        ));
    }

    /**
     * @Route("/full/add/vehicle/{vehicleId}")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function addAction($vehicleId, Request $request)
    {
        $user = $this->getUser();

        $full = new Full();
        $em = $this->getDoctrine()->getManager();
        $vehicleM = $em->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));

        // Distance calculation
        $fullM = $em->getRepository('CarbuBundle:Full');

        // Previous
        $meterLast = 0;
        $fullPrevious = $fullM->findOneBy(array('vehicle' => $vehicle), array('date' => 'desc'));
        if ($fullPrevious !== null) {
            $meterLast = $fullPrevious->getMeter();
        }

        $full->setVehicle($vehicle);
        $full->setMeter($meterLast);
        $form = $this->get('form.factory')->create(FullType::class, $full);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            if ($fullPrevious !== null) {
                $previousMeter = $fullPrevious->getMeter();
                $full->setDistance($full->getMeter() - $previousMeter);
            } else {
                $full->setDistance(0);
            }

            // Save data
            $em->persist($full);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Plein enregistré.");

            return $this->redirectToRoute('carbu_full_index', array('vehicleId' => $vehicleId));
        }

        return $this->render('CarbuBundle:Full:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/full/edit/vehicle/{vehicleId}/id/{id}")
     * @Method({"GET", "POST"})
     * @return Response
     */
    public function editAction($id, $vehicleId, Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $vehicleM = $em->getRepository('CarbuBundle:Vehicle');
        $vehicle = $vehicleM->findOneBy(array('user' => $user, 'id' => $vehicleId));

        $fullM = $em->getRepository('CarbuBundle:Full');
        $full = $fullM->find($id);

        if (null === $full) {
            throw new NotFoundHttpException("Impossible de trouver le plein.");
        }

        $fullPrevious = $fullM->findPrevious($full->getDate(), $vehicle);
        $previousMeter = 0;
        if ($fullPrevious !== null) {
            $previousMeter = $fullPrevious->getMeter();
        }

        $full->setVehicle($vehicle);
        $form = $this->get('form.factory')->create(FullType::class, $full);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            if ($previousMeter != 0) {
                $full->setDistance($full->getMeter() - $previousMeter);
            } else {
                $full->setDistance(0);
            }

            // Save data
            $em->persist($full);
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', "Plein modifié.");

            return $this->redirectToRoute('carbu_full_index', array('vehicleId' => $vehicleId));
        } else {
            $full->setMeter($previousMeter);
        }

        return $this->render('CarbuBundle:Full:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/full/del/id/{id}")
     * @Method({"GET"})
     */
    public function delAction($id){}
}
