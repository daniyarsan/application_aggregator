<?php

namespace Appform\BackendBundle\Form;

use Appform\BackendBundle\Entity\Agency;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgencyGroupType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('agencies', 'entity', array(
            	'class' => Agency::class,
	            'multiple' => true,
	            'attr' => array('class'=>'select')
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\BackendBundle\Entity\AgencyGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_agencygroup';
    }
}
