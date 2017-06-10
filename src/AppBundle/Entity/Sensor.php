<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Sensor
 *
 * @ORM\Table(name="sensor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SensorRepository")
 */
class Sensor
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
     * @ORM\Column(name="uuid", type="string", length=255, unique=true)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

	/**
	 * @var Position
	 *
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Position", inversedBy="sensor")
	 * @ORM\JoinColumn(name="position_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
	 */
    private $position;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="AppBundle\Entity\SensorType", inversedBy="sensors", indexBy="id")
	 * @ORM\JoinColumn(onDelete="NO ACTION")
	 * @ORM\JoinTable(
	 *     name="sensors_x_sensortypes",
	 *     joinColumns={@ORM\JoinColumn(name="sensor_id", referencedColumnName="id", onDelete="CASCADE")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="sensortype_id", referencedColumnName="id")}
	 * )
	 */
    private $sensorTypes;

    /**
     * @var float
     *
     * @ORM\Column(name="battery", type="float")
     */
    private $battery;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="datetime", type="datetime")
	 */
    private $datetime;

	/**
	 * @var boolean
	 */
    private $scheduleDeletion;

	/**
	 * Sensor constructor.
	 */
    public function __construct()
	{
		$this->sensorTypes = new ArrayCollection();
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
     * Set uuid
     *
     * @param string $uuid
     * @return Sensor
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string 
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Sensor
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set battery
     *
     * @param float $battery
     * @return Sensor
     */
    public function setBattery($battery)
    {
        $this->battery = $battery;

        return $this;
    }

    /**
     * Get battery
     *
     * @return float 
     */
    public function getBattery()
    {
        return $this->battery;
    }

	/**
	 * @return \DateTime
	 */
	public function getDatetime()
	{
		return $this->datetime;
	}

	/**
	 * @param \DateTime $datetime
	 */
	public function setDatetime($datetime)
	{
		$this->datetime = $datetime;
	}

	/**
	 * @return Position
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @param Position $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getSensorTypes()
	{
		return $this->sensorTypes;
	}

	/**
	 * @param SensorType $sensorType
	 */
	public function addSensorType(SensorType $sensorType)
	{
		if (!$this->sensorTypes->contains($sensorType)) {
			$this->sensorTypes->add($sensorType);
		}
	}

	/**
	 * @param SensorType $sensorType
	 */
	public function removeSensorType(SensorType $sensorType)
	{
		$this->sensorTypes->remove($sensorType);
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

	/**
	 * @return string
	 */
	public function getName()
	{
		if ($this->getPosition()) {
			return $this->getPosition()->getName();
		}

		return $this->getUuid();
	}

	/**
	 * Return a JSON representation of sensor type names
	 *
	 * @return string
	 */
	public function getJsonSensorTypes()
	{
		$typeNames = array();
		foreach ($this->getSensorTypes() as $sensorType) {
			/** @var SensorType $sensorType */
			$typeNames[] = $sensorType->getName();
		}

		return json_encode($typeNames);
	}
}
