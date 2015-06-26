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
            ->add('firstName', 'text', array('label' => '* First Name'))
            ->add('lastName', 'text', array('label' => '* Last Name'))
            ->add('email', 'email', array('label' => '* Email Address'))
            ->add('appOrigin', 'hidden')
            ->add('personalInformation', new PersonalInformationType($this->helper), array(
                'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation'))
            ->add('document', new DocumentType($this->helper), array(
                'data_class' => 'Appform\FrontendBundle\Entity\Document',
                'label' => 'Optional upload your resume/cv',
                'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Applicant',
            'csrf_protection' => false,
            'cascade_validation' => true
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
