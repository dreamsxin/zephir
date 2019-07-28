<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Zephir\Statements;

use Zephir\CompilationContext;
use Zephir\Exception\CompilerException;

/**
 * BreakStatement.
 *
 * Break statement
 */
class BreakStatement extends StatementAbstract
{
    /**
     * @param CompilationContext $compilationContext
     *
     * @throws CompilerException
     */
    public function compile(CompilationContext $compilationContext)
    {
        if ($compilationContext->insideCycle || $compilationContext->insideSwitch) {
            $compilationContext->codePrinter->output('break;');
        } else {
            throw new CompilerException("Cannot use 'break' outside of a loop", $this->statement);
        }
    }
}
