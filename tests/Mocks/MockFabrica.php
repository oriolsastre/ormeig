<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Tests\Mocks;

use PHPUnit\Framework\TestCase;
use Sastreo\Ormeig\Enums\Driver;
use Sastreo\Ormeig\Gestor;
use Sastreo\Ormeig\Interfaces\Model;
use Sastreo\Ormeig\Ormeig;
use Sastreo\Ormeig\Tests\Models\TestModelPk;

class MockFabrica extends TestCase
{
    public function __construct(string $name = 'Test')
    {
        parent::__construct($name);
    }

    public function mockOrmeig(): Ormeig
    {
        $ormeigMock = $this->createMock(Ormeig::class);
        $ormeigMock->method('getDbcnx')->willReturn($this->pdoMock());
        $ormeigMock->expects($this->any())->method('getGestor')->willReturnCallback(function ($model) use ($ormeigMock) {
            return new Gestor($ormeigMock, $model);
        });

        return $ormeigMock;
    }

    public function realOrmeig(): Ormeig
    {
        return new Ormeig(
            driver: Driver::SQLITE,
            dbname: __DIR__.'/../Seed/test.db',
        );
    }

    /**
     * @param class-string<Model> $model
     *
     * @return Gestor|\PHPUnit\Framework\MockObject\MockObject
     */
    public function mockGestor(string $model = TestModelPk::class): Gestor
    {
        $gestorMock = $this->createMock(Gestor::class);
        $gestorMock->method('getModel')->willReturn($model);

        return $gestorMock;
    }

    private function pdoMock(): \PDO
    {
        return $this->createMock(\PDO::class);
    }
}
