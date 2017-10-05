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

		if ($agency && $agency != 'Indeed-organic' && $agency != 'Indeed-cpc') {
			if ($agency == 'jobdiagnosis-cpc') {
				$exDisciplines = array(
						'Clinical Nurse Specialist',
						'Nurse Practitioner',
						'Certified Nurse Mid-Wife',
						'Physician Assistant',
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
						'Speech Language Pathologist',
				);
			}
			elseif ($agency == 'upward-cpc') {
				$exDisciplines = array(
						'Clinical Nurse Specialist',
						'Nurse Practitioner',
						'Certified Nurse Mid-Wife',
						'Physician Assistant',
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
						'Speech Language Pathologist',
				);
			}
			else {
				$exDisciplines = array(
						'Clinical Nurse Specialist',
						'Nurse Practitioner',
						'Certified Nurse Mid-Wife',
						'Physician Assistant',
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
				);
			}
		} else {
			$exDisciplines = array(
				'Certified Nurse Anesthetist',
				'Physician Assistant',
				'Certified Registered Nurse Anesthetist'
			);
		}

		$exSpecsSecond = array(
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
			'Skilled Nursing Facility',
			'Supervisor',
			'School Nurse',
			'Home Health',
			'Long Term Care Nursing',
			'Long Term Care Facility',
			'Rehab and Skilled Nursing',
			'Psychiatric',
			'Corrections',
			'Emergency Care Center',
			'Acute Care Hospital',
			'Rehabilitation Facility',
			'Case Manager',
			'Retail Pharmacy',
			'Hospice Pallative Care',
			'Out Patient Clinic',
			'OR-Outpatient Pre/Post',
			'Occupational Health'
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
				'Rehab and Skilled Nursing',
				'Psychiatric',
				'Corrections',
				'Emergency Care Center',
				'Acute Care Hospital',
				'Rehabilitation Facility',
				'Case Manager',
				'Retail Pharmacy',
				'Hospice Pallative Care',
				'Out Patient Clinic',
				'OR-Outpatient Pre/Post',
				'Occupational Health'
		);

		$this->initFields($exDisciplines, $exSpecs, $exSpecsSecond, $agency);
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
			'csrf_protection' => false,
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
	public function initFields($exDisciplines, $exSpecs, $exSpecsSecond, $agency)
	{
		$disciplineList = array_diff($this->helper->getDiscipline(), $exDisciplines);
		$specialtyList = array_diff($this->helper->getSpecialty(), $exSpecs);
		$specialtyListSecond = array_diff($this->helper->getSpecialty(), $exSpecsSecond);
		asort($disciplineList);
		asort($specialtyList);
		asort($specialtyListSecond);
		if (!$agency || ($agency == 'Indeed-organic' || $agency == 'Indeed-cpc')) {
			$disciplineList = array(5 => $disciplineList[ 5 ]) + $disciplineList;
			$disciplineList = array(3 => $disciplineList[ 3 ]) + $disciplineList;
			$disciplineList = array(2 => $disciplineList[ 2 ]) + $disciplineList;
			$disciplineList = array(1 => $disciplineList[ 1 ]) + $disciplineList;
			if (isset($disciplineList[ 0 ])) {
				$disciplineList = array(0 => $disciplineList[ 0 ]) + $disciplineList;
			}
		} else {
			$disciplineList = array(5 => $disciplineList[ 5 ]) + $disciplineList;
		}

		$this->disciplineList = $disciplineList;
		$this->specsList = $specialtyList;
		$this->specsListSecond = $specialtyListSecond;
	}
}
