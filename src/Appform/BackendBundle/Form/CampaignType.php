<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
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
		$files = ['doc'=>'Document', 'pdf' => 'pdf', 'xls' => 'xls'];

		$builder
			->add('name')
			->add('subject')
			->add('publishat', 'datetime', array('date_widget' => "single_text", 'time_widget' => "single_text"))
			->add('applicant', 'text')
			->add('agencygroup')
				->add('files', 'choice', array(
						'expanded' => true,
						'multiple' => true,
						'choices'  => $files,
						'data' => array_keys($files)
				))
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
