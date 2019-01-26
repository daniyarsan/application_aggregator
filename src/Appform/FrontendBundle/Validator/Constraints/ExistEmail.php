<?php

namespace Appform\FrontendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExistEmail extends Constraint
{
    public $message = 'Email Already exists';
    
    public function validatedBy()
    {
        return 'exist_email';
    }
}
