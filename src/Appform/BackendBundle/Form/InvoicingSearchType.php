<?php

namespace Appform\BackendBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoicingSearchType extends AbstractType
{

    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setRequired(false)
            ->add('id')
            ->add('agency_group', 'choice', array(
                'choices' => $this->buildAgencyGroup(),
                'empty_data' => null,
                'empty_value' => "Select Agency Group"
            ))
            ->add('candidate_id')
            ->add('discipline', 'choice', array(
                'choices' => $this->fillDisciplines(),
                'empty_data' => null,
                'empty_value' => "Select Discipline"))
            ->add('specialty_primary', 'choice', array(
                'choices' => $this->fillSpecialties(),
                'empty_data' => null,
                'empty_value' => "Select Specialty"))
            ->add('generate_report', 'choice', array(
                'choices' => ['' => 'Choose format', 'CSV' => 'CSV', 'Excel5' => 'XLS',]
            ))
            ->add('fromdate', 'date', array(
                'html5' => false,
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker', 'placeholder' => '...From Date']
            ))
            ->add('todate', 'date', array(
                'html5' => false,
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker', 'placeholder' => '...To Date']
            ))
            ->add('search', 'submit', array(
                'label' => 'Search',
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_backendbundle_stats_invoice_search';
    }

    public function buildAgencyGroup()
    {
        $choices = [];
        $table2Repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('AppformBackendBundle:AgencyGroup');
        $table2Objects = $table2Repository->findAll();
        foreach ($table2Objects as $table2Obj) {
            $choices[ $table2Obj->getName() ] = $table2Obj->getName();
        }

        return $choices;
    }

    public function fillDisciplines()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Discipline')->getDisciplinesList($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }

    public function fillSpecialties()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesList($this->agency);
        foreach ($disciplinesList as $discipline) {
            $list[$discipline['id']] = $discipline['name'];
        }
        return $list;
    }
}
