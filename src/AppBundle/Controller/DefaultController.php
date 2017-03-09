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
	 * @Route("/sandbox/", name="dashboard")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sandboxAction(Request $request)
	{
		dump($this->get('api.service')->getSensors());
		die();
	}
}