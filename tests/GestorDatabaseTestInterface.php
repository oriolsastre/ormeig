<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests;

interface GestorDatabaseTestInterface
{
    public function testTrobaTots(): void;

    public function testTrobaPerId(): void;

    public function testCrea(): void;

    public function testElimina(): void;

    public function testEliminaNoExistent(): void;
}
