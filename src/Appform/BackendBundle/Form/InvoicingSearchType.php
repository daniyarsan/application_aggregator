<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoicingSearchType extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->setRequired(false)
			->add('id')
			->add('agency_group')
			->add('candidate_id')
			->add('discipline')
			->add('specialty_primary')
			->add('generate_report', 'choice', array(
					'choices' => ['' => 'Choose format', 'CSV' => 'CSV', 'Excel5' => 'XLS',]
			))
			->add('fromdate','date', array(
				'html5' => false,
				'required' => false,
				'widget' => 'single_text',
				'attr' => ['class' => 'datepicker']
			))
			->add('todate','date', array(
				'html5' => false,
				'required' => false,
				'widget' => 'single_text',
				'attr' => ['class' => 'datepicker']
			))
			->add('search', 'submit', array(
				'label' => 'Search',
			));
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'csrf_protection' => false,
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'appform_backendbundle_stats_invoice_search';
	}
}
