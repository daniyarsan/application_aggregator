<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{

    private $helper;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper)
    {
        $this->helper = $helper;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $expanded = $this->helper->getRequest() ? false : true;
        $builder
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                                           'label' => '* Home State',
                                           'placeholder' => 'Select State',
            'required' => false))
            ->add('discipline', 'choice', array('choices' => $this->helper->getDiscipline(),
                                                'label' => '* Discipline / Professional License',
                                                'placeholder' => 'Select Discipline',
                                                'required' => false
                                                ))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->helper->getSpecialty(),
                                                      'label' => '* Specialty - Primary',
                                                      'required' => false,
                                                      'placeholder' => 'Primary Specialty'))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                                                            'label' => '* Are you an experienced Traveler?',
                                                            'required' => false,
                                                            'placeholder' => 'Experienced Traveler'))
            ->add('isOnAssignement','choice', array('choices' => $this->helper->getBoolean(),
                                                    'required' => false,
                                                    'placeholder' => 'On Assignment?'))
            ->add('hasResume','choice', array('choices' => $this->helper->getBoolean(),
                                              'required' => false,
                                              'placeholder' => 'Has Resume'))
            ->add('fromdate','date', array(
                'label' => 'From: ',
                'widget' => 'single_text'
            ))
            ->add('todate','date', array(
                'label' => 'To: ',
                'widget' => 'single_text'
            ));
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
        return 'appform_frontendbundle_search';
    }
}
