<?php

namespace AppBundle\Service;

use AppBundle\Entity\Sensor;
use Doctrine\ORM\EntityManager;

class SensorService
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	/**
	 * Try to get a readable name for the give uuid by sensor position
	 *
	 * @param $uuid
	 * @return string
	 */
	public function getReadableName($uuid)
	{
		$name = $uuid;

		/** @var Sensor $sensor */
		$sensor = $this->entityManager->getRepository('AppBundle:Sensor')->findOneByUuid($uuid);
		if ($sensor && $sensor->getPosition()) {
			$name = $sensor->getPosition()->getName();
		}

		return $name;
	}
}