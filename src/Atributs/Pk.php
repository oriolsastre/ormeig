<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Pk
{
    public function __construct() {}
}
