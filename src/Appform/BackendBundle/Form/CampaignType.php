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
			->add('applicants', 'collection', array('type' => 'number',
					'allow_add' => true,
					'allow_delete' => true,
					'by_reference' => false,
					'options' => array(
							'required' => true,
							'attr'=> array('class' => 'input-xlarge'))))
			->add('agencygroup')
			->add('save', 'submit');
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
