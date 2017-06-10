<?php

namespace AppBundle\Service;

use AppBundle\Entity\Reading;
use Doctrine\ORM\EntityManager;

class LatestReadingService {

	/** @var EntityManager */
	private $em;

	/**
	 * LatestReadingService constructor.
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @return array
	 */
	public function findLatestDefaultPositionReadings()
	{
		$aReadings = array();

		$defaultPositions = $this->em->getRepository('AppBundle:Position')->findBy(array(
			'readonly' => true
		));

		foreach ($defaultPositions as $defaultPosition) {
			$positionReadings = array();

			// some position might not have a sensor connected
			if ($defaultPosition->getSensor()) {
				$sensorReadings = $this->em->getRepository('AppBundle:Reading')->findBy(array(
					'sensor' => $defaultPosition->getSensor()
				));

				foreach ($sensorReadings as $sensorReading) {
					$positionReadings[$sensorReading->getSensorType()->getName()] = $sensorReading->getReading();
				}
			}

			$aReadings[$defaultPosition->getName()] = $positionReadings;
		}

		return $aReadings;
	}
}