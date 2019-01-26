<?php

namespace Appform\FrontendBundle\Validator\Constraints;

use Doctrine\Bundle\DoctrineBundle\Registry;
use KeenSteps\EcommerceBundle\Entity\Category;
use KeenSteps\EcommerceBundle\Entity\Product;
use KeenSteps\EcommerceBundle\Entity\StaticPage;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 */
class UniqueUrlValidator extends ConstraintValidator
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param Registry $doctrine
     * @param Router $router
     */
    public function __construct($doctrine, $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
    }

    public function validate($value, Constraint $constraint)
    {
        if (is_null($value)) {
            return;
        }

        try {
            $routeParams = $this->router->match($value);
            if ($routeParams['_route'] != 'url_show') {
                $this->context->addViolation($constraint->message);
                return;
            }
        } catch (\Exception $ex) {
        }

        $changedEntity = $this->context->getRoot()->getData();

        $em = $this->doctrine->getManager()->getRepository('EcommerceBundle:Category');
        $entities = $em->findByUrl($value);
        if ($entities) {
            foreach ($entities as $entity) {
                if ($changedEntity instanceof Category && $entity->getId() == $changedEntity->getId()) {
                    continue;
                }
                $this->context->addViolation($constraint->message);
                return;
            }
        }

        $em = $this->doctrine->getManager()->getRepository('EcommerceBundle:Product');
        $entities = $em->findByUrl($value);
        if ($entities) {
            foreach ($entities as $entity) {
                if ($changedEntity instanceof Product && $entity->getId() == $changedEntity->getId()) {
                    continue;
                }
                $this->context->addViolation($constraint->message);
                return;
            }
        }

        $em = $this->doctrine->getManager()->getRepository('EcommerceBundle:StaticPage');
        $entities = $em->findByUrl($value);
        if ($entities) {
            foreach ($entities as $entity) {
                if ($changedEntity instanceof StaticPage && $entity->getId() == $changedEntity->getId()) {
                    continue;
                }
                $this->context->addViolation($constraint->message);
                return;
            }
        }
    }
}
