<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VideoUrlsValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof VideoUrls) {
            throw new UnexpectedTypeException($constraint, VideoUrls::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        // Check if the string starts with "https://"
        if (strpos($value, 'https://') !== 0) {
            $this->context->buildViolation('La première URL de la vidéo doit commencer par "https://".')
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;  // Stop validation if any URL does not match
        }

         // Check if the string contains "http://" (to block URLs starting with "http://")
        if (strpos($value, 'http://') !== false) {
            $this->context->buildViolation('Toutes les URLs de la vidéo doivent commencer par "https://".')
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;  // Stop validation if any URL does not match
        }
    }
}