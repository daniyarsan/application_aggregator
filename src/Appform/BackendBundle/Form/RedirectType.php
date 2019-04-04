<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RedirectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setRequired(false)
            ->add('discipline', 'entity', [
                'class' => 'Appform\FrontendBundle\Entity\Discipline',
                'property'  => 'name',
                'empty_value' => 'Select Discipline',
                'label' => 'Discipline'
            ])
            ->add('specialty', 'entity', [
                'class' => 'Appform\FrontendBundle\Entity\Specialty',
                'property'  => 'name',
                'empty_value' => 'Select Specialty',
                'label' => 'Specialty'
            ])
            ->add('redirectUrl');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Redirect'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_redirect';
    }
}
