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
            ->add('state', 'choice', array('choices' => $this->helper->getStates(), 'label' => 'State'))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(), 'label' => 'Discipline / Professional License'))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getStates()))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(), 'label' => 'Specialty - Primary'))
            ->add('yearsLicenceSp', 'date', array(
                'label' => 'Years experience primary specialty?',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField')))
            ->add('specialtySecondary', 'choice', array('choices' => $this->helper->getSpecialty(), 'label' => 'Specialty - Secondary(Optional)'))
            ->add('yearsLicenceSs', 'date', array(
                'label' => 'Years experience secondary  specialty?',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField')))
            ->add('desiredAssignementState', 'choice', array('choices' => $this->helper->getStates(), 'label' => 'Desired Assignment State(s)'))
            ->add('isExperiencedTraveler', null, array('label' => 'Are you an experienced Traveler?', 'required' => false))
            ->add('isOnAssignement', null, array('label' => 'Are you presently on assignment?', 'required' => false))
            ->add('assignementTime', 'date', array(
                'label' => 'When would you like an assignment?',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField')))
            ->add('question', null, array('label' => 'Question'))
            ->add('resume', 'file');
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
