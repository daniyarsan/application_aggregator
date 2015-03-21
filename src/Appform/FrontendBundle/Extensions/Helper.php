<?php
namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class Helper
{
	private $em;

	private $boolean = array(
		0 => 'No',
		1 => 'Yes',
	);

	private $states = array(
		'Alabama'=>'AL',
		'Alaska'=>'AK',
		'Arizona'=>'AZ',
		'Arkansas'=>'AR',
		'California'=>'CA',
		'Colorado'=>'CO',
		'Connecticut'=>'CT',
		'Delaware'=>'DE',
		'Florida'=>'FL',
		'Georgia'=>'GA',
		'Hawaii'=>'HI',
		'Idaho'=>'ID',
		'Illinois'=>'IL',
		'Indiana'=>'IN',
		'Iowa'=>'IA',
		'Kansas'=>'KS',
		'Kentucky'=>'KY',
		'Louisiana'=>'LA',
		'Maine'=>'ME',
		'Maryland'=>'MD',
		'Massachusetts'=>'MA',
		'Michigan'=>'MI',
		'Minnesota'=>'MN',
		'Mississippi'=>'MS',
		'Missouri'=>'MO',
		'Montana'=>'MT',
		'Nebraska'=>'NE',
		'Nevada'=>'NV',
		'New Hampshire'=>'NH',
		'New Jersey'=>'NJ',
		'New Mexico'=>'NM',
		'New York'=>'NY',
		'North Carolina'=>'NC',
		'North Dakota'=>'ND',
		'Ohio'=>'OH',
		'Oklahoma'=>'OK',
		'Oregon'=>'OR',
		'Pennsylvania'=>'PA',
		'Rhode Island'=>'RI',
		'South Carolina'=>'SC',
		'South Dakota'=>'SD',
		'Tennessee'=>'TN',
		'Texas'=>'TX',
		'Utah'=>'UT',
		'Vermont'=>'VT',
		'Virginia'=>'VA',
		'Washington'=>'WA',
		'West Virginia'=>'WV',
		'Wisconsin'=>'WI',
		'Wyoming'=>'WY'
	);

	private $currency = array(
		'rub' => 'RUB',
		'usd' => 'USD',
		'eur' => 'EUR'
	);
	
	private $specialty = array(
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
		'ICU- Medical',
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
		'Long Term Acute Care (LTAC)',
		'Long Term Care Nursing',
		'Maternal-Newborn',
		'Medical-Surgical',
		'Newborn Nursery',
		'Occupational Health',
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
		'Acute Care Hospital',
		'Doctors Office',
		'Emergency Care Center',
		'Home Visits',
		'Hospital Pharmacy',
		'Long Term Acute Care Facility',
		'Long Term Care Facility',
		'Out Patient Clinic',
		'Rehabilitation Facility',
		'Retail Pharmacy',
		'Skilled Nursing Facility');


	private $discipline = array('Physician Assistant',
		'Nurse Practitioner',
		'Certified Nurse Mid-Wife',
		'Clinical Nuse Specialist',
		'Certified Nurse Anesthetist',
		'Registered Nurse',
		'RN First Assistant',
		'LPN / LVN',
		'Perfusionist',
		'Occupational Therapist',
		'Occupational Therapy Assistant',
		'Physical Therapist',
		'Physical Therapy Assistant',
		'Recreational Therapist',
		'Respiratory Therapist',
		'Speech Language Pathologist',
		'Pharmacist',
		'Pharmacy Tech',
		'Cath Lab Tech',
		'Surgical Technologist',
		'Certified Surgical Technologist',
		'Certified First Assistant',
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
		'Nursing Assistant',
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
		'Vascular Ultrasound Tech');

	private $yearsExperience = array(
		'New grad',
		'0-1 Year',
		'1-3 Years',
		'3-5 Years',
		'5-10 Years',
		'10-15 Years',
		'20+ Years' );

	private $assignementTime = array(
		'ASAP',
		'1-3 Months',
		'3-6 Months',
		'Undecided');

	function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getAssTime($key = null)
	{
		if (isset($this->assignementTime[$key])) {
			return $this->assignementTime[$key];
		}

		return $this->assignementTime;
	}

	public function getExpYears($key = null)
	{
		if (isset($this->yearsExperience[$key])) {
			return $this->yearsExperience[$key];
		}

		return $this->yearsExperience;
	}

	public function getBoolean($key = null)
	{
		if (isset($this->boolean[$key])) {
			return $this->boolean[$key];
		}

		return $this->boolean;
	}

	public function getStates($key = null)
	{
		if (isset($this->states[$key])) {
			return $this->states[$key];
		}

		return $this->states;
	}

	public function getSpecialty($key = null)
	{
		if (isset($this->specialty[$key])) {
			return $this->specialty[$key];
		}

		return $this->specialty;
	}

	public function getDiscipline($key = null)
	{
		if (isset($this->discipline[$key])) {
			return $this->discipline[$key];
		}

		return $this->discipline;
	}

	public function getCurrency($key = null)
	{
		if (isset($this->currency[$key])) {
			return $this->currency[$key];
		}

		return $this->currency;
	}
}