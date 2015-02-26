<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EducationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'School Name'))
            ->add('degree', null, array('label' => 'Degree'))
            ->add('location', null, array('label' => 'School Location'))
            ->add('graduationDate', 'date', array('label' => 'Graduation Date',
                                                'widget' => 'single_text',
                                                'format' => 'yyyy-MM-dd',
                                                'attr' => array(
                                                    'class' => 'dateField'
                                                )))
            ->add('courseOfStudy', null, array('label' => 'Course of Study'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\Education'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_education';
    }
}
