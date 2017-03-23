<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Position;
use AppBundle\Entity\Sensor;
use AppBundle\Form\Type\PositionsType;
use AppBundle\Form\Type\SensorsType;
use Doctrine\ORM\EntityManager;
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
	 * @Route("/sensors", name="settings_sensors")
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function sensorsAction(Request $request)
	{
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getEntityManager();

		// sensors with low battery or warnings
		$sensorWarnings = $this->getDoctrine()->getRepository('AppBundle:Sensor')->findSensorWarnings();

		// build positions form
		$positionForm = $this->createForm(new PositionsType($this->getDoctrine()->getRepository('AppBundle:Position')->findAll()));
		$positionForm->handleRequest($request);

		// save or delete
		if ($positionForm->isSubmitted() && $positionForm->isValid()) {
			$formData = $positionForm->getData();

			if (!empty($formData['positions'])) {
				/** @var Position $position */
				foreach ($formData['positions'] as $position) {
					if ($position->isScheduleDeletion()) {
						$em->remove($position);
					} else {
						$em->persist($position);
					}
				}
				$em->flush();
			}
		}

		// build sensors form
		$sensorForm = $this->createForm(new SensorsType($this->getDoctrine()->getRepository('AppBundle:Sensor')->findAll()));
		$sensorForm->handleRequest($request);

		// save or delete
		if ($sensorForm->isSubmitted() && $sensorForm->isValid()) {
			$formData = $sensorForm->getData();

			if (!empty($formData['sensors'])) {
				/** @var Sensor $sensor */
				foreach ($formData['sensors'] as $sensor) {
					if ($sensor->isScheduleDeletion()) {
						$em->remove($sensor);
					} else {
						$em->persist($sensor);
					}
				}
				$em->flush();
			}
		}

		return $this->render('AppBundle:Default:sensors.html.twig', array(
			'sensorWarnings' => $sensorWarnings,
			'positionForm' => $positionForm->createView(),
			'sensorForm' => $sensorForm->createView()
		));
	}
}