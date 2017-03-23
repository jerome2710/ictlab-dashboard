<?php

namespace ApiBundle\Domain\Messaging\Command;

use ApiBundle\Service\ApiService;
use AppBundle\Entity\Sensor;
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

			if (!$oSensor = $this->entityManager->getRepository('AppBundle:Sensor')->findOneByUuid($aSensor['_id'])) {
				$oSensor = new Sensor();
			}

			$oSensor->setUuid($aSensor['_id']);
			$oSensor->setLocation($aSensor['location']);
			$oSensor->setBattery($aSensor['battery']);

			$oDateTime = new \DateTime;
			$oDateTime->setTimestamp($aSensor['timestamp']);
			$oSensor->setDatetime($oDateTime);

			$this->entityManager->merge($oSensor);
		}

		$this->entityManager->flush();
	}
}