<?php
namespace App\Data;

use Symfony\Component\Form\AbstractType;

class FindUserData extends AbstractType{

    public ?string $pseudo ;

    public int $id;
}