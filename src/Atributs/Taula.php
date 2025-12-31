<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Atributs;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Taula
{
    public function __construct(
        public readonly ?string $nom = null,
    ) {
    }
}
