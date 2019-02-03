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
            'nexxt_RN2',
            'Nexxt_RN2-cpc'
        );

        $therapist = array(
            'nexxt_therapist',
            'nexxt_PTOT'
        );

        $exDisciplines = array();
        $exSpecs = array();

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
            $exSpecs = array(
                'Cardiac Cath Lab',
                'Case Manager',
                'Charge Nurse',
                'Clinic Nursing',
                'Corrections',
                'Dementia Nursing',
                'Dialysis',
                'Director of Nursing',
                'Emergency Department',
                'Endoscopy',
                'Home Health',
                'Hospice Pallative Care',
                'House Supervisor',
                'ICU-Medical',
                'ICU-Burn',
                'ICU-Critical Care',
                'ICU-Neonatal',
                'ICU-Neurology',
                'ICU-Pediatric',
                'ICU-Surgical',
                'ICU-Trauma',
                'Immunization',
                'Labor & Delivery',
                'Legal / Chart Review',
                'LTAC',
                'Long Term Care Nursing',
                'Maternal-Newborn',
                'Medical-Surgical',
                'Newborn Nursery',
                'Oncology',
                'OR-CVOR',
                'OR-ENT',
                'OR-General Surgery',
                'OR-Neurology',
                'OR-Orthopedic',
                'OR-Outpatient Pre/Post',
                'OR-Pediatric Surgery',
                'OR-Plastic Surgery',
                'Orthopedic Nursing',
                'PACU',
                'Pediatrics',
                'PICC Nurse',
                'Postpartum',
                'Progresssive Care-Stepdown',
                'Psychiatric',
                'Rehab and Skilled Nursing',
                'School Nurse',
                'Supervisor',
                'Telemetry',
                'Wound Care-Certified',
                'Hospital Pharmacy',
                'Retail Pharmacy',
                'OR-RN First Assistant',
                'ICU-Cardiac Unit'
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
            $exSpecs = array(
                'Charge Nurse',
                'Clinic Nursing',
                'Dementia Nursing',
                'Director of Nursing',
                'Endoscopy',
                'House Supervisor',
                'Immunization',
                'Legal / Chart Review',
                'OR-ENT',
                'PICC Nurse',
                'Doctors Office',
                'Home Visits',
                'Hospital Pharmacy',
                'Long Term Acute Care Facility',
                'Long Term Care Nursing',
                'Skilled Nursing Facility',
                'Supervisor',
                'School Nurse',
                'Corrections',
                'Emergency Care Center',
                'Acute Care Hospital',
                'Rehabilitation Facility',
                'Retail Pharmacy',
                'Out Patient Clinic',
                'OR-RN First Assistant'
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
            $exSpecs = array(
                'Charge Nurse',
                'Clinic Nursing',
                'Dementia Nursing',
                'Director of Nursing',
                'Endoscopy',
                'House Supervisor',
                'Immunization',
                'Legal / Chart Review',
                'OR-ENT',
                'PICC Nurse',
                'Doctors Office',
                'Home Visits',
                'Hospital Pharmacy',
                'Long Term Acute Care Facility',
                'Long Term Care Nursing',
                'Skilled Nursing Facility',
                'Supervisor',
                'School Nurse',
                'Corrections',
                'Emergency Care Center',
                'Acute Care Hospital',
                'Rehabilitation Facility',
                'Retail Pharmacy',
                'Out Patient Clinic',
                'OR-RN First Assistant'
            );
        }

        $this->initFields($exDisciplines, $exSpecs);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $expanded = false;
        $builder
            ->add('phone', 'text', array('label' => '* Phone Number', 'attr' => array('placeholder' => '* Phone Number')))
            ->add('state', 'choice', array(
                'choices' => $this->helper->getStates(),
                'label' => '* Select Home State',
                'placeholder' => '* Select Home State'))
            ->add('discipline', 'choice', array(
                'choices' => $this->disciplineList,
                'label' => '* Select Discipline',
                'placeholder' => '* Select Discipline'))
            ->add('licenseState', 'choice', array(
                'choices' => $this->helper->getLicenseStates(),
                'multiple' => true,
                'label' => '* Licensed State(s)'))
            ->add('specialtyPrimary', 'choice', array(
                'choices' => $this->specsList,
                'label' => '* Speciality Primary',
                'placeholder' => '* Speciality Primary'))
            ->add('yearsLicenceSp', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'label' => '* Years Experience',
                'placeholder' => '* Years Experience'))
            ->add('specialtySecondary', 'choice', array(
                'choices' => $this->specsListSecond,
                'label' => 'Specialty Secondary)',
                'required' => false,
                'placeholder' => 'Specialty Secondary'))
            ->add('yearsLicenceSs', 'choice', array(
                'choices' => $this->helper->getExpYears(),
                'required' => false,
                'label' => 'Years Experience',
                'placeholder' => 'Years Experience'))
            ->add('desiredAssignementState', 'choice', array(
                'label' => '* Assignment Location Preference',
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
    public function initFields($exDisciplines, $exSpecs)
    {
        $disciplineList = array_diff($this->helper->getDiscipline(), $exDisciplines);
        $specialtyList = array_diff($this->helper->getSpecialty(), $exSpecs);
        $specialtyListSecond = array_diff($this->helper->getSpecialty(), $exSpecs);

        asort($disciplineList);
        asort($specialtyList);
        asort($specialtyListSecond);

        $disciplineOrderList = array(
            'Physician Assistant',
            'Nurse Practitioner',
            'Certified Nurse Mid-Wife',
            'Clinical Nurse Specialist',
            'Registered Nurse',
            'LPN / LVN',
            'Nursing Assistant',
        );

        foreach (array_reverse($disciplineOrderList) as $item) {
            if (($match = array_search($item, $disciplineList)) !== false) {
                $temp = array($match => $disciplineList[$match]);
                unset($disciplineList[$match]);
                $disciplineList = $temp + $disciplineList;
            }
        }

        $this->disciplineList = $disciplineList;
        $this->specsList = $specialtyList;
        $this->specsListSecond = $specialtyListSecond;
    }

}
