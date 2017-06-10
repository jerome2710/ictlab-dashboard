<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

class PositionsType extends AbstractType
{
	/** @var Position[] $positions */
	private $positions;

	/**
	 * PositionsType constructor.
	 * @param $positions
	 */
	public function __construct($positions)
	{
		$this->positions = $positions;
	}

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('positions', CollectionType::class, array(
			'entry_type' => PositionType::class,
			'allow_add' => true,
			'allow_delete' => true,
			'label' => 'Positions',
			'data' => $this->positions
		));
	}
}