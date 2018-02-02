<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Regex;

class SearchVisitorsType extends AbstractType
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
		/*$constraints = ['candidateId' => new Regex(array(
										'pattern'   => '/^[0-9]+$/',
										'match'     => true,
										'message'   => 'Only numbers are allowed'))];*/
		$builder
			->setRequired(false)
			->add('referrers', 'choice', array('choices' => $this->getReferersList(),
					'label' => '* Referrers',
					'placeholder' => 'Referrers'))
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
			->add('show_all', 'choice', array('choices' => $this->helper->getBoolean(),
					'label' => '* Show all in list?',
					'placeholder' => 'Show all on page'));
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
		foreach ($this->em->getRepository('AppformFrontendBundle:Visitor')->getAvailableReferers() as $ref) {
			if ($ref[ 'referrer' ] != null) {
				$data[ $ref[ 'referrer' ] ] = $ref[ 'referrer' ];
			}
		}
		return $data;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'search_visitors';
	}
}
