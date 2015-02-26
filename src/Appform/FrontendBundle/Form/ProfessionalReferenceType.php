<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfessionalReferenceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'Reference Name'))
            ->add('email', null, array('label' => 'Reference Email'))
            ->add('title', null, array('label' => 'Reference Title'))
            ->add('phone', null, array('label' => 'Reference Phone'))
            ->add('address', null, array('label' => 'Address'))
            ->add('city', null, array('label' => 'City'))
            ->add('state', null, array('label' => 'State'))
            ->add('facility', null, array('label' => 'Facility'))
            ->add('unit', null, array('label' => 'Unit'))
            ->add('facilityPhone', null, array('label' => 'Facility Phone'))
            ->add('facilityFax', null, array('label' => 'Facility Fax'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\ProfessionalReference'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_professionalreference';
    }
}
