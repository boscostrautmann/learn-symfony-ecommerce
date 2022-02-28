<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtention extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount']),
        ];
    }

    public function amount($value, string $symbol = '€', string $decsep = ',', string $thousandsep = ' ')
    {
        $formatedValue = $value / 100;
        $formatedValue = number_format($formatedValue, 2, $decsep, $thousandsep);

        return $formatedValue.' '.$symbol;
    }
}
