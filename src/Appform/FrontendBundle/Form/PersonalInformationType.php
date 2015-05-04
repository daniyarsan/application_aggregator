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
            ->add('phone', 'text', array('label' => '* Phone Number'))
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                                           'label' => '* State',
                                           'placeholder' => ''))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(),
                                                'label' => '* Discipline / Professional License',
                                                'placeholder' => ''))
            ->add('licenseState', 'text', array('label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                      'label' => '* Specialty - Primary',
                                                      'placeholder' => ''))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'label' => '* Years experience primary specialty?',
                                                    'placeholder' => ''))
            ->add('specialtySecondary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                        'label' => 'Specialty - Secondary(Optional)',
                                                        'required' => false,
                                                        'placeholder' => ''))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'required' => false,
                                                    'label' => 'Years experience secondary specialty?',
                                                    'placeholder' => ''))
            ->add('desiredAssignementState', 'text', array('label' => '* Assignment Location Preference'))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                                                            'label' => '* Are you an experienced Traveler?',
                                                            'placeholder' => ''))
            ->add('isOnAssignement','choice', array('choices' => $this->helper->getBoolean(),
                                                    'label' => '* On Assignment?',
                                                    'placeholder' => ''))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                                                     'label' => '* Assignment availability',
                                                     'placeholder' => ''))
            ->add('question', null, array('label' => 'Question'))
            ->add('completion', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Assignment completion date',
                'required' => false,
                'attr' => array(
                    'class' => 'dateField',
                    'disabled' => 'disabled'
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
