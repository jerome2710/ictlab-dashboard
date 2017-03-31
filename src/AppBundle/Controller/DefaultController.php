<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
	/**
	 * @Route("/", name="dashboard")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction(Request $request)
	{
		return $this->render('AppBundle:Default:dashboard.html.twig');
	}

	/**
	 * @Route("/statistics", name="statistics")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function statisticsAction(Request $request)
	{
		$sensors = $this->getDoctrine()->getRepository('AppBundle:Sensor')->findAll();

		return $this->render('AppBundle:Default:statistics.html.twig', array(
			'sensors' => $sensors
		));
	}

	/**
	 * @Route("/sandbox/", name="sandbox")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sandboxAction(Request $request)
	{
		die();
	}
}