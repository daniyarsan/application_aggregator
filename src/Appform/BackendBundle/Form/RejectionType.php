<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RejectionType extends AbstractType
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

	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$helper = $this->container->get('helper');
		$disciplineList = $helper->getDisciplines();
		$specList = $helper->getSpecialties();

		$builder
			->add('name')
			->add('vendor', 'choice', array('choices' => $this->getReferersList(),
				'label' => 'Vendors',
				'placeholder' => 'Choose Vendor'))
			->add('disciplinesList', 'choice', array(
				'choices' => $disciplineList,
				'empty_data'  => null,
				'multiple' => true,
				'empty_value' => "Select Discipline"))
			->add('specialtiesList', 'choice', array(
				'choices'    => $specList,
				'empty_data'  => null,
				'multiple' => true,
				'empty_value' => "Select Specialty"))
		->add('reject_message');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Appform\BackendBundle\Entity\Rejection'
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'appform_backendbundle_rejection';
	}

	public function getReferersList()
	{
		$data = false;
		foreach ($this->em->getRepository('AppformFrontendBundle:Applicant')->getAvailableReferers() as $ref) {
			if ($ref[ 'appReferer' ] != null) {
				$data[ $ref[ 'appReferer' ] ] = $ref[ 'appReferer' ];
			}
		}
		return ['all' => 'All'] + $data;
	}
}
