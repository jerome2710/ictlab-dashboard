<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\SensorsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SettingsController
 * @package AppBundle\Controller
 *
 * @Route("/settings")
 */
class SettingsController extends Controller
{
	/**
	 * @Route("/sensors", name="dashboard")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sensorsAction(Request $request)
	{
		$form = $this->createForm(new SensorsType($this->getDoctrine()->getRepository('AppBundle:Sensor')->findAll()));
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// @TODO: handle form submission
		}

		return $this->render('AppBundle:Default:sensors.html.twig', array(
			'form' => $form->createView()
		));
	}
}