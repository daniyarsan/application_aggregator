<?php

namespace Appform\BackendBundle\Form;

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

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('phone', 'text', array(
                    'label' => 'Phone Number'
                )
            )
            ->add('state', 'choice', array(
                    'choices' => $this->helper->getStates()
                )
            )
            ->add('discipline', 'choice', array(
                    'choices' => $this->helper->getDiscipline()
                )
            )
            ->add('licenseState', 'choice', array(
                    'choices' => $this->helper->getLicenseStates(),
                    'multiple' => true,
                    'required' => false,
                    'post_max_size_message' => 10,
                    'attr' => array(
                        'size' => '10'
                    )
                )

            )
            ->add('specialtyPrimary', 'choice', array(
                    'choices' => $this->helper->getSpecialty()
                )
            )
            ->add('yearsLicenceSp', 'choice', array(
                    'choices' => $this->helper->getExpYears()
                )
            )
            ->add('specialtySecondary', 'choice', array(
                    'choices' => $this->helper->getSpecialty(),
                )
            )
            ->add('yearsLicenceSs', 'choice', array(
                    'choices' => $this->helper->getExpYears(),
                    'required' => false
                )
            )
            ->add('desiredAssignementState', 'choice', array(
                    'choices' => $this->helper->getDaStates(),
                    'multiple' => true,
                    'required' => false,
                    'attr' => array(
                        'size' => '10'
                    )
                )
            )
            ->add('isExperiencedTraveler', 'choice', array(
                    'choices' => $this->helper->getBoolean(),
                )
            )
            ->add('isOnAssignement', 'choice', array(
                    'choices' => $this->helper->getBoolean()
                )
            )
            ->add('assignementTime', 'choice', array(
                    'choices' => $this->helper->getAssTime()
                )
            )
            ->add('question')
            ->add('completion', 'date', array(
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                    'label' => 'Completion Date',
                    'required' => false,
                    'attr' => array(
                        'class' => 'dateField'
                    )
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_personalinformation';
    }

}