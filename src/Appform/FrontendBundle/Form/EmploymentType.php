<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EmploymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Employer Name'))
            ->add('startDate', 'date', array(
                'label' => 'Start Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField'
                )
            ))
            ->add('endDate', 'date', array(
                'label' => 'End Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField'
                )))
            ->add('address', null, array('label' => 'Address'))
            ->add('isContactable', null, array('label' => 'May we contact this employer?'))
            ->add('wasTravelAssignement', null, array('label' => 'Was this a Travel Assignment? '))
            ->add('position', null, array('label' => 'Position'))
            ->add('specialty', null, array('label' => 'Specialty / Unit'))
            ->add('city', null, array('label' => 'City'))
            ->add('state', null, array('label' => 'State'))
            ->add('supervisor', null, array('label' => 'Supervisor'))
            ->add('supervisorTitle', null, array('label' => 'Supervisors Title'))
            ->add('supervisorPhone', null, array('label' => 'Supervisors Phone #'))
            ->add('leavingReason', null, array('label' => 'Reason for Leaving'))
            ->add('dutyDescription', null, array('label' => 'Brief description of duties preformed'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Employment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_employment';
    }
}
