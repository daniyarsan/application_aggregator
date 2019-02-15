<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RejectionType extends AbstractType
{

    private $container;
    private $helper;
    private $em;

    function __construct(Container $container)
    {
        $this->container = $container;
        $this->helper = $this->container->get('helper');
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = $this->container->get('helper');

        $builder
            ->setRequired(false)
            ->add('name')
            ->add('vendor')
            ->add('vendorType')
            ->add('disciplinesList', 'entity', array(
                'class' => 'Appform\FrontendBundle\Entity\Discipline',
                'property' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Disciplines to Reject"
                ],
            ))
            ->add('specialtiesList', 'entity', array(
                'class' => 'Appform\FrontendBundle\Entity\Specialty',
                'property' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Specialties to Reject"
                ],
            ))
            ->add('disciplinesHide', 'entity', array(
                'class' => 'Appform\FrontendBundle\Entity\Discipline',
                'property' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Disciplines to Hide"
                ],
            ))
            ->add('specialtiesHide', 'entity', array(
                'class' => 'Appform\FrontendBundle\Entity\Specialty',
                'property' => 'name',
                'multiple' => true,
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Specialties to Hide"
                ],
            ))
            ->add('reject_message')
            ->add('submit', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\BackendBundle\Entity\Rejection'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_rejection';
    }

}
