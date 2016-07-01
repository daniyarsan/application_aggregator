<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CampaignType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name')
			->add('subject')
			->add('publishat')
			->add('applicants', 'choice', array(
				'multiple' => true))
			->add('agencygroup')
		->add('submit', 'submit');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Appform\BackendBundle\Entity\Campaign'
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'appform_backendbundle_campaign';
	}
}
