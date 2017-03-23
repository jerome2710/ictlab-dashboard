<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PositionType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', TextType::class);

		$builder->add('delete', SubmitType::class);
		$builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
			/** @var Position $formData */
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
			'data_class' => Position::class,
		));
	}
}