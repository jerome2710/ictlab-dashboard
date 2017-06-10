<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Sensor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
			'read_only' => true,
			'label' => 'UUID'
		));

		$builder->add('position', EntityType::class, array(
			'class' => 'AppBundle\Entity\Position',
			'choice_label' => 'name',
			'placeholder' => 'Choose a position inside the house',
		));

		$builder->add('battery', TextType::class, array(
			'read_only' => true,
			'label' => 'Battery percentage'
		));

		$builder->add('delete', SubmitType::class);
		$builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
			/** @var Sensor $formData */
			$formData = $event->getData();
			$form = $event->getForm();

			if ($form['delete']->isClicked()) {
				$formData->setScheduleDeletion(true);
			}
		});
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