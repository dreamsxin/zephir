<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Zephir\Console;

use Exception;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\HelpCommand;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Zephir\Console\Command\ListCommand;
use Zephir\Zephir;

final class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Zephir', Zephir::VERSION);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getHelp()
    {
        return Zephir::LOGO.parent::getHelp();
    }

    /**
     * {@inheritdoc}
     *
     * @return string The application version
     */
    public function getVersion()
    {
        $version = explode('-', parent::getVersion());

        if (isset($version[1]) && 0 === strpos($version[1], '$')) {
            return "{$version[0]}-source";
        }

        return implode('-', $version);
    }

    /**
     * {@inheritdoc}
     *
     * @return string The long application version
     */
    public function getLongVersion()
    {
        $version = explode('-', $this->getVersion());
        $commit = "({$version[1]})";

        return trim(
            sprintf(
                '%s <info>%s</info> by <comment>Andres Gutierrez</comment> and <comment>Serghei Iakovlev</comment> %s',
                $this->getName(),
                $version[0],
                $commit
            )
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Exception|Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (true === $input->hasParameterOption(['--dumpversion'], true)) {
            $output->writeln($this->getVersion());

            return 0;
        }

        try {
            // Makes ArgvInput::getFirstArgument() able to distinguish an option from an argument.
            $input->bind($this->getDefinition());
        } catch (ExceptionInterface $e) {
            // Errors must be ignored, full binding/validation happens later when the command is known.
        }

        $wantsHelp = false;
        $name = $this->getCommandName($input);

        if ($name && 'help' == strtolower($name) && 2 == $_SERVER['argc']) {
            $wantsHelp = true;
        } elseif (!$name && true === $input->hasParameterOption(['--help', '-h'], true)) {
            $wantsHelp = true;
        }

        if ($wantsHelp) {
            $command = $this->find('list');

            return $command->run(new ArrayInput(['command' => 'list']), $output);
        }

        try {
            return parent::doRun($input, $output);
        } catch (CommandNotFoundException $e) {
            fprintf(STDERR, $e->getMessage().PHP_EOL);

            return 1;
        } catch (RuntimeException $e) {
            fprintf(STDERR, $e->getMessage().PHP_EOL);

            return 1;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return Command[] An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        return [new HelpCommand(), new ListCommand()];
    }

    /**
     * {@inheritdoc}
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition(): InputDefinition
    {
        return new InputDefinition([
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption(
                'dumpversion',
                null,
                InputOption::VALUE_NONE,
                "Print the version of the compiler and don't do anything else"
            ),
            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Print this help message'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Print compiler version information and quit'),
        ]);
    }
}
