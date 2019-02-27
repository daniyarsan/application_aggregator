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
        $builder
            ->add('phone', 'text', array('label' => '* Phone Number', 'attr' => array('placeholder' => '* Phone Number')))
            ->add('state', 'choice', array(
                'choices' => $this->helper->getStates(),
                'placeholder' => '* Select Home State'))
            ->add('discipline', 'choice',
                array(
                    'placeholder' => '* Select Discipline',
                    "choices" => $this->fillDisciplines()))
            ->add('licenseState', 'choice', array(
                'choices' => $this->helper->getStatesShort(),
                'multiple' => true,
                'attr' => array(
                    'class' => 'chosen'
                )))
            ->add('specialtyPrimary', 'choice', array(
                'choices' => $this->fillSpecialties(),
                'placeholder' => '* Speciality Primary'))
            ->add('yearsLicenceSp', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'placeholder' => '* Years Experience'))
            ->add('specialtySecondary', 'choice', array(
                'choices' => $this->fillSpecialties(),
                'required' => false,
                'placeholder' => 'Specialty Secondary'))
            ->add('yearsLicenceSs', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'required' => false,
                'placeholder' => 'Years Experience'))
            ->add('desiredAssignementState', 'choice', array(
                'choices' => ['All States' => 'All States'] + $this->helper->getStatesShort(),
                'multiple' => true,
                'attr' => array(
                    'class' => 'chosen'
                )))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                'placeholder' => '* Experienced Traveler?'))
            ->add('isOnAssignement', 'choice', array('choices' => $this->helper->getBoolean(),
                'placeholder' => '* Are you on Assignment?'))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                'placeholder' => '* Assignment availability'))
            ->add('completion', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
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
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Discipline')->getDisciplinesListByAgency($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }

    public function fillSpecialties()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesListByAgency($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }
}
