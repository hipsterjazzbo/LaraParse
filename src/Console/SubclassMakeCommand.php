<?php

namespace LaraParse\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class SubclassMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'parse:subclass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new parse subclass';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Parse Subclass';

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        $parseClass = $this->option('parse-class') ?: $this->argument('name');

        return str_replace('{{parseClass}}', $parseClass, $stub);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/subclass.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\ParseClasses';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['parse-class', null, InputOption::VALUE_OPTIONAL, 'Manually specify the parse class name.'],
        ];
    }
}