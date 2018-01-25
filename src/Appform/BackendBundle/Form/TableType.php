<?php

namespace Appform\BackendBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TableType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('table', 'entity', array(
				'mapped' => false,
				'required' => false,
				'class' => 'AppformBackendBundle:Filter',
				'empty_value'   => 'None Selected',
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('f')
							->orderBy('f.id', 'ASC');
				},
			))
			->add('name')
			->add('save', 'submit');
	}

	/**
	 * @param OptionsResolverInterface $resolver
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Appform\BackendBundle\Entity\Filter'
		));
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'appform_backendbundle_table';
	}
}
/*		$files = ['doc'=>'Document', 'pdf' => 'pdf', 'xls' => 'xls'];
		->add('files', 'choice', array(
			'expanded' => true,
			'multiple' => true,
			'choices'  => $files,
			'data' => array_keys($files)
	))*/