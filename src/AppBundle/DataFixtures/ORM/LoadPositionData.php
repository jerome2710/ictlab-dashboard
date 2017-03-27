<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Position;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData implements FixtureInterface
{
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$positionNames = array(
			'Outside',
			'Living room',
			'Greenhouse'
		);

		foreach ($positionNames as $positionName) {
			$position = new Position();
			$position->setName($positionName);
			$position->setReadonly(true);

			$manager->persist($position);
		}

		$manager->flush();
	}
}