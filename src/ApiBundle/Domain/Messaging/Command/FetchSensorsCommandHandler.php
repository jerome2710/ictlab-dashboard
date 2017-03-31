<?php

namespace ApiBundle\Domain\Messaging\Command;

use ApiBundle\Service\ApiService;
use AppBundle\Entity\Sensor;
use AppBundle\Entity\SensorType;
use Doctrine\ORM\EntityManager;

class FetchSensorsCommandHandler
{
	/** @var ApiService $apiService */
	protected $apiService;

	/** @var EntityManager $entityManager */
	protected $entityManager;

	/**
	 * FetchSensorsCommandHandler constructor.
	 *
	 * @param ApiService $apiService
	 * @param EntityManager $entityManager
	 */
	public function __construct(ApiService $apiService, EntityManager $entityManager)
	{
		$this->apiService = $apiService;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param FetchSensorsCommand $command
	 */
	public function handle(FetchSensorsCommand $command)
	{
		$aSensors = $this->apiService->getSensors();

		foreach ($aSensors as $aSensor) {
			// Check if the sensor not already exists
			if (!$oSensor = $this->entityManager->getRepository('AppBundle:Sensor')->findOneByUuid($aSensor['_id'])) {
				$oSensor = new Sensor();
			}

			// Set general information
			$oSensor->setUuid($aSensor['_id']);
			$oSensor->setLocation($aSensor['location']);
			$oSensor->setBattery($aSensor['battery']);

			$oDateTime = new \DateTime;
			$oDateTime->setTimestamp($aSensor['timestamp']);
			$oSensor->setDatetime($oDateTime);

			// Find sensor types for this sensor
			$aSensorTypes = $this->apiService->getTypesBySensor($oSensor->getUuid());

			// Save all found sensor types
			foreach ($aSensorTypes as $aSensorType) {
				$oSensorType = $this->entityManager->getRepository('AppBundle:SensorType')->findOneByName($aSensorType['_id']);

				if (!$oSensorType) {
					$oSensorType = new SensorType();
					$oSensorType->setName($aSensorType['_id']);
					$this->entityManager->persist($oSensorType);
					$this->entityManager->flush();
				}

				$oSensor->addSensorType($oSensorType);
			}

			$this->entityManager->merge($oSensor);
		}

		$this->entityManager->flush();
	}
}