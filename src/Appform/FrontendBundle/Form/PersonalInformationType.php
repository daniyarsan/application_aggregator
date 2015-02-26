<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInformationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */

    private $helper;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper)
    {
        $this->helper = $helper;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('discipline', null, array('label' => 'Discipline / Professional License'))
            ->add('specialtyPrimary', null, array('label' => 'Specialty - Primary'))
            ->add('specialtySecondary', null, array('label' => 'Specialty - Secondary(Optional)'))
            ->add('phone', null, array('label' => 'Phone Number'))
            ->add('preferredContactMethod', null, array('label' => 'Preferred Contact Method'))
            ->add('birthdate', 'date', array(
                'label' => 'Birthdate (MMDDYYYY)',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField'
                )
            ))
            ->add('socialSecurityNumber', null, array('label' => 'Social Security Number'))
            ->add('resume', null, array('label' => 'Resume'))
            ->add('experienced', null, array('label' => 'Experienced Healthcare Traveler?'))
            ->add('howhearaboutus', null, array('label' => 'How did you hear about us?'))
            ->add('timeLicence', null, array('label' => 'How long have you had your License/Certification?'))
            ->add('address', null, array('label' => 'Present Address - Street'))
            ->add('appartment', null, array('label' => 'Apt #'))
            ->add('city', null, array('label' => 'City'))
            ->add('state', null, array('label' => 'State'))
            ->add('zipcode', null, array('label' => 'Zip Code'))
            ->add('isTravelAssignementAddress', null, array('label' => 'Is this aTravel Assignment Address?'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_personalinformation';
    }
}
