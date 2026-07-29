<?php

namespace Src\Auth\Domain\Exceptions;

use Exception;

class InvalidCredentialsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Las credenciales proporcionadas no coinciden con nuestros registros.');
    }
}
