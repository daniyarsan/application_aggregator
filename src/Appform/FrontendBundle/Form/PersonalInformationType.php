<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInformationType extends AbstractType
{

    private $helper;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper)
    {
        $this->helper = $helper;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array('label' => 'Phone Number'))
            ->add('state', 'choice', array('choices' => $this->helper->getStates(), 'label' => 'State','placeholder' => 'Select State'))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(), 'label' => 'Discipline / Professional License'))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getStates(),
                                                  'expanded' => true,
                                                  'multiple' => true))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(), 'label' => 'Specialty - Primary'))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),'label' => 'Years experience primary specialty?'))
            ->add('specialtySecondary', 'choice', array('choices' => $this->helper->getSpecialty(), 'label' => 'Specialty - Secondary(Optional)'))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                'label' => 'Years experience secondary  specialty?'))
            ->add('desiredAssignementState', 'choice', array(
                'choices' => $this->helper->getStates(),
                'label' => 'Desired Assignment State(s)',
                'placeholder' => 'Select State'))
            ->add('isExperiencedTraveler', 'choice', array(
                'choices' => $this->helper->getBoolean(),
                'label' => 'Are you an experienced Traveler?',
                'required' => false,
                'data' => 0,
                'placeholder' => 'Choose an option'))
            ->add('isOnAssignement','choice', array(
                'choices' => $this->helper->getBoolean(),
                'label' => 'Are you presently on assignment?',
                'required' => false,
                'data' => 0,
                'placeholder' => 'Choose an option',))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                'label' => 'When would you like an assignment?'))
            ->add('question', null, array('label' => 'Question'));
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
