<?php

namespace Appform\BackendBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Regex;

class SearchType extends AbstractType
{

    private $container;
    private $helper;
    private $em;

    function __construct(Container $container)
    {
        $this->container = $container;
        $this->manager = $this->container->get('doctrine.orm.entity_manager');
        $this->helper = $this->container->get('helper');
        $this->em = $this->container->get('doctrine.orm.default_entity_manager');
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = ['candidateId' => new Regex(array(
            'pattern' => '/^[0-9]+$/',
            'match' => true,
            'message' => 'Only numbers are allowed'))];
        $builder
            ->setRequired(false)
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                'label' => '* Home State',
                'placeholder' => 'Select State'))
            ->add('candidateId', 'text', array(
                'constraints' => $constraints[ 'candidateId' ],
                'attr' => ['placeholder' => 'Candidate Id']))
            ->add('name', 'text', array('label' => false, 'attr' => ['placeholder' => 'Name']))
            ->add('referrers', 'choice', array('choices' => $this->getReferersList(),
                'multiple' => true,
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Refferers"
                ],
                'label' => '* Referrers',
                'placeholder' => 'Referrers'))
            ->add('discipline', 'choice', array('choices' => $this->fillDisciplines(),
                'label' => '* Discipline / Professional License',
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Disciplines"
                ],
                'multiple' => true))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->fillSpecialties(),
                'label' => '* Specialty - Primary',
                'attr' => [
                    'class' => 'select',
                    'multiple title' => "Select Specialties"
                ],
                'multiple' => true))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Are you an experienced Traveler?',
                'placeholder' => 'Experienced Traveler'))
            ->add('isOnAssignement', 'choice', array('choices' => $this->helper->getBoolean(),
                'placeholder' => 'On Assignment?'))
            ->add('hasResume', 'choice', array('choices' => $this->helper->getBoolean(),
                'placeholder' => 'Has Resume'))
            ->add('fromdate', 'date', array(
                'html5' => false,
                'label' => 'From: ',
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker']))
            ->add('todate', 'date', array(
                'html5' => false,
                'label' => 'To: ',
                'widget' => 'single_text',
                'attr' => ['class' => 'datepicker']))
            ->add('search', 'submit', array(
                'label' => 'Search'))
            ->add('generate_report', 'choice', array(
                'choices' => ['' => 'Choose format', 'CSV' => 'CSV', 'Excel5' => 'XLS'],
                'label' => '* Generate User report?',
                'placeholder' => 'Generate User report?'))
            ->add('show_all', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Show all in list?',
                'placeholder' => 'Show all on page'));
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

    public function getReferersList()
    {
        $data = false;
        foreach ($this->em->getRepository('AppformFrontendBundle:Applicant')->getAvailableReferers() as $ref) {
            if ($ref[ 'appReferer' ] != null) {
                $data[ $ref[ 'appReferer' ] ] = $ref[ 'appReferer' ];
            }
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_search';
    }

    public function fillDisciplines()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Discipline')->getDisciplinesList();
        foreach ($disciplinesList as $discipline) {
            $list[ $discipline[ 'id' ] ] = $discipline[ 'name' ];
        }
        return $list;
    }

    public function fillSpecialties()
    {
        $list = [];
        $disciplinesList = $this->manager->getRepository('AppformFrontendBundle:Specialty')->getSpecialtiesList();
        foreach ($disciplinesList as $discipline) {
            $list[ $discipline[ 'id' ] ] = $discipline[ 'name' ];
        }
        return $list;
    }
}
