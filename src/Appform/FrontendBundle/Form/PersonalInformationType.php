<?php

namespace Appform\FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PersonalInformationType extends AbstractType
{

    private $helper;
    private $disciplineList;
    private $specsList;

    public function __construct(\Appform\FrontendBundle\Extensions\Helper $helper, $agency = false)
    {
        $this->helper = $helper;

        $common = array(
            'upward-cpc',
            'hirednurses-cpc',
            'ZipRecruiter-cpc',
            'ziprecruiter-cpc',
            'jobs2careers-cpc'
        );

        $nurse = array(
            'nexxt_nurse',
            'nexxt_RN1',
            'nexxt_RN2'
        );

        $therapist = array(
            'nexxt_therapist',
            'nexxt_PTOT'
        );

        $exDisciplines = array();

        if (in_array($agency, $therapist)) {
            $exDisciplines = array(
                'Physician Assistant',
                'Nurse Practitioner',
                'Registered Nurse',
                'LPN / LVN',
                'Nursing Assistant',
                'RN First Surgical Assistant',
                'Perfusionist',
                'Recreational Therapist',
                'Respiratory Therapist',
                'Pharmacist-Hospital',
                'Pharmacy Tech',
                'Cath Lab Tech',
                'Surgical Tech General Surgery',
                'Certified Surgical Technologist',
                'CST First Surgical Assistant',
                'Anesthesia Tech',
                'Audiologist',
                'Bone Densitometry',
                'CT Scan Tech',
                'Cardiac Intervention Tech',
                'Cytologist',
                'Dialysis Tech',
                'Dosimetrist',
                'Echo Tech',
                'EEG Tech',
                'Emergency Medical Tech',
                'Emergency Room Tech',
                'Histologist',
                'Mammographer',
                'Medical Laboratory Tech',
                'Medical Tech',
                'Monitor Tech',
                'MRI Tech',
                'Nuclear Med Tech',
                'OB Ultrasound Tech',
                'Orthopedic Tech',
                'ParaMedic',
                'Pathology Assistant',
                'Phlebotomy Tech',
                'Polysomnographer Tech',
                'Psychologist',
                'Radiation Therapy Tech',
                'Radiology Tech',
                'Sterile Processing Tech',
                'Ultrasound Tech',
                'Vascular Intervention Tech',
                'Vascular Ultrasound Tech',
                'Surgical Tech CVOR',
                'Surgical Tech Labor & Delivery',
                'Surgical Tech Cath Lab',
                'Pharmacist-Retail',
                'Certified Registered Nurse Anesthetist',
                'Certified Nurse Mid-Wife',
                'Clinical Nurse Specialist',
            );
        }
        elseif (in_array($agency, $nurse)) {
            $exDisciplines = array(
                'RN First Surgical Assistant',
                'Perfusionist',
                'Recreational Therapist',
                'Respiratory Therapist',
                'Pharmacist-Hospital',
                'Pharmacy Tech',
                'Cath Lab Tech',
                'Surgical Tech General Surgery',
                'Certified Surgical Technologist',
                'CST First Surgical Assistant',
                'Anesthesia Tech',
                'Audiologist',
                'Bone Densitometry',
                'CT Scan Tech',
                'Cardiac Intervention Tech',
                'Cytologist',
                'Dialysis Tech',
                'Dosimetrist',
                'Echo Tech',
                'EEG Tech',
                'Emergency Medical Tech',
                'Emergency Room Tech',
                'Histologist',
                'Mammographer',
                'Medical Laboratory Tech',
                'Medical Tech',
                'Monitor Tech',
                'MRI Tech',
                'Nuclear Med Tech',
                'OB Ultrasound Tech',
                'Orthopedic Tech',
                'ParaMedic',
                'Pathology Assistant',
                'Phlebotomy Tech',
                'Polysomnographer Tech',
                'Psychologist',
                'Radiation Therapy Tech',
                'Radiology Tech',
                'Sterile Processing Tech',
                'Ultrasound Tech',
                'Vascular Intervention Tech',
                'Vascular Ultrasound Tech',
                'Surgical Tech CVOR',
                'Surgical Tech Labor & Delivery',
                'Surgical Tech Cath Lab',
                'Pharmacist-Retail',
                'Certified Registered Nurse Anesthetist',
                'Occupational Therapist',
                'Occupational Therapy Assistant',
                'Physical Therapist',
                'Physical Therapy Assistant',
                'Speech Language Pathologist'
            );
        }
        elseif (in_array($agency, $common)) {
            $exDisciplines = array(
                'RN First Surgical Assistant',
                'Perfusionist',
                'Recreational Therapist',
                'Respiratory Therapist',
                'Pharmacist-Hospital',
                'Pharmacy Tech',
                'Cath Lab Tech',
                'Surgical Tech General Surgery',
                'Certified Surgical Technologist',
                'CST First Surgical Assistant',
                'Anesthesia Tech',
                'Audiologist',
                'Bone Densitometry',
                'CT Scan Tech',
                'Cardiac Intervention Tech',
                'Cytologist',
                'Dialysis Tech',
                'Dosimetrist',
                'Echo Tech',
                'EEG Tech',
                'Emergency Medical Tech',
                'Emergency Room Tech',
                'Histologist',
                'Mammographer',
                'Medical Laboratory Tech',
                'Medical Tech',
                'Monitor Tech',
                'MRI Tech',
                'Nuclear Med Tech',
                'OB Ultrasound Tech',
                'Orthopedic Tech',
                'ParaMedic',
                'Pathology Assistant',
                'Phlebotomy Tech',
                'Polysomnographer Tech',
                'Psychologist',
                'Radiation Therapy Tech',
                'Radiology Tech',
                'Sterile Processing Tech',
                'Ultrasound Tech',
                'Vascular Intervention Tech',
                'Vascular Ultrasound Tech',
                'Surgical Tech CVOR',
                'Surgical Tech Labor & Delivery',
                'Surgical Tech Cath Lab',
                'Pharmacist-Retail',
                'Certified Registered Nurse Anesthetist',
                'Certified Nurse Mid-Wife',
                'Clinical Nurse Specialist',
            );
        }

        $this->initFields($exDisciplines);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$expanded = $this->helper->getRequest() ? false : true;
        $expanded = false;
        $builder
            ->add('phone', 'text', array('label' => '* Phone Number', 'attr' => array('placeholder' => '* Phone Number')))
            ->add('state', 'choice', array('choices' => $this->helper->getStates(),
                'label' => '* Select Home State',
                'placeholder' => '* Select Home State'))
            ->add('discipline', 'choice', array('choices' => $this->disciplineList,
                'label' => '* Select Discipline',
                'placeholder' => '* Select Discipline'))
            ->add('licenseState', 'choice', array('choices' => $this->helper->getLicenseStates(),
                'multiple' => true,
                'label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array('choices' => $this->specsList,
                'label' => '* Speciality Primary',
                'placeholder' => '* Speciality Primary'))
            ->add('yearsLicenceSp', 'choice', array('choices' => $this->helper->getExpYears(),
                'label' => '* Years Experience',
                'placeholder' => '* Years Experience'))
            ->add('specialtySecondary', 'choice', array('choices' => $this->specsListSecond,
                'label' => 'Specialty Secondary)',
                'required' => false,
                'placeholder' => 'Specialty Secondary'))
            ->add('yearsLicenceSs', 'choice', array('choices' => $this->helper->getExpYears(),
                'required' => false,
                'label' => 'Years Experience',
                'placeholder' => 'Years Experience'))
            ->add('desiredAssignementState', 'choice', array('label' => '* Assignment Location Preference',
                'choices' => $this->helper->getDaStates(),
                'multiple' => true,
                'expanded' => $expanded))
            ->add('isExperiencedTraveler', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Experienced Traveler?',
                'placeholder' => '* Experienced Traveler?'))
            ->add('isOnAssignement', 'choice', array('choices' => $this->helper->getBoolean(),
                'label' => '* Are you on Assignment?',
                'placeholder' => '* Are you on Assignment?'))
            ->add('assignementTime', 'choice', array('choices' => $this->helper->getAssTime(),
                'label' => '* Assignment availability',
                'placeholder' => '* Assignment availability'))
            ->add('completion', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => '(If Yes) Assignment completion date',
                'required' => true,
                'attr' => array(
                    'disabled' => 'disabled'
                )));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Appform\FrontendBundle\Entity\PersonalInformation',
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appform_frontendbundle_personalinformation';
    }

    public function moveItemTop(& $arr, $item)
    {
        $new_value = $arr[ $item ];
        unset($arr[ $item ]);
        array_unshift($arr, $new_value);
    }

    /**
     * @param $exDisciplines
     * @param $exSpecs
     */
    public function initFields($exDisciplines)
    {
        $disciplineList = array_diff($this->helper->getDiscipline(), $exDisciplines);
        asort($disciplineList);
        asort($specialtyList);
        asort($specialtyListSecond);

        $itemsFirst = array(
            3 => 'Nursing Assistant',
            5 => 'Registered Nurse',
        );

        foreach ($itemsFirst as $key => $item) {
            if ($match = array_search($item, $disciplineList)) {
                $output = array_diff_key($disciplineList, array_flip((array) $match));
                $disciplineList = array_merge(array($key => $item), $output);
            }
        }

        $this->disciplineList = $disciplineList;
        $this->specsList = $this->helper->getSpecialty();
        $this->specsListSecond = $this->helper->getSpecialty();
    }
}
