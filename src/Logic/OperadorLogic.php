<?php

declare(strict_types=1);

namespace Sastreo\Ormeig\Logic;

use ArrayObject;
use InvalidArgumentException;
use Sastreo\Ormeig\Sql\Condicio;
use Sastreo\Ormeig\Interfaces\OperadorLogic as OperadorLogicInterface;

/**
 * @extends ArrayObject<int|string, OperadorLogicInterface|Condicio>
 */
abstract class OperadorLogic extends ArrayObject implements OperadorLogicInterface
{
    /**
     * @param OperadorLogicInterface|Condicio $condicions
     * @throws InvalidArgumentException
     */
    public function __construct(...$condicions)
    {
        foreach ($condicions as $condicio) {
            if (!$condicio instanceof Condicio && !$condicio instanceof OperadorLogicInterface) {
                throw new InvalidArgumentException("No és condició o operador lògic.");
            }
        }

        parent::__construct($condicions);
    }
}
