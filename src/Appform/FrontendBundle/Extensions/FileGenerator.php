<?php

namespace Appform\FrontendBundle\Extensions;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FileGenerator
{
    const DOC_AUTHOR = 'HealthcareTravelerNetwork';
    const DOC_TITLE = 'Applicant Document';

    /**
     * @var array
     */
    private $params = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->twig = $this->container->get('twig');
    }

    public function getFileName($applicant)
    {
        $helper = $this->container->get('helper');
        $filename = "HCEN - {$helper->translateDisciplineShort($applicant->getPersonalInformation()->getDiscipline())}, ";
        if ($personalInfo->getDiscipline() == 5) {
            $filename .= "{$helper->translateSpecialty($personalInfo->getSpecialtyPrimary())}, ";
        }
        $filename .= "{$applicant->getLastName()}, {$applicant->getFirstName()} - {$randNum}";
        $filename = str_replace('/', '-', $filename);
    }
    
    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    public function generatePdf($data)
    {
        $path = $this->container->getParameter('resume_upload_dir') . '/' . $data['pdf'];
        $pdfGenerator = $this->container->get('knp_snappy.pdf');
        $html = $this->twig->render('@AppformFrontend/Default/pdf.html.twig', $data);
        $pdfGenerator->generateFromHtml($html, $path);
        return $path;
    }

    public function generateXls($data, $mapping)
    {
        $path = $this->container->getParameter('resume_upload_dir') . '/' . $data['xls'];
        $objPHPExcel = $this->container->get('phpexcel')->createPHPExcelObject();

        $objPHPExcel->setActiveSheetIndex(0);

        // Init document
        $objPHPExcel->getProperties()->setCreator(self::DOC_AUTHOR)
            ->setLastModifiedBy(self::DOC_AUTHOR)
            ->setTitle(self::DOC_TITLE)
            ->setSubject(self::DOC_TITLE);

        $excelBody = array_intersect_key($data, $mapping);
        /* set value for resume document */
        $excelBody['path'] = $excelBody['path'] ? 'Yes' : 'No';
        $dataToExport[] = $mapping;
        $dataToExport[] = $excelBody;
        $objPHPExcel->getActiveSheet()->fromArray($dataToExport);

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($path);

        return $path;
    }

}
