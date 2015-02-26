<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicantType extends AbstractType
{

    private $helper;

    public function __construct( \Appform\FrontendBundle\Extensions\Helper $helper ) {
        $this->helper = $helper;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array('label' => 'First Name'))
            ->add('middleName', null, array('label' => 'Middle Name'))
            ->add('lastName', null, array('label' => 'Last Name'))
            ->add('email', null, array('label' => 'Email Address'));

        $builder->add('PersonalInformation', new PersonalInformationType($this->helper));
		$builder->add('AdditionalInformation', new AdditionalInformationType($this->helper));
		$builder->add('WorkPreferences', new WorkPreferencesType($this->helper));
		$builder->add('EmergencyContacts', new EmergencyContactsType($this->helper));
		$builder->add('ProfessionalLicense', new ProfessionalLicenseType($this->helper));
		$builder->add('Education', new EducationType($this->helper));
		$builder->add('Employment', new EmploymentType($this->helper));
		$builder->add('ProfessionalReference', new ProfessionalReferenceType($this->helper));
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

    // Other methods omitted

    public function getDefaultOptions(array $options)
    {
        return array(
            'csrf_protection' => false,
            // Rest of options omitted
        );
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_applicant';
    }
}
