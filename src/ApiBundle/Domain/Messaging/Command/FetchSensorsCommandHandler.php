<?php

namespace ApiBundle\Domain\Messaging\Command;

use ApiBundle\Service\ApiService;
use AppBundle\Entity\Reading;
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
			if (!$oSensor = $this->entityManager->getRepository('AppBundle:Sensor')->findOneByUuid($aSensor['uuid'])) {
				$oSensor = new Sensor();
			}

			// Set general information
			$oSensor->setUuid($aSensor['uuid']);
			$oSensor->setLocation($aSensor['location']);
			$oSensor->setBattery($aSensor['battery']);

			$oDateTime = new \DateTime;
			$oDateTime->setTimestamp(substr($aSensor['timestamp'], 0, -3)); // remove microseconds
			$oSensor->setDatetime($oDateTime);

			// Find sensor types for this sensor
			$aSensorTypes = $this->apiService->getTypesBySensor($oSensor->getUuid());

			// Save all found sensor types
			foreach ($aSensorTypes as $aSensorType) {

				// Save sensor type
				$oSensorType = $this->entityManager->getRepository('AppBundle:SensorType')->findOneByName($aSensorType['type']);
				if (!$oSensorType) {
					$oSensorType = new SensorType();
					$oSensorType->setName($aSensorType['type']);
					$this->entityManager->persist($oSensorType);
					$this->entityManager->flush();
				}
				$oSensor->addSensorType($oSensorType);

				// Save reading
				$oReading = $this->entityManager->getRepository('AppBundle:Reading')->findOneBy(array(
					'sensor' => $oSensor,
					'sensorType' => $oSensorType
				));
				if (!$oReading) {
					$oReading = new Reading();
					$oReading->setSensor($oSensor);
					$oReading->setSensorType($oSensorType);
				}
				$oReading->setReading($aSensorType['reading']);
				$this->entityManager->persist($oReading);
			}

			$this->entityManager->merge($oSensor);
		}

		$this->entityManager->flush();
	}
}