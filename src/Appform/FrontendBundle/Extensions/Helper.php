<?php
namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class Helper
{
	private $em;
	private $request;

	/**
	 * @return mixed
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 * @param mixed $controller
	 */
	public function setRequest( $request ) {
		$this->request = $request;
		return $this;
	}


	private $boolean = array(
		0 => 'No',
		1 => 'Yes',
	);

	private $states = array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming'
	);


	private $licenseStates = array(
		'0' => 'Select License State',
		'Compact'=>'Compact',
		'AL'=>'AL',
		'AK'=>'AK',
		'AZ'=>'AZ',
		'AR'=>'AR',
		'CA'=>'CA',
		'CO'=>'CO',
		'CT'=>'CT',
		'DE'=>'DE',
		'DC'=>'DC',
		'FL'=>'FL',
		'GA'=>'GA',
		'HI'=>'HI',
		'ID'=>'ID',
		'IL'=>'IL',
		'IN'=>'IN',
		'IA'=>'IA',
		'KS'=>'KS',
		'KY'=>'KY',
		'LA'=>'LA',
		'ME'=>'ME',
		'MH'=>'MH',
		'MD'=>'MD',
		'MA'=>'MA',
		'MI'=>'MI',
		'MN'=>'MN',
		'MS'=>'MS',
		'MO'=>'MO',
		'MT'=>'MT',
		'NE'=>'NE',
		'NV'=>'NV',
		'NH'=>'NH',
		'NJ'=>'NJ',
		'NM'=>'NM',
		'NY'=>'NY',
		'NC'=>'NC',
		'ND'=>'ND',
		'OH'=>'OH',
		'OK'=>'OK',
		'OR'=>'OR',
		'PA'=>'PA',
		'RI'=>'RI',
		'SC'=>'SC',
		'SD'=>'SD',
		'TN'=>'TN',
		'TX'=>'TX',
		'UT'=>'UT',
		'VT'=>'VT',
		'VI'=>'VI',
		'VA'=>'VA',
		'WA'=>'WA',
		'WV'=>'WV',
		'WI'=>'WI',
		'WY'=>'WY');

	private $daStates = array(
		'0' => 'Select Destination State',
		'All States'=>'All States',
		'AL'=>'AL',
		'AK'=>'AK',
		'AZ'=>'AZ',
		'AR'=>'AR',
		'CA'=>'CA',
		'CO'=>'CO',
		'CT'=>'CT',
		'DE'=>'DE',
		'DC'=>'DC',
		'FL'=>'FL',
		'GA'=>'GA',
		'HI'=>'HI',
		'ID'=>'ID',
		'IL'=>'IL',
		'IN'=>'IN',
		'IA'=>'IA',
		'KS'=>'KS',
		'KY'=>'KY',
		'LA'=>'LA',
		'ME'=>'ME',
		'MH'=>'MH',
		'MD'=>'MD',
		'MA'=>'MA',
		'MI'=>'MI',
		'MN'=>'MN',
		'MS'=>'MS',
		'MO'=>'MO',
		'MT'=>'MT',
		'NE'=>'NE',
		'NV'=>'NV',
		'NH'=>'NH',
		'NJ'=>'NJ',
		'NM'=>'NM',
		'NY'=>'NY',
		'NC'=>'NC',
		'ND'=>'ND',
		'OH'=>'OH',
		'OK'=>'OK',
		'OR'=>'OR',
		'PA'=>'PA',
		'RI'=>'RI',
		'SC'=>'SC',
		'SD'=>'SD',
		'TN'=>'TN',
		'TX'=>'TX',
		'UT'=>'UT',
		'VT'=>'VT',
		'VI'=>'VI',
		'VA'=>'VA',
		'WA'=>'WA',
		'WV'=>'WV',
		'WI'=>'WI',
		'WY'=>'WY'
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
		'LTAC',
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
		'Skilled Nursing Facility',
		'OR-RN First Assistant'
	);


	private $discipline = array(
		'Physician Assistant',
		'Nurse Practitioner',
		'Certified Nurse Mid-Wife',
		'Clinical Nurse Specialist',
		'Certified Nurse Anesthetist',
		'Registered Nurse',
		'RN First Surgical Assistant',
		'LPN / LVN',
		'Perfusionist',
		'Occupational Therapist',
		'Occupational Therapy Assistant',
		'Physical Therapist',
		'Physical Therapy Assistant',
		'Recreational Therapist',
		'Respiratory Therapist',
		'Speech Language Pathologist',
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
		'Vascular Ultrasound Tech',
		'Surgical Tech CVOR',
		'Surgical Tech Labor & Delivery',
		'Surgical Tech Cath Lab',
		'Pharmacist-Retail');

	private $discipline_short = array(
			'PA',
			'NP',
			'CNM',
			'CNS',
			'CRNA',
			'RN',
			'RNFA',
			'LPN / LVN',
			'Perfusionist',
			'OT',
			'OTA',
			'PT',
			'PTA',
			'RCIS',
			'RT',
			'SLP',
			'Pharmacist-Hospital',
			'Pharmacy Tech',
			'Cath Lab Tech',
			'ST',
			'CST',
			'CFA',
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
			'EMT',
			'Emergency Room Tech',
			'Histologist',
			'Mammographer',
			'Medical Laboratory Tech',
			'Medical Tech',
			'Monitor Tech',
			'MRI Tech',
			'Nuclear Med Tech',
			'CNA',
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
			'ST/CVOR',
			'ST/L&D',
			'ST/Cath Lab',
			'Pharmacist-Retail');

	private $specialty_short = array(
			'Cath Lab',
			'Case Mgr',
			'Charge ',
			'Clinic',
			'Corrections',
			'Dementia',
			'Dialysis',
			'DON',
			'ED',
			'Endo',
			'HH',
			'Hospice',
			'Super',
			'MICU',
			'ICU',
			'ICU',
			'NICU',
			'ICU',
			'PICU',
			'SICU',
			'ICU',
			'Imm',
			'L&D',
			'Legal',
			'LTAC',
			'LTC',
			'Maternal',
			'M/S',
			'Nursery',
			'Occupational',
			'Onc',
			'OR',
			'OR',
			'OR',
			'OR',
			'OR',
			'OR',
			'OR',
			'OR',
			'Ortho',
			'PACU',
			'Peds',
			'PICC',
			'Postpartum',
			'PCU',
			'Psych',
			'Rehab',
			'School',
			'Supervisor',
			'Tele',
			'W/C',
			'Acute Care',
			'Doctors Office',
			'Urgent Care',
			'Home Visits',
			'Pharmacy',
			'LTAC',
			'LTC',
			'Outpatient',
			'Rehab',
			'Pharmacy',
			'SNF',
			'RNFA'
			);



	private $yearsExperience = array(
		'New grad (2yrs exp required)',
		'0-1 Year (2yrs exp required)',
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

	public function getLicenseStates($key = null)
	{
		if (isset($this->licenseStates[$key])) {
			return $this->licenseStates[$key];
		}

		return $this->licenseStates;
	}

	public function getDaStates($key = null)
	{
		if (isset($this->daStates[$key])) {
			return $this->daStates[$key];
		}

		return $this->daStates;
	}

	public function getSpecialty($key = null)
	{
		if (isset($this->specialty[$key])) {
			return $this->specialty[$key];
		}

		return $this->specialty;
	}

	public function getSpecialtyShort($key = null)
	{
		if (isset($this->specialty_short[$key])) {
			return $this->specialty_short[$key];
		}

		return $this->specialty_short;
	}

	public function getDiscipline($key = null)
	{
		if (isset($this->discipline[$key])) {
			return $this->discipline[$key];
		}

		return $this->discipline;
	}

	public function getDisciplineShort($key = null)
	{
		if (isset($this->discipline_short[$key])) {
			return $this->discipline_short[$key];
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

	public function getDisciplines()
	{
		return isset($this->discipline) ? $this->discipline : false;
	}

	public function getSpecialties()
	{
		return isset($this->specialty) ? $this->specialty : false;
	}
}