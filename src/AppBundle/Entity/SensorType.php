<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SensorType
 *
 * @ORM\Table(name="sensor_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SensorTypeRepository")
 */
class SensorType
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Sensor", mappedBy="sensorTypes", fetch="EAGER")
	 * @ORM\JoinColumn(onDelete="NO ACTION")
	 */
    private $sensors;

	/**
	 * SensorType constructor.
	 */
    public function __construct()
	{
		$this->sensors = new ArrayCollection();
	}

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
     * @return SensorType
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
	 * @return ArrayCollection
	 */
	public function getSensors()
	{
		return $this->sensors;
	}

	/**
	 * @param Sensor $sensor
	 */
	public function addPeriod(Sensor $sensor)
	{
		if (!$this->sensors->contains($sensor)) {
			$sensor->addSensorType($this);
			$this->sensors->add($sensor);
		}
	}

	/**
	 * @param Sensor $sensor
	 * @return $this
	 */
	public function removePeriod(Sensor $sensor)
	{
		$this->sensors->removeElement($sensor);
		$sensor->removeSensorType($this);

		return $this;
	}
}
