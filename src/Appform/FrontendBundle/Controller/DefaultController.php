<?php

namespace Appform\FrontendBundle\Controller;

use Appform\FrontendBundle\Entity\Applicant;
use Appform\FrontendBundle\Entity\AppUser;
use Appform\FrontendBundle\Entity\PersonalInformation;
use Appform\FrontendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Form\AppUserType;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
	public $xlsFile;
	public $pdfFile;


	public function indexAction(Request $request)
	{
		$resumeDir = '../web/resume/';
		$applicant = new Applicant();
		$personalInfo = new PersonalInformation();

		$applicant->setPersonalInformation($personalInfo);
		$form = $this->createForm(new ApplicantType($this->get('Helper'), $applicant));

		$form->handleRequest($request);
		if ($form->isValid()) {
			$applicant = $form->getData();
			$personalInfo = $applicant->getPersonalInformation();
			$personalInfo->setApplicant($applicant);

			$resume = $personalInfo->getResume();

			$personalInfo->getResume()->move($resumeDir, $applicant->getFirstName() . '_' . $applicant->getLastName().'.'. $resume->getClientOriginalExtension() );

			$em = $this->getDoctrine()->getManager();
			$em->persist($personalInfo);
			$em->persist($applicant);
			$em->flush();

			$session = $this->get('session');
			$session->getFlashBag()->add('success', 'Your message has been sent successfully.');

			/* TODO Send report */
			$this->sendReport($form);
			/* end send report */

			return $this->redirect($this->generateUrl('appform_frontend_homepage'));
		}
		$data = array(
			'form' => $form->createView()
		);
		return $this->render('AppformFrontendBundle:Default:index.html.twig', $data);
	}

	public function generateAction()
	{
		/*Get all values in the form to $fields variable*/
		$formTitles1 = array('id' => 'Candidate #');
		$formTitles2 = array();
		$form1 = $this->createForm(new ApplicantType($this->get('Helper')));
		$form2 = $this->createForm(new PersonalInformationType($this->get('Helper')));
		$children1 = $form1->all();
		$children2 = $form2->all();

		foreach ($children1 as $child) {
			$config = $child->getConfig();
			if ($config->getOption("label") != null) {
				$formTitles1[$child->getName()] = $config->getOption("label");
			}
		}
		foreach ($children2 as $child) {
			$config = $child->getConfig();
			if ($config->getOption("label") != null) {
				$formTitles2[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		$fields = array_merge($formTitles1, $formTitles2);
		$alphabet = array();
		$alphas = range('A', 'Z');
		$i = 0;
		foreach ($fields as $key => $value) {
			$alphabet[$key] = $alphas[$i];
			$i++;
		}

		// Create new PHPExcel object
		$objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("HealthcareTravelerNetwork")
		            ->setLastModifiedBy("HealthcareTravelerNetwork")
		            ->setTitle("Applicant Data")
		            ->setSubject("Applicant Document");

		/* Filling in excel document */

		foreach ($fields as $key => $value) {
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($alphabet[$key] . '1', $value)
			            ->setCellValue($alphabet[$key] . '2','' );
		}


		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($this->get('kernel')->getRootDir() . '/../web/resume/file.xls');
		return $this->render('AppformFrontendBundle:Default:pdf.html.twig');
	}

	protected function sendReport(Form $form)
	{
		$applicant = $form->getData();
		$personalInfo = $applicant->getPersonalInformation();
		$helper = $this->get('Helper');

		/* File paths */
		$this->xlsFile = $this->get('kernel')->getRootDir() . '/../web/resume/'. $applicant->getFirstName() . '_' . $applicant->getLastName() .'.xls';
		$this->pdfFile = $this->get('kernel')->getRootDir() . '/../web/resume/'. $applicant->getFirstName() . '_' . $applicant->getLastName() .'.pdf';
		/* end File paths */

		/* Data Generation*/
		$formTitles1 = array('id' => 'Candidate #');
		$formTitles2 = array();
		$form1 = $this->createForm(new ApplicantType($this->get('Helper')));
		$form2 = $this->createForm(new PersonalInformationType($this->get('Helper')));
		$children1 = $form1->all();
		$children2 = $form2->all();
		foreach ($children1 as $child) {
			$config = $child->getConfig();
			if ($config->getOption("label") != null) {
				$formTitles1[$child->getName()] = $config->getOption("label");
			}
		}
		foreach ($children2 as $child) {
			$config = $child->getConfig();
			if ($config->getOption("label") != null) {
				$formTitles2[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		$fields = array_merge($formTitles1, $formTitles2);
		$alphabet = array();
		$alphas = range('A', 'Z');
		$i = 0;
		foreach ($fields as $key => $value) {
			$alphabet[$key] = $alphas[$i];
			$i++;
		}
		/* Data Generation*/

		// Create new PHPExcel object
		$objPHPExcel = $this->get('phpexcel')->createPHPExcelObject();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("HealthcareTravelerNetwork")
		            ->setLastModifiedBy("HealthcareTravelerNetwork")
		            ->setTitle("Applicant Data")
		            ->setSubject("Applicant Document");

		/* Filling in excel document */
		$forPdf = array();

		foreach ($fields as $key => $value) {
			$metodName = 'get'. $key;
			if (method_exists($applicant, $metodName)) {
				$data = $applicant->$metodName();
				$data = $data ? $data : '';
			}
			else  {
				if (method_exists($personalInfo, $metodName)) {
					$data = $personalInfo->$metodName();
						$data = (is_object($data) && get_class($data) == 'DateTime') ? $data->format('Y-m-d H:i:s') : $data;
						$data = ($key == 'state') ? $helper->getStates($data) : $data;
						$data = ($key == 'discipline') ? $helper->getDiscipline($data) : $data;
						$data = ($key == 'specialtyPrimary') ? $helper->getSpecialty($data) : $data;
						$data = ($key == 'specialtySecondary') ? $helper->getSpecialty($data) : $data;
						if ($key == 'isOnAssignement' || $key == 'isExperiencedTraveler') {
							$data = $data == true ? 'yes' : 'no';
						}
				}
			}
			$data = $data ? $data : '';
			$forPdf[$key] = $data;
			$objPHPExcel->setActiveSheetIndex(0)
			            ->setCellValue($alphabet[$key] . '1', $value)
			            ->setCellValue($alphabet[$key] . '2', $data);
		}

		$this->get('knp_snappy.pdf')->generateFromHtml(
			$this->renderView(
				'AppformFrontendBundle:Default:pdf.html.twig',
				$forPdf
			),
			$this->pdfFile
		);

				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				$objWriter->save($this->xlsFile);

				$message = \Swift_Message::newInstance()
										->setFrom('from@example.com')
										->setTo('daniyar.san@gmail.com')
										->setSubject('New Lead')
										->setBody('Please find new candidate Lead')
										->attach(\Swift_Attachment::fromPath($this->xlsFile));

				$this->get('mailer')->send($message);
	}
}
