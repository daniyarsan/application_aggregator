<?php

namespace Appform\FrontendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ExistEmailValidator extends ConstraintValidator
{
    /**
     * @var Registry
     */
    private $doctrine;
    private $repository;

    /**
     * @param Registry $doctrine
     */
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getManager()->getRepository('AppformFrontendBundle:Applicant');
    }

    /**
     * @param string $value
     * @param Constraint $constraint
     * @return bool
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return false;
        }

        if ($this->repository->findOneBy(array('email' => $value))) {
            $this->context->addViolation($constraint->message);
            return;
        }

        return true;
    }

}
