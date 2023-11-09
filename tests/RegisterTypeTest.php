<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterTypeTest extends TestCase
{

    // TEST UNITAIRE POUR SAVOIR SI LE PASSWORD ET CONSTRAINT FONCTIONNE BIEN
    public function testValidPassword()
    {
        $validator = Validation::createValidator();
        $password = 'MyStrongPassword123!';

        $constraint = new Regex([
            'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[#?!@$%^&*-]).{8,}$/',
            'message' => 'Vous devez impérativement avoir 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux pour créer votre compte.',
        ]);

        $violations = $validator->validate($password, $constraint);

        $this->assertCount(0, $violations);
    }

    
    // TEST UNITAIRE POUR SAVOIR SI LE PASSWORD ET CONSTRAINT SONT INVALIDES
    public function testInvalidPassword()
    {
        $validator = Validation::createValidator();
        $password = 'invalidPassword';

        $constraint = new Regex([
            'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}$/',
            'message' => 'Vous devez impérativement avoir 8 caractères minimum, contenant des chiffres, lettres, majuscules, minuscules et des caractères spéciaux pour créer votre compte.',
        ]);

        $violations = $validator->validate($password, $constraint);

        $this->assertCount(1, $violations);
    }
}