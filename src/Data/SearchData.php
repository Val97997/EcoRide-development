<?php
namespace App\Data;

use App\Entity\Carshare;
use DateInterval;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Form\FormTypeInterface;

class SearchData extends AbstractType{

    public DateTimeInterface $departure_date ;

    public string $departure_location = '';

    public string $arrival_location = '';

    public ?int $max;

    public ?bool $eco;

    public ?DateInterval $duration;

    public ?int $rating;
}

