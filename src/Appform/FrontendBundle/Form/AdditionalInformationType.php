<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdditionalInformationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hasLicense', null, array('label' => 'Has your license/ certification ever been investigated, suspended or revoked?'))
            ->add('hasLicenseExplanation', null, array('label' => 'If yes, please explain'))
            ->add('felony', null, array('label' => 'In the last 7 years, convicted of a felony?'))
            ->add('felonyExplanation', null, array('label' => 'If yes, please explain'))
            ->add('proofEligibility', null, array('label' => 'Can you provide proof of eligibility tow ork in the United States?'))
            ->add('citezenship', null, array('label' => 'Are you a United States Citizen?'))
            ->add('visaType', null, array('label' => 'If NO, please provide Visa type'))
            ->add('certification', null, array('label' => 'If NOT a US Citizen have you passed US Certification or NCLEX?'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\AdditionalInformation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_additionalinformation';
    }
}
