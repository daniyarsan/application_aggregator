<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WorkPreferencesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeOfAssignements', null, array('label' => 'What type of assignments are you interested in?'))
            ->add('preferredShifts', null, array('label' => 'Preferred Shifts?'))
            ->add('opportunitiesInStates', null, array('label' => 'I am interested in learning about opportunities in'))
            ->add('travelAssignementStatus', null, array('label' => 'Are you presently on a Travel Assignment?'))
            ->add('facility', null, array('label' => 'Which Facility?'))
            ->add('state', null, array('label' => 'State?'))
            ->add('completion', 'date', array(
                'label' => 'State? Assignment Completion Date',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'dateField'
                )))
            ->add('assignementStartTime', null, array('label' => 'When are you interested in starting an assignment?'))
            ->add('takeVehicle', null, array('label' => 'Do you take a vehicle on assignment?'))
            ->add('takeFriends', null, array('label' => 'Do you travel with family / friends on assignment?'))
            ->add('takePets', null, array('label' => 'Do you travelwith pet(s) on assignment?'))
            ->add('petsType', null, array('label' => 'If so, please specify pet type / breed'))
            ->add('petsWeight', null, array('label' => 'If so, please specify pet\'s weight'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\WorkPreferences'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_workpreferences';
    }
}
