<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Sensor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class SensorsType extends AbstractType
{
	/** @var Sensor[] $sensors */
	protected $sensors;

	/**
	 * SensorsType constructor.
	 * @param Sensor[] $sensors
	 */
	public function __construct($sensors)
	{
		$this->sensors = $sensors;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('sensors', CollectionType::class, array(
			'entry_type' => new SensorType(),
			'label' => 'Sensors',
			'data' => $this->sensors
		));
	}
}