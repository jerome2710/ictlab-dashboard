<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SensorType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('uuid', TextType::class, array(
			'read_only' => true
		));

		$builder->add('location', TextType::class, array(
			'read_only' => true
		));

		$builder->add('battery', TextType::class, array(
			'read_only' => true
		));
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'AppBundle\Entity\Sensor',
		));
	}

	/**
	 * @return string
	 */
	public function getBlockPrefix()
	{
		return 'sensor';
	}
}