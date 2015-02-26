<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfessionalLicenseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('primaryLicense', null, array('label' => 'My Primary Professional License is'))
            ->add('secondaryLicense', null, array('label' => 'I also have an "Active" license / certification in'))
            ->add('CPRCertification', null, array('label' => 'I presently have a valid active CPR Certification?'))
            ->add('advancedCertification', null, array('label' => 'I have Advanced Certifications in'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\ProfessionalLicense'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_professionallicense';
    }
}
