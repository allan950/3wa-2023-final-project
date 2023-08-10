<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintNonHtmlTagsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\ConstraintNonHtmlTags $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $forbidden = array('<script .*?>.*?<\/script>', '<script>.*?<\/script>', 
            '<a .*?>.*?<\/a>', '<a>.*?<\/a>',
            '<head .*?>.*?<\/head>', '<head>.*?<\/head>',
            '<body .*?>.*?<\/body>', '<body>.*?<\/body>',
            '<iframe .*?>.*?<\/iframe>', '<iframe>.*?<\/iframe>',
            '<link .*?>'
        );
        
        foreach($forbidden as $val) {
            if (preg_match("/^$val+$/", $value, $matches) == 1) {
                $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
                break;
            }
        }
    }
}
