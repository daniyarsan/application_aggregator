<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Appform\BackendBundle\Form\DocumentType;

class ApplicantType extends AbstractType{

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
            ->add('candidateId')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('personalInformation', new PersonalInformationType($this->helper), array(
                    'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation'
                )
            )
            ->add('document', new DocumentType($this->helper), array(
                    'data_class' => 'Appform\FrontendBundle\Entity\Document',
                    'label' => 'Upload your file',
                    'required' => false
                )
            )
        ;
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
        return 'appform_backendbundle_aplicant';
    }

}