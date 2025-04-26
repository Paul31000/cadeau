<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use IntlDateFormatter;

class DateFormatterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_fr', [$this, 'formatDateInFrench']),
        ];
    }

    public function formatDateInFrench(\DateTimeInterface $date, string $pattern = 'EEEE d MMMM Y'): string
    {
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            null,
            IntlDateFormatter::GREGORIAN,
            $pattern
        );

        return $formatter->format($date);
    }
}
