<?php

namespace Appform\BackendBundle\Form;

use Appform\FrontendBundle\Entity\Specialty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DisciplineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setRequired(false)
            ->add('name')
            ->add('type', 'choice', [
                'choices' => ['nurse' => 'Nurse', 'therapist' => 'Therapist'],
                'placeholder' => 'Choose an option'
            ])
            ->add('short')
            ->add('hidden')
            ->add('order')
            ->add('specialties', 'entity', array(
                'class' => Specialty::class,
                'multiple' => true,
                'by_reference' => false,
                'attr' => array('class'=>'select')
            ))
            ->add('save', 'submit')
            ->add('saveAndExit', 'submit', ['label' => 'Save and Exit']);;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Discipline'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_discipline';
    }
}
