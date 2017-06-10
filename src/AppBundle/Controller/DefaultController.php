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
		// find default positions & latest readings
		$readings = $this->getLatestReadingService()->findLatestDefaultPositionReadings();

		return $this->render('AppBundle:Default:dashboard.html.twig', array(
			'readings' => $readings
		));
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
	 * @Route("/exports", name="exports")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function exportsAction(Request $request)
	{
		return $this->render('AppBundle:Default:exports.html.twig');
	}

	/**
	 * @return \AppBundle\Service\LatestReadingService
	 */
	public function getLatestReadingService()
	{
		return $this->get('app.service.latest_reading');
	}
}