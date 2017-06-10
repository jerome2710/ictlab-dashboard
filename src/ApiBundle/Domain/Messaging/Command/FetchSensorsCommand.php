<?php

namespace ApiBundle\Domain\Messaging\Command;

class FetchSensorsCommand
{
	/** @var $name */
	private $name;

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}
}