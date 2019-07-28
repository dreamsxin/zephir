<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Zephir\Types;

use Zephir\Types;

/**
 * DoubleType.
 *
 * Defines methods of the built-in double type
 */
class DoubleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public $methodMap = [
        'abs' => 'abs',
        'tobinary' => 'decbin',
        'tohex' => 'dechex',
        'tooctal' => 'decoct',
        'pow' => 'pow',
        'sqrt' => 'sqrt',
        'exp' => 'exp',
        'sin' => 'sin',
        'cos' => 'cos',
        'tan' => 'tan',
        'asin' => 'asin',
        'acos' => 'acos',
        'atan' => 'atan',
        'log' => 'log',
    ];

    /**
     * {@inheritdoc}
     */
    public function getTypeName()
    {
        return Types::T_DOUBLE;
    }
}
