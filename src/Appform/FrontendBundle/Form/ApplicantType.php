<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicantType extends AbstractType
{

    private $helper;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper)
    {
        $this->helper = $helper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array('label' => 'First Name'))
            ->add('middleName', 'text', array('label' => 'Middle Name'))
            ->add('lastName', 'text', array('label' => 'Last Name'))
            ->add('email', 'email', array('label' => 'Email Address'))
            ->add('personalInformation', new PersonalInformationType($this->helper), array(
                'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Applicant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_applicant';
    }
}
