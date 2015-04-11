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
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                                           'label' => 'State',
                                           'placeholder' => 'Select Home State  '))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(),
                                                'label' => 'Discipline / Professional License',
                                                'placeholder' => 'Select Discipline'))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getLicenseStates(),
                                                  'multiple' => true,
                                                  'label' => '* Licensed State(s)',
                                                  'placeholder' => 'Select State(s)'))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                      'label' => 'Specialty - Primary',
                                                      'placeholder' => 'Select Primary Specialty'))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'label' => 'Years experience primary specialty?',
                                                    'placeholder' => 'Select Years of Experience'))
            ->add('specialtySecondary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                        'label' => 'Specialty - Secondary(Optional)',
                                                        'placeholder' => 'Select Specialty - Secondary'))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'label' => 'Years experience secondary specialty?',
                                                    'placeholder' => 'Select Years of Experience'))
            ->add('desiredAssignementState', 'choice', array('choices' => $this->helper->getDaStates(),
                                                             'label' => 'Assignment Location Preference',
                                                             'multiple' => true,
                                                             'placeholder' => 'Select Assignment Location Preference'))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                                                            'label' => 'Are you an experienced Traveler?',
                                                            'required' => false,
                                                            'placeholder' => 'Select an option'))
            ->add('isOnAssignement','choice', array('choices' => $this->helper->getBoolean(),
                                                    'label' => 'On Assignment?',
                                                    'required' => false,
                                                    'placeholder' => 'Select State(s)'))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                                                     'label' => 'Assignment availability',
                                                     'placeholder' => 'Select an option'))
            ->add('question', null, array('label' => 'Question'))
            ->add('completion', 'date', array(
                'placeholder' => '',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Completion Date',
                'attr' => array(
                    'class' => 'dateField'
                )));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation',
            'csrf_protection' => false,
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
