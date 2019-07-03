<?php

namespace Appform\FrontendBundle\Extensions;

use Appform\FrontendBundle\Entity\Applicant;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FieldManager
{
    /**
     * @var ContainerInterface
     */
    private $container;
    private $manager;
    private $helper;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, $manager)
    {
        $this->container = $container;
        $this->manager = $manager;
        $this->helper = $this->container->get('helper');

    }

    public function transform($entity)
    {
        $result = [];

        /* Get Root entity */
        $rootEntity = $this->manager->getClassMetadata(get_class($entity));

        /* Fetch all fields from root entity */
        if (!empty($rootEntity->fieldMappings)) {
            $result = $this->getFieldsValuesFromMapping($rootEntity->fieldMappings, $entity);
        }

        /* Find node and get all fields from child node */
        if (!empty($rootEntity->associationMappings)) {
            foreach ($rootEntity->associationMappings as $assocItem) {
                $childEntityName = 'get' . ucfirst($assocItem[ 'fieldName' ]);
                $childEntity = $this->manager->getClassMetadata(get_class($entity->{$childEntityName}()));
                if (!empty($childEntity->fieldMappings)) {
                    $result = array_merge($result, $this->getFieldsValuesFromMapping($childEntity->fieldMappings, $entity->{$childEntityName}()));
                }
            }
        }

        return $result;
    }

    /**
     * Core processing of entity data transformation
     *
    */
    private function getFieldsValuesFromMapping($fieldMappings, $entity)
    {
        $data = [];

        foreach ($fieldMappings as $fieldItem) {
            /* Hydrate data to array */
            $dataItem[ 'field' ] = $fieldItem[ 'fieldName' ];
            $dataItem[ 'name' ] = ucfirst(preg_replace('/(?<!\ )[A-Z]/', ' $0', $fieldItem[ 'fieldName' ]));
            /* Get Data from entity using getters */
            $getMethod = 'get' . ucfirst($fieldItem[ 'fieldName' ]);
            $dataItem[ 'value' ] = $entity->{$getMethod}();

            /* Get Interpretation of Applicant values from database */
            if (method_exists($this->helper, 'fetch' . ucfirst($fieldItem[ 'fieldName' ])) && $dataItem[ 'value' ] !== NULL) {
                $helperMethodName = 'fetch' . ucfirst($fieldItem[ 'fieldName' ]);
                $dataItem[ 'value' ] = $this->helper->{$helperMethodName}($dataItem[ 'value' ]);
            }
            switch ($fieldItem[ 'type' ]) {
                case 'datetime':
                    $dataItem[ 'value' ] = $dataItem[ 'value' ] ? $dataItem[ 'value' ]->format('F d,Y') : null;
                    break;
                case 'date':
                    $dataItem[ 'value' ] = $dataItem[ 'value' ] ? $dataItem[ 'value' ]->format('F d,Y') : null;
                    break;
                case 'boolean':
                    $dataItem[ 'value' ] = $dataItem[ 'value' ] ? 'Yes' : 'No';
                    break;
            }
            $data[] = $dataItem;
        }
        return $data;
    }

    public function getDataForExport($entity)
    {
        $export = [];
        $data = $this->transform($entity);
        foreach ($data as $item) {
            $export[$item['field']] = $item['value'];
        }
        return $export;
    }

    public function getFieldsForExport()
    {
        return [
            'candidateId' => 'Candidate #',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'state' => 'State',
            'discipline' => 'Discipline',
            'licenseState' => 'License State',
            'specialtyPrimary' => 'Primary Specialty',
            'yearsLicenceSp' => 'Years of Experience',
            'specialtySecondary' => 'Secondary Specialty',
            'yearsLicenceSs' => 'Years of Experience',
            'desiredAssignementState' => 'Assignment State',
            'isExperiencedTraveler' => 'Has Experience',
            'assignementTime' => 'Assignment Time',
            'isOnAssignement' => 'Is on assignment',
            'completion' => '(If Yes) Completion Date',
            'path' => 'Has Resume'];
    }
}