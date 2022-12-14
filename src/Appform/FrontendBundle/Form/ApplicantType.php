<?php

namespace Appform\FrontendBundle\Form;

use Appform\FrontendBundle\Validator\Constraints\ExistEmail;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ApplicantType extends AbstractType
{
    private $helper;
    private $agency;
    private $manager;

    public function __construct(Container $container, $manager, $agency = false)
    {
        $this->helper = $container->get('helper');
        $this->agency = $agency;
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array(
                'label' => '* First Name',
                'attr' => array('placeholder' => '* Given First Name')))
            ->add('lastName', 'text', array(
                'label' => '* Last Name',
                'attr' => array('placeholder' => '* Last Name')))
            ->add('email', 'email', array(
                'constraints' => array(new ExistEmail(array(
                    'message' => 'Such application already exists.'
                ))),
                'label' => '* Email Address',
                'attr' => array('placeholder' => '* Email'),
                'error_bubbling' => true
            ))
            ->add('appOrigin', 'hidden', array(
                'attr' => array('value' => 'desktop')
                ))
            ->add('personalInformation', new PersonalInformationType($this->helper, $this->manager, $this->agency))
            ->add('document', new DocumentType($this->helper), array(
                'label' => 'Optional upload your resume/cv',
                'required' => false))
        ->add('submit', 'submit');
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
