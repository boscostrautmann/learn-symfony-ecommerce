<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentimesTransformer implements DataTransformerInterface
{
    public function transform(mixed $value)
    {
        if (null === $value) {
            return;
        }

        return $value / 100;
    }

    public function reverseTransform(mixed $value)
    {
        if (null === $value) {
            return;
        }

        return $value * 100;
    }
}
