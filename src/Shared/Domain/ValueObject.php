<?php

namespace Src\Shared\Domain;

abstract class ValueObject
{
    public function equals(ValueObject $other): bool
    {
        return $this->value() === $other->value();
    }

    abstract public function value(): mixed;
}
