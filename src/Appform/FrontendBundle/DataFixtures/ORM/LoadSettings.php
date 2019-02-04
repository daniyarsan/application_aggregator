<?php

namespace Appform\FrontendBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;

class LoadDisciplines implements Fixtures
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $manager->flush();
    }

    protected function getDisciplines()
    {
        return array(
            'Physician Assistant',
            'Nurse Practitioner',
            'Certified Nurse Mid-Wife',
            'Clinical Nurse Specialist',
            'Certified Registered Nurse Anesthetist',
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
            'Pharmacist-Retail'
        );
    }

    protected function getSpecialties()
    {
        return array(
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
            'OR-RN First Assistant',
            'ICU-Cardiac Unit'
        );
    }
}