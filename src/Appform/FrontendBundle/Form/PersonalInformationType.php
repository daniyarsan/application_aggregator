<?php

namespace Appform\FrontendBundle\Form;

use Appform\FrontendBundle\Entity\Discipline;
use Appform\FrontendBundle\Entity\DisciplineRepository;
use Appform\FrontendBundle\Entity\SpecialtyRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInformationType extends AbstractType
{

    private $helper;
    private $agency;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper, $manager, $agency = false)
    {
        $this->helper = $helper;
        $this->agency = $agency;
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $expanded = false;
        $builder
            ->add('phone', 'text', array('label' => '* Phone Number', 'attr' => array('placeholder' => '* Phone Number')))
            ->add('state', 'choice', array(
                'choices' => $this->helper->getStates(),
                'label' => '* Select Home State',
                'placeholder' => '* Select Home State'))
            ->add('discipline', 'choice',
                array(
                    'placeholder' => '* Select Discipline',
                    "choices" => $this->fillDisciplines()))
            ->add('licenseState', 'choice', array(
                'choices' => $this->helper->getLicenseStates(),
                'multiple' => true,
                'label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array(
                'choices' => $this->fillSpecialties(),
                'placeholder' => '* Speciality Primary'))
            ->add('yearsLicenceSp', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'label' => '* Years Experience',
                'placeholder' => '* Years Experience'))
            ->add('specialtySecondary', 'choice', array(
                'choices' => $this->fillSpecialties(),
                'label' => 'Specialty Secondary)',
                'required' => false,
                'placeholder' => 'Specialty Secondary'))
            ->add('yearsLicenceSs', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'required' => false,
                'label' => 'Years Experience',
                'placeholder' => 'Years Experience'))
            ->add('desiredAssignementState', 'choice', array(
                'label' => '* Assignment Location Preference',
                'choices' => $this->helper->getDaStates(),
                'multiple' => true,
                'expanded' => $expanded))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Experienced Traveler?',
                'placeholder' => '* Experienced Traveler?'))
            ->add('isOnAssignement', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Are you on Assignment?',
                'placeholder' => '* Are you on Assignment?'))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                'label' => '* Assignment availability',
                'placeholder' => '* Assignment availability'))
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
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_personalinformation';
    }


    public function fillDisciplines()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Discipline')->getDisciplinesList($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }

    public function fillSpecialties()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesList($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }
}
