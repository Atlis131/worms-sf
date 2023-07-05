<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Emter E-Mail")
     * @Assert\Email()
     * @UniqueUser()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Enter Name")
     */
    public $name;

    public $password;
}
