<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{

	private $container;
	private $helper;
	private $em;

	function __construct(Container $container)
	{
		$this->container = $container;
		$this->helper = $this->container->get('helper');
		$this->em = $this->container->get('doctrine.orm.default_entity_manager');
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->setRequired(false)
			->add('state', 'choice', array('choices' => $this->helper->getStates(),
				'label' => '* Home State',
				'placeholder' => 'Select State'))
			->add('referrers', 'choice', array('choices' => $this->getReferersList(),
				'label' => '* Referrers',
				'placeholder' => 'Referrers'))
			->add('discipline', 'choice', array('choices' => $this->helper->getDisciplines(),
				'label' => '* Discipline / Professional License',
				'multiple' => true))
			->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialties(),
				'label' => '* Specialty - Primary',
				'multiple' => true))
			->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
				'label' => '* Are you an experienced Traveler?',
				'placeholder' => 'Experienced Traveler'))
			->add('isOnAssignement', 'choice', array('choices' => $this->helper->getBoolean(),
				'placeholder' => 'On Assignment?'))
			->add('hasResume', 'choice', array('choices' => $this->helper->getBoolean(),
				'placeholder' => 'Has Resume'))
			->add('fromdate', 'date', array(
				'html5' => false,
				'label' => 'From: ',
				'widget' => 'single_text',
				'attr' => ['class' => 'datepicker']))
			->add('todate', 'date', array(
				'html5' => false,
				'label' => 'To: ',
				'widget' => 'single_text',
				'attr' => ['class' => 'datepicker']))
			->add('search', 'submit', array(
				'label' => 'Search'))
			->add('generate_report', 'choice', array(
					'choices' => ['' => 'Choose format', 'CSV' => 'CSV', 'Excel5' => 'XLS'],
					'label' => '* Generate User report?',
					'placeholder' => 'Generate User report?'))
			->add('generate_report_table', 'choice', array('choices' => $this->helper->getBoolean(),
					'label' => '* Generate Report table?',
					'placeholder' => 'Generate Report table?'));
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

	public function getReferersList()
	{
		$data = false;
		foreach ($this->em->getRepository('AppformFrontendBundle:Applicant')->getAvailableReferers() as $ref) {
			if ($ref[ 'appReferer' ] != null) {
				$data[ $ref[ 'appReferer' ] ] = $ref[ 'appReferer' ];
			}
		}
		return $data;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'appform_frontendbundle_search';
	}
}
