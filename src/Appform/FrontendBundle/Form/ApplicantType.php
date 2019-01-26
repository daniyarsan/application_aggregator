<?php

namespace Appform\FrontendBundle\Form;

use Appform\FrontendBundle\Validator\Constraints\ExistEmail;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicantType extends AbstractType
{

    private $helper;
    /**
     * @var
     */
    private $agency;

    public function __construct(Container $container, $applicant, $agency = false)
    {
        $this->helper = $container->get('helper');
        $this->agency = $agency;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array(
                'label' => '* First Name',
                'attr' => array('placeholder' => '* First Name')))
            ->add('lastName', 'text', array(
                'label' => '* Last Name',
                'attr' => array('placeholder' => '* Last Name')))
            ->add('email', 'email', array(
                'constraints' => array(new ExistEmail()),
                'label' => '* Email Address',
                'attr' => array('placeholder' => '* Email')))
            ->add('appOrigin', 'hidden', array('attr' => array('value' => 'desktop')))
            ->add('personalInformation', new PersonalInformationType($this->helper, $this->agency), array(
                'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation'))
            ->add('document', new DocumentType($this->helper), array(
                'data_class' => 'Appform\FrontendBundle\Entity\Document',
                'label' => 'Optional upload your resume/cv',
                'required' => false));
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
