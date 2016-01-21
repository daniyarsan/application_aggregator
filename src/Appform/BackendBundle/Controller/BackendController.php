<?php

namespace Appform\BackendBundle\Controller;

use Appform\BackendBundle\Form\ApplicantType;
use Appform\FrontendBundle\Entity\Filter;
use Appform\FrontendBundle\Entity\PersonalInformation;
use Appform\FrontendBundle\Form\PersonalInformationType;
use Appform\FrontendBundle\Form\SearchType;
use DatePeriod;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BackendController extends Controller {

	public $limit = 150;

	public function indexAction( Request $request ) {
		return $this->redirect( 'users' );
	}

	public function usersAction( Request $request ) {

		$form = $this->createForm(new SearchType( $this->get( 'Helper' ), $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' ),  new PersonalInformation()));
		if ( $request->request->get( 'search' ) ) {
			$likeField = $request->request->get( 'search' );
			$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->findApplicantById( $likeField );
		} else {
			if ( $request->query->get( 'sort' ) && $request->query->get( 'direction' ) ) {
				$sort      = $request->query->get( 'sort' );
				$direction = $request->query->get( 'direction' );
			} else {
				$sort      = 'id';
				$direction = 'DESC';
			}
			$searchData = false;
			if ($request->request->get( 'filter' )) {
				$searchData = $request->request->get( 'appform_frontendbundle_search');
				$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->getUsersPerFilter( $searchData, $sort, $direction );
				$this->limit = count($applicant) ? count($applicant) : 1;  // Show all users after the filtering
			} else {
				$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->getUsersPerFilter( $searchData, $sort, $direction );
			}
		}
		$paginator = $this->get( 'knp_paginator' );
		$pagination = $paginator->paginate(
			$applicant,
			$this->get( 'request' )->query->get( 'page', 1 ),
			$this->limit
		);

		return $this->render( 'AppformBackendBundle:Backend:users.html.twig', array(
				'pagination' => $pagination,
				'form' => $form->createView(),
				'counter' => count($applicant),
				'originStats' => $applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->countReferers()));
	}

	public function userEditAction( $id, Request $request ) {
		$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
		if ( ! $applicant ) {
			throw new NotFoundHttpException( "Page not found" );
		}

		$applicantForm = $this->createForm( new ApplicantType( $this->get( 'Helper' ) ), $applicant );
		if ( $request->isMethod( 'POST' ) ) {
			$applicantForm->handleRequest( $request );

			if ( $applicantForm->isValid() ) {
				$em = $this->getDoctrine()->getManager();
				$em->persist( $applicant );
				$em->flush();
				return $this->redirect( $this->generateUrl( 'appform_backend_index' ) );
			}
		}

		return $this->render( '@AppformBackend/Backend/editUser.html.twig', array(
				'form' => $applicantForm->createView(),
				'applicant' => $applicant
			)
		);
	}

	public function userDeleteAction( $id ) {
		$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
		if ( ! $applicant ) {
			throw new NotFoundHttpException( "Page not found" );
		}

		$em = $this->getDoctrine()->getManager();
		$em->remove( $applicant );
		$em->flush();

		return $this->redirect( $this->generateUrl( 'appform_backend_index' ) );
	}

	public function downloadAction( $filename ) {
		$request = $this->get('request');
		$path = $this->get('kernel')->getRootDir(). "/../web/resume/";
		$content = file_get_contents($path.$filename);

		$response = new Response();
		//set headers
		$response->headers->set('Content-Type', 'mime/type');
		$response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

		$response->setContent($content);
		return $response;
	}

	public function userReportAction( Request $request ) {
		if ( $request->isXmlHttpRequest() ) {

			// Create new PHPExcel object
			$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
			// Set document properties
			$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
			            ->setLastModifiedBy( "HealthcareTravelerNetwork" )
			            ->setTitle( "Applicant Report" )
			            ->setSubject( "Applicant Document" );

			$fields = $this->getFields();
			$objPHPExcel = $this->setExcelHeader($fields, $objPHPExcel);

			$usersIds = $request->request->get( 'usersId' );
			if ( $usersIds ) {
				foreach ( $usersIds as $counter => $id ) {
					$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
					if ( ! $applicant ) {
						throw new EntityNotFoundException( "Page not found" );
					}
					$objPHPExcel = $this->prepareDataForExcel($fields, $applicant, $objPHPExcel, $counter );
				}
				$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
				$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/../reports/report.xls');

				return new JsonResponse(array('url'=>'/reports/report.xls'), 200);
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}

	public function prepareDataForExcel( $fields, $applicant, $objPHPExcel, $counter ) {
		$personalInfo = $applicant->getPersonalInformation();

		$helper = $this->get('helper');
		$counter +=2;

		// prepare alphabet counter
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}

		foreach ( $fields as $key => $value ) {
			$metodName = 'get' . ucfirst($value);
			if ( method_exists( $applicant, $metodName ) ) {
				$data = $applicant->$metodName();
				$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'm/d/Y H:i:s' ) : $data;
				$data = $data ? $data : '';
				if ( is_object( $data ) && get_class( $data ) == 'Appform\FrontendBundle\Entity\Document' ) {
					$data = $data->getPath() ? 'Yes' : 'No';
				}
			} else if ( method_exists( $personalInfo, $metodName ) ) {
					$data = $personalInfo->$metodName();
					$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( is_object( $data ) && get_class( $data ) == 'Document' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( $value == 'state' ) ? $helper->getStates( $data ) : $data;
					$data = ( $value == 'discipline' ) ? $helper->getDiscipline( $data ) : $data;
					$data = ( $value == 'specialtyPrimary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $value == 'specialtySecondary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $value == 'yearsLicenceSp' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $value == 'yearsLicenceSs' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $value == 'assignementTime' ) ? $helper->getAssTime( $data ) : $data;
					if (is_array($data)) {
						$data = ( $value == 'desiredAssignementState' ) ? implode(',', ($data)) : $data;
					}

					//$data = ( $value == 'licenseState' || $value == 'desiredAssignementState' ) ? implode( ',', $data ) : $data;
					if ( $value == 'isOnAssignement' || $value == 'isExperiencedTraveler' ) {
						$data = $data == true ? 'Yes' : 'No';
					}
			} else {
				$document = $applicant->getDocument();
				$data = $document->getPath() ?  'Yes' : 'No';
			}
			$data = $data ? $data : '';
			$data = is_array( $data ) ? '' : $data;

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue( $alphabet[ $key ] . (string)$counter, $data );
		}
		return $objPHPExcel;
	}

	public function userSendMessageAction( $id, Request $request ) {
		if ( $request->isXmlHttpRequest() ) {
			$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );

			if ( ! $applicant ) {
				throw new EntityNotFoundException;
			}
			$message = \Swift_Message::newInstance()
			                         ->setFrom( 'from@example.com' )
			                         ->setTo( 'daniyar.san@gmail.com' )
			                         ->addCc( 'moreinfo@healthcaretravelers.com' )
			                         ->setSubject( 'HCEN Request for More Info' )
			                         ->setBody( 'Please find new candidate Lead. HCEN Request for More Info' )
			                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPdf() ) )
			                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getXls() ) )
			                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPath() ) );

			if ( $applicant->getDocument()->getPath() ) {
				$message->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPath() ) );
			}

			try {
				$sentStatus = $this->get( 'mailer' )->send( $message );
			} catch ( Exception $e ) {
				throw new NotFoundHttpException();
			}

			if ( $sentStatus == 1 ) {
				return new JsonResponse( 'Your message has been sent successfully', 200 );
			} else {
				return new JsonResponse( 'Error', 500 );
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}

	public function fixAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->select('d');
		$qb->from('Appform\FrontendBundle\Entity\Document', 'd');
		$result = $qb->where($qb->expr()->like('d.xls', ':path'))
		   ->setParameter('path','%moreinfo%')
		   ->getQuery()
		   ->getResult();
		foreach ($result as $key => $doc) {
			$result[$key]->setXls(str_replace("/home/hctcom/public_html/moreinfo-app/src/Appform/FrontendBundle/Entity/../../../../web/resume/", "", $doc->getXls()));
			$result[$key]->setPdf(str_replace("/home/hctcom/public_html/moreinfo-app/src/Appform/FrontendBundle/Entity/../../../../web/resume/", "", $doc->getPdf()));
		}
		$em->flush();
		return new Response("done");
	}

	public function sendMessageAction( Request $request ) {
		if ( $request->isXmlHttpRequest() ) {
			if ( $request->isMethod( 'POST' ) ) {
				$usersIds   = $request->request->get( 'data' );
				$sentStatus = false;
				if ( $usersIds ) {
					foreach ( $usersIds as $id ) {
						$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );

						if ( ! $applicant ) {
							throw new EntityNotFoundException;
						}
						$message = \Swift_Message::newInstance()
						                         ->setFrom( 'from@example.com' )
						                         ->setTo( 'daniyar.san@gmail.com' )
						                         ->addCc( 'moreinfo@healthcaretravelers.com' )
						                         ->setSubject( 'HCEN Request for More Info' )
						                         ->setBody( 'Please find new candidate Lead. HCEN Request for More Info' )
						                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPdf() ) )
						                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getXls() ) )
						                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPath() ) );

						if ( $applicant->getDocument()->getPath() ) {
							$message->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getPath() ) );
						}

						try {
							$sentStatus = $this->get( 'mailer' )->send( $message );
						} catch ( Exception $e ) {
							throw new NotFoundHttpException();
						}
					}
				}
				if ( $sentStatus == 1 ) {
					return new JsonResponse( 'Your message has been sent successfully', 200 );
				} else {
					return new JsonResponse( 'Error', 500 );
				}
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}

	public function regenerateAction( Request $request ) {
		if ( $request->isXmlHttpRequest() ) {
			if ( $request->isMethod( 'POST' ) ) {
				$usersIds   = $request->request->get( 'data' );
				$sentStatus = false;
				if ( $usersIds ) {
					foreach ( $usersIds as $id ) {
						$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );

						if ( ! $applicant ) {
							throw new EntityNotFoundException;
						}
						$this->sendReport($applicant);
					}
				}
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}
	protected function sendReport( $applicant ) {

		$personalInfo = $applicant->getPersonalInformation();
		$helper       = $this->get( 'Helper' );

		/* Data Generation*/
		$formTitles1 = array( 'id' => 'Candidate #', 'firstName' => 'First Name', 'lastName' => "Last Name", "email" => "Email" );
		$formTitles2 = array();
		$form1       = $this->createForm( new ApplicantType( $this->get( 'Helper' ) ) );
		$form2       = $this->createForm( new PersonalInformationType( $this->get( 'Helper' ) ) );

		$children1   = $form1->all();
		$children2   = $form2->all();
		foreach ( $children1 as $child ) {
			$config = $child->getConfig();
			if ( $config->getOption( "label" ) != null ) {
				$formTitles1[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		foreach ( $children2 as $child ) {
			$config = $child->getConfig();
			if ( $config->getOption( "label" ) != null ) {
				$formTitles2[ $child->getName() ] = $config->getOption( "label" );
			}
		}
		$fields   = array_merge( $formTitles1, $formTitles2 );
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}
		/* Data Generation*/

		// Create new PHPExcel object
		$objPHPExcel = $this->get( 'phpexcel' )->createPHPExcelObject();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator( "HealthcareTravelerNetwork" )
		            ->setLastModifiedBy( "HealthcareTravelerNetwork" )
		            ->setTitle( "Applicant Data" )
		            ->setSubject( "Applicant Document" );

		/* Filling in excel document */
		$forPdf = array();

		foreach ( $fields as $key => $value ) {
			$metodName = 'get' . $key;
			if ( method_exists( $applicant, $metodName ) ) {
				$data = $applicant->$metodName();
				$data = $data ? $data : '';
				if ( is_object( $data ) && get_class( $data ) == 'Appform\FrontendBundle\Entity\Document' ) {
					$data = $data->getPath() ? 'Yes' : 'No';
				}
			} else {
				if ( method_exists( $personalInfo, $metodName ) ) {
					$data = $personalInfo->$metodName();
					$data = ( is_object( $data ) && get_class( $data ) == 'DateTime' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( is_object( $data ) && get_class( $data ) == 'Document' ) ? $data->format( 'F d,Y' ) : $data;
					$data = ( $key == 'state' ) ? $helper->getStates( $data ) : $data;
					$data = ( $key == 'discipline' ) ? $helper->getDiscipline( $data ) : $data;
					$data = ( $key == 'specialtyPrimary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $key == 'specialtySecondary' ) ? $helper->getSpecialty( $data ) : $data;
					$data = ( $key == 'yearsLicenceSp' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $key == 'yearsLicenceSs' ) ? $helper->getExpYears( $data ) : $data;
					$data = ( $key == 'assignementTime' ) ? $helper->getAssTime( $data ) : $data;
					$data = ( $key == 'licenseState' || $key == 'desiredAssignementState' ) ? implode( ',', $data ) : $data;
					if ( $key == 'isOnAssignement' || $key == 'isExperiencedTraveler' ) {
						$data = $data == true ? 'Yes' : 'No';
					}
				}
			}

			$data                  = $data ? $data : '';
			$data                  = is_array( $data ) ? '' : $data;
			$forPdf['candidateId'] = $applicant->getCandidateId();

			$forPdf[ $key ] = $data;

			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value )
			            ->setCellValue( $alphabet[ $key ] . '2', $data );
		}

		$this->get( 'knp_snappy.pdf' )->generateFromHtml(
			$this->renderView(
				'AppformFrontendBundle:Default:pdf.html.twig',
				$forPdf
			),$applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf());

		$objWriter = \PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
		$objWriter->save( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls());

		$message = \Swift_Message::newInstance()
		                         ->setFrom( 'from@example.com' )
		                         ->setTo( 'daniyar.san@gmail.com' )
		                         ->addCc( 'moreinfo@healthcaretravelers.com' )
		                         ->setSubject( 'HCEN Request for More Info' )
		                         ->setBody( 'Please find new candidate Lead. HCEN Request for More Info' )
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPdf() ) )
		                         ->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getXls() ) );

		if ( $applicant->getDocument()->getPath() ) {
			$message->attach( \Swift_Attachment::fromPath( $applicant->getDocument()->getUploadRootDir() . '/' . $applicant->getDocument()->getPath()) );
		}

		return $this->get( 'mailer' )->send( $message );
	}

	public function removeUsersAction( Request $request ) {
		if ( $request->isXmlHttpRequest() ) {
			$usersIds = $request->request->get( 'usersId' );
			if ( $usersIds ) {
				foreach ( $usersIds as $id ) {
					$applicant = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->find( $id );
					if ( ! $applicant ) {
						throw new EntityNotFoundException( "Page not found" );
					}

					$em = $this->getDoctrine()->getManager();
					$em->remove( $applicant );
					$em->flush();
				}

				return new JsonResponse( 'Your message has been sent successfully', 200 );
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}

	public function getRegisterUsersAction( Request $request ) {
		if ( $request->isXmlHttpRequest() && $request->isMethod( 'POST' ) ) {
			$reg   = array();
			$users = $this->getDoctrine()->getRepository( 'AppformFrontendBundle:Applicant' )->getLastMonth();
			foreach ( $users as $user ) {
				$format = new \DateTime( $user->getCreated()->format( 'Y-m-d' ) );
				$reg[]  = $format->format( 'MdD' );
			}

			$registerDays = array_count_values( $reg );

			$now = new \DateTime();

			$thirtyDaysAgo = $now->sub( new \DateInterval( "P30D" ) );

			$begin = new \DateTime( $thirtyDaysAgo->format( 'Y-m-d' ) );
			$end   = new \DateTime();
			$end   = $end->modify( '+1 day' );

			$interval  = new \DateInterval( 'P1D' );
			$daterange = new DatePeriod( $begin, $interval, $end );

			$daysInMonth = array();
			foreach ( $daterange as $date ) {
				$daysInMonth[ $date->format( "MdD" ) ] = 0;
			}

			$result = array_merge( $daysInMonth, $registerDays );

			return new JsonResponse( $result );
		} else {
			throw new BadRequestHttpException( 'This is bad request' );
		}
	}

	public function getFields() {
		$em      = $this->getDoctrine()->getManager();
		$fields  = $em->getClassMetadata( 'AppformFrontendBundle:Applicant' )->getFieldNames();
		$fields1 = $em->getClassMetadata( 'AppformFrontendBundle:PersonalInformation' )->getFieldNames();

		return array(
			"id",
			"candidateId",
			"firstName",
			"lastName",
			"email",
			"created",
			"phone",
			"state",
			"discipline",
			"licenseState",
			"specialtyPrimary",
			"yearsLicenceSp",
			"specialtySecondary",
			"yearsLicenceSs",
			"desiredAssignementState",
			"isExperiencedTraveler",
			"isOnAssignement",
			"assignementTime",
			"question",
			"completion",
			"resume",
			"appReferer",
			'ip'
		);

		return array_merge( $fields, $fields1 );
	}

	public function setExcelHeader( $fields, $objPHPExcel ){
		// prepare alphabet counter
		$alphabet = array();
		$alphas   = range( 'A', 'Z' );
		$i        = 0;
		foreach ( $fields as $key => $value ) {
			$alphabet[ $key ] = $alphas[ $i ];
			$i ++;
		}
		foreach ( $fields as $key => $value ) {
			$objPHPExcel->setActiveSheetIndex( 0 )
			            ->setCellValue( $alphabet[ $key ] . '1', $value );
		}
		return $objPHPExcel;
	}

	public function saveFilterAction( Request $request )
	{
		if ( $request->isXmlHttpRequest() ) {
			$usersIds = $request->request->get( 'data' );
			if ( $usersIds ) {
				$em = $this->getDoctrine()->getEntityManager();
				$filter = new Filter();
				$filter->setUserIds($usersIds);
				$em->persist($filter);
				$em->flush();
				return new JsonResponse( 'Table have been saved successfully', 200 );
			}
		} else {
			throw new BadRequestHttpException( 'This is not ajax' );
		}
	}
}
