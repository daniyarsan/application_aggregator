<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
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
        $expanded = $this->helper->getRequest() ? true : false;

        $builder
            ->add('phone', 'text', array('label' => '* Phone Number'))
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                                           'label' => '* State',
                                           'placeholder' => ''))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(),
                                                'label' => '* Discipline / Professional License',
                                                ))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getLicenseStates(),
                                                  'multiple' => true,
                                                  'expanded' => $expanded,
                                                  'label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                      'label' => '* Specialty - Primary',
                                                      'placeholder' => ''))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'label' => '* Primary specialty experience',
                                                    'placeholder' => ''))
            ->add('specialtySecondary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                        'label' => 'Specialty - Secondary(Optional)',
                                                        'required' => false,
                                                        'placeholder' => ''))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'required' => false,
                                                    'label' => 'Secondary specialty experience',
                                                    'placeholder' => ''))
            ->add('desiredAssignementState', 'choice', array('label' => '* Assignment Location Preference',
                                                             'choices' => $this->helper->getDaStates(),
                                                             'multiple' => true,
                                                             'expanded' => $expanded))
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
