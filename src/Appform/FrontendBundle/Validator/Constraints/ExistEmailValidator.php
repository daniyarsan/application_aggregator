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

    /**
     * @param Registry $doctrine
     */
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
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
        
        $em = $this->doctrine->getManager();
        return false;
        
        return true;
    }
}
