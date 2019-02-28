<?php

namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class Helper
{
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
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
    private $assignementTime = array(
        'ASAP',
        '1-3 Months',
        '3-6 Months',
        'Undecided');
    private $yearsExperience = array(
        'New grad (2yrs exp required)',
        '0-1 Year (2yrs exp required)',
        '1-3 Years',
        '3-5 Years',
        '5-10 Years',
        '10-15 Years',
        '20+ Years');
    private $statesShort = array(
        'AL' => 'AL',
        'AK' => 'AK',
        'AZ' => 'AZ',
        'AR' => 'AR',
        'CA' => 'CA',
        'CO' => 'CO',
        'CT' => 'CT',
        'DE' => 'DE',
        'DC' => 'DC',
        'FL' => 'FL',
        'GA' => 'GA',
        'HI' => 'HI',
        'ID' => 'ID',
        'IL' => 'IL',
        'IN' => 'IN',
        'IA' => 'IA',
        'KS' => 'KS',
        'KY' => 'KY',
        'LA' => 'LA',
        'ME' => 'ME',
        'MH' => 'MH',
        'MD' => 'MD',
        'MA' => 'MA',
        'MI' => 'MI',
        'MN' => 'MN',
        'MS' => 'MS',
        'MO' => 'MO',
        'MT' => 'MT',
        'NE' => 'NE',
        'NV' => 'NV',
        'NH' => 'NH',
        'NJ' => 'NJ',
        'NM' => 'NM',
        'NY' => 'NY',
        'NC' => 'NC',
        'ND' => 'ND',
        'OH' => 'OH',
        'OK' => 'OK',
        'OR' => 'OR',
        'PA' => 'PA',
        'RI' => 'RI',
        'SC' => 'SC',
        'SD' => 'SD',
        'TN' => 'TN',
        'TX' => 'TX',
        'UT' => 'UT',
        'VT' => 'VT',
        'VI' => 'VI',
        'VA' => 'VA',
        'WA' => 'WA',
        'WV' => 'WV',
        'WI' => 'WI',
        'WY' => 'WY');


    public function getBoolean($key = null)
    {
        if (isset($this->boolean[ $key ])) {
            return $this->boolean[ $key ];
        }

        return $this->boolean;
    }
    public function getStates($key = null)
    {
        if (isset($this->states[ $key ])) {
            return $this->states[ $key ];
        }

        return $this->states;
    }
    public function getAssTime($key = null)
    {
        if (isset($this->assignementTime[ $key ])) {
            return $this->assignementTime[ $key ];
        }

        return $this->assignementTime;
    }
    public function getExpYears($key = null)
    {
        if (isset($this->yearsExperience[ $key ])) {
            return $this->yearsExperience[ $key ];
        }

        return $this->yearsExperience;
    }
    public function getStatesShort($key = null)
    {
        if (isset($this->statesShort[ $key ])) {
            return $this->statesShort[ $key ];
        }

        return $this->statesShort;
    }

    public function translateSpecialty($id)
    {
        $spec = $this->em->getRepository('AppformFrontendBundle:Specialty')->find($id);
        if ($spec) {
            return $spec->getName();
        }
        return false;
    }

    public function translateSpecialtyShort($id)
    {
        $spec = $this->em->getRepository('AppformFrontendBundle:Specialty')->find($id);
        if ($spec) {
            return $spec->getShort();
        }
        return false;
    }

    public function translateDiscipline($id)
    {
        $discipline = $this->em->getRepository('AppformFrontendBundle:Discipline')->find($id);
        if ($discipline) {
            return $discipline->getName();
        }
        return false;
    }

    public function translateDisciplineShort($id)
    {
        $discipline = $this->em->getRepository('AppformFrontendBundle:Discipline')->find($id);
        if ($discipline) {
            return $discipline->getShort();
        }
        return false;
    }


    /**
     * Fetchers for FieldManager
    */

    public function fetchDiscipline($value)
    {
        $discipline = $this->em->getRepository('AppformFrontendBundle:Discipline')->find($value);
        return $discipline->getName();
    }
    public function fetchState($value)
    {
        return $this->states[ $value ];
    }
    public function fetchSpecialtyPrimary($value)
    {
        $specialty = $this->em->getRepository('AppformFrontendBundle:Specialty')->find($value);
        return $specialty->getName();
    }
    public function fetchSpecialtySecondary($value)
    {
        $specialty = $this->em->getRepository('AppformFrontendBundle:Specialty')->find($value);
        return $specialty->getName();
    }
    public function fetchYearsLicenceSp($value)
    {
        return $this->yearsExperience[ $value ];
    }

    public function fetchYearsLicenceSs($value)
    {
        return $this->yearsExperience[ $value ];
    }
    public function fetchDesiredAssignementState($value)
    {
        return implode(', ', $value);
    }
    public function fetchLicenseState($value)
    {
        return implode(', ', $value);
    }
    public function fetchAssignementTime($value)
    {
        return $this->assignementTime[$value];
    }
}