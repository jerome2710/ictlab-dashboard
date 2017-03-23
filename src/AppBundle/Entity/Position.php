<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Position
 *
 * @ORM\Table(name="position")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PositionRepository")
 */
class Position
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
	 * @var Sensor
	 *
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Sensor", mappedBy="position")
	 */
    private $sensor;

	/**
	 * @var boolean
	 */
    private $scheduleDeletion;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Position
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

	/**
	 * @return Sensor
	 */
	public function getSensor()
	{
		return $this->sensor;
	}

	/**
	 * @param Sensor $sensor
	 */
	public function setSensor($sensor)
	{
		$this->sensor = $sensor;
	}

	/**
	 * @return bool
	 */
	public function isScheduleDeletion()
	{
		return $this->scheduleDeletion;
	}

	/**
	 * @param bool $scheduleDeletion
	 */
	public function setScheduleDeletion($scheduleDeletion)
	{
		$this->scheduleDeletion = $scheduleDeletion;
	}
}
