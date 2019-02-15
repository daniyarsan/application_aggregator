<?php

namespace Appform\FrontendBundle\Extensions;

use Doctrine\ORM\EntityManager;

class Helper
{
    private $em;
    private $request;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getRequest()
    {
        return $this->request;
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
    private $daStates = array(
        '0' => 'Select Destination State',
        'All States' => 'All States',
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
        'WY' => 'WY'
    );
    private $yearsExperience = array(
        'New grad (2yrs exp required)',
        '0-1 Year (2yrs exp required)',
        '1-3 Years',
        '3-5 Years',
        '5-10 Years',
        '10-15 Years',
        '20+ Years');

    private $assignementTime = array(
        'ASAP',
        '1-3 Months',
        '3-6 Months',
        'Undecided');

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
    public function getDaStates($key = null)
    {
        if (isset($this->daStates[ $key ])) {
            return $this->daStates[ $key ];
        }

        return $this->daStates;
    }
    public function getLicenseStates($key = null)
    {
        if (isset($this->licenseStates[ $key ])) {
            return $this->licenseStates[ $key ];
        }

        return $this->licenseStates;
    }



    public function getSpecialty($key = null)
    {
        if (isset($this->specialty[ $key ])) {
            return $this->specialty[ $key ];
        }

        return $this->specialty;
    }

    public function getSpecialtyShort($key = null)
    {
        if (isset($this->specialty_short[ $key ])) {
            return $this->specialty_short[ $key ];
        }

        return $this->specialty_short;
    }

    public function getDiscipline($key = null)
    {
        if (isset($this->discipline[ $key ])) {
            return $this->discipline[ $key ];
        }

        return $this->discipline;
    }

    public function getDisciplineShort($key = null)
    {
        if (isset($this->discipline_short[ $key ])) {
            return $this->discipline_short[ $key ];
        }

        return $this->discipline;
    }


    public function getDisciplines()
    {
        return isset($this->discipline) ? $this->discipline : false;
    }

    public function getSpecialties()
    {
        return isset($this->specialty) ? $this->specialty : false;
    }


    public function fetchDiscipline($value)
    {
        $discipline = $this->em->getRepository('AppformFrontendBundle:Discipline')->find($value);
        return $discipline->getShort();
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

/*    public function asdf()
    {
        if (method_exists($personalInfo, $metodName)) {
            $data = $personalInfo->$metodName();
            $data = (is_object($data) && get_class($data) == 'DateTime') ? $data->format('F d,Y') : $data;
            $data = (is_object($data) && get_class($data) == 'Document') ? $data->format('F d,Y') : $data;
            $data = ($key == 'discipline') ? $helper->getDiscipline($data) : $data;
            $data = ($key == 'specialtyPrimary') ? $helper->getSpecialty($data) : $data;
            $data = ($key == 'specialtySecondary') ? $helper->getSpecialty($data) : $data;
            $data = ($key == 'yearsLicenceSp') ? $helper->getExpYears($data) : $data;
            $data = ($key == 'yearsLicenceSs') ? $helper->getExpYears($data) : $data;
            $data = ($key == 'assignementTime') ? $helper->getAssTime($data) : $data;
            $data = ($key == 'licenseState' || $key == 'desiredAssignementState') ? implode(',', $data) : $data;
            if ($key == 'isOnAssignement' || $key == 'isExperiencedTraveler') {
                $data = $data == true ? 'Yes' : 'No';
            }
    }*/
}