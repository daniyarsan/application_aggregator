<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInformationType extends AbstractType
{

    private $helper;
    private $disciplineList;
    private $specsList;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper)
    {
        $this->helper = $helper;
        $exDisciplines =  array(
            'Certified Nurse Anesthetist',
            'LPN / LVN',
            'Nursing Assistant',
            'Phlebotomy Tech',
            'Certified Surgical Technologist');

        $exSpecs = array(
            'Charge Nurse',
            'Clinic Nursing',
            'Dementia Nursing',
            'Director of Nursing',
            'Endoscopy',
            'House Supervisor',
            'Immunization',
            'Legal / Chart Review',
            'OR-ENT',
            'PICC Nurse',
            'Doctors Office',
            'Home Visits',
            'Hospital Pharmacy',
            'Long Term Acute Care Facility',
            'Skilled Nursing Facility',
            'Supervisor',
            'School Nurse');

        $this->disciplineList = array_diff($this->helper->getDiscipline(), $exDisciplines);
        asort($this->disciplineList);
        $this->specsList = array_diff($this->helper->getSpecialty(), $exSpecs);
        asort($this->specsList);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$expanded = $this->helper->getRequest() ? false : true;
        $expanded = false;
        $builder
            ->add('phone', 'text', array('label' => '* Phone Number', 'attr' => array('placeholder'=> '* Phone Number')))
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                                           'label' => '* Select Home State',
                                           'placeholder' => '* Select Home State'))
            ->add('discipline', 'choice', array('choices' => $this->disciplineList,
                                                'label' => '* Select Discipline',
                                                'placeholder' => '* Select Discipline'))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getLicenseStates(),
                                                  'multiple' => true,
                                                  'label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->specsList,
                                                      'label' => '* Speciality Primary',
                                                      'placeholder' => '* Speciality Primary'))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'label' => '* Years Experience',
                                                    'placeholder' => '* Years Experience'))
            ->add('specialtySecondary', 'choice', array('choices' => $this->specsList,
                                                        'label' => 'Specialty Secondary)',
                                                        'required' => false,
                                                        'placeholder' => 'Specialty Secondary'))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                                                    'required' => false,
                                                    'label' => 'Years Experience',
                                                    'placeholder' => 'Years Experience'))
            ->add('desiredAssignementState', 'choice', array('label' => '* Assignment Location Preference',
                                                             'choices' => $this->helper->getDaStates(),
                                                             'multiple' => true,
                                                             'expanded' => $expanded))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                                                            'label' => '* Experienced Traveler?',
                                                            'placeholder' => '* Experienced Traveler?'))
            ->add('isOnAssignement','choice', array('choices' => $this->helper->getBoolean(),
                                                    'label' => '* Are you on Assignment?',
                                                    'placeholder' => '* Are you on Assignment?'))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                                                     'label' => '* Assignment availability',
                                                     'placeholder' => '* Assignment availability'))
            ->add('question', null, array('label' => 'Question',
                                          'required' => false))
            ->add('completion', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => '(If Yes) Assignment completion date',
                'required' => true,
                'attr' => array(
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
