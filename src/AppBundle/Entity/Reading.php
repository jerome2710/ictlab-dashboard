<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Reading
 *
 * @ORM\Table(name="reading")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReadingRepository")
 */
class Reading
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
	 * @var Sensor
	 *
	 * @ManyToOne(targetEntity="AppBundle\Entity\Sensor", cascade={"persist"})
	 * @JoinColumn(name="sensor_id", referencedColumnName="id")
	 */
    private $sensor;

    /**
     * @var SensorType
     *
     * @ManyToOne(targetEntity="AppBundle\Entity\SensorType", cascade={"persist"})
	 * @JoinColumn(name="sensortype_id", referencedColumnName="id")
     */
    private $sensorType;

    /**
     * @var float
     *
     * @ORM\Column(name="reading", type="float")
     */
    private $reading;


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
     * Set sensor
     *
     * @param Sensor $sensor
     * @return Reading
     */
    public function setSensor(Sensor $sensor)
    {
        $this->sensor = $sensor;

        return $this;
    }

    /**
     * Get sensor
     *
     * @return Sensor
     */
    public function getSensor()
    {
        return $this->sensor;
    }

    /**
     * Set sensorType
     *
     * @param SensorType $sensorType
     * @return Reading
     */
    public function setSensorType(SensorType $sensorType)
    {
        $this->sensorType = $sensorType;

        return $this;
    }

    /**
     * Get sensorType
     *
     * @return SensorType
     */
    public function getSensorType()
    {
        return $this->sensorType;
    }

    /**
     * Set reading
     *
     * @param float $reading
     * @return Reading
     */
    public function setReading($reading)
    {
        $this->reading = $reading;

        return $this;
    }

    /**
     * Get reading
     *
     * @return float 
     */
    public function getReading()
    {
        return $this->reading;
    }
}
