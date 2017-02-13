<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Entity\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$this->createUser('0864155@hr.nl');
	}

	/**
	 * @param string $email
	 */
	protected function createUser($email)
	{
		$user = new User();
		$user->setUsername($email);
		$user->setEmail($email);
		$user->setPlainPassword('test');
		$user->setEnabled(true);
		$user->setRoles(['ROLE_ADMIN']);

		$this->getUserManager()->updateUser($user, true);
	}

	/**
	 * @return object
	 */
	protected function getUserManager()
	{
		return $this->container->get('fos_user.user_manager');
	}

	/**
	 * @param ContainerInterface|null $container
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}