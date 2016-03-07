<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MailgroupType extends AbstractType
{
    public $applicantRep;

    public function __construct(\Appform\FrontendBundle\Repository\ApplicantRepository $ar)
    {
        $this->applicantRep = $ar;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('email')
            ->add('originsList', 'choice', array('label' => 'Select referers',
                'choices' => $this->getReferersList(),
                'multiple' => true,
                'expanded' => true)
            )
        ;
    }

    public function getReferersList () {
        $data = false;
        foreach($this->applicantRep->getAvailableReferers() as $ref) {
            if ($ref['appReferer'] != null) {
                $data[$ref['appReferer']] = $ref['appReferer'];
            }
        }
        return $data;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\BackendBundle\Entity\Mailgroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_mailgroup';
    }
}
