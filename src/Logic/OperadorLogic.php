<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Logic;

use Sastreo\Ormeig\Interfaces\OperadorLogic as OperadorLogicInterface;
use Sastreo\Ormeig\Sql\Condicio;

/**
 * @extends \ArrayObject<int|string, OperadorLogicInterface|Condicio>
 */
abstract class OperadorLogic extends \ArrayObject implements OperadorLogicInterface
{
    /**
     * @param OperadorLogicInterface|Condicio $condicions
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(...$condicions)
    {
        foreach ($condicions as $condicio) {
            if (!$condicio instanceof Condicio && !$condicio instanceof OperadorLogicInterface) {
                throw new \InvalidArgumentException('No és condició o operador lògic.');
            }
        }

        parent::__construct($condicions);
    }
}
