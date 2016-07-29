<?php

namespace Appform\BackendBundle\Controller\Stats;

use Appform\BackendBundle\Form\InvoicingSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Appform\BackendBundle\Entity\Stats\Invoicing;
use Symfony\Component\HttpFoundation\Request;

/**
 * Stats\Invoicing controller.
 *
 * @Route("/invoicing")
 */
class InvoicingController extends Controller
{

    /**
     * Lists all Stats\Invoicing entities.
     *
     * @Route("/", name="stats_invoicing")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

	    $queryBuilder = $em->getRepository('AppformBackendBundle:Stats\Invoicing')->createQueryBuilder('i');

	    $searchForm = $this->createSearchForm();
	    $searchForm->handleRequest($request);

	    if ($searchForm->isSubmitted()) {
		    $data = $searchForm->getData();
		    var_dump($data);
	    }

        return array(
            'entities' => $queryBuilder,
	        'search_form' => $searchForm->createView()
        );
    }

    /**
     * Finds and displays a Stats\Invoicing entity.
     *
     * @Route("/{id}", name="stats_invoicing_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppformBackendBundle:Stats\Invoicing')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stats\Invoicing entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

    /**
     * Creates a form to search an Order.
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm()
    {
        $form = $this->createForm(
            new InvoicingSearchType(),
            null,
            array(
                'action' => $this->generateUrl('stats_invoicing'),
                'method' => 'GET',
            )
        );
        return $form;
    }
}
