<?php

namespace LaraParse\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'parse:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new parse repository';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Parse Repository';

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
        switch ($this->option('which')) {
            case 'contract':
                return __DIR__.'/stubs/repository_contract.stub';

            case 'implementation':
                return __DIR__.'/stubs/repository_implementation.stub';

            default:
                $this->error("Valid values for 'which': contract, implementation");
                return false;
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        switch ($this->option('which')) {
            case 'contract':
                return $rootNamespace.'\Repositories\Contracts';

            case 'implementation':
                return $rootNamespace.'\Repositories';

            default:
                $this->error("Valid values for 'which': contract, implementation");
                return false;
        }
    }

    /**
     * Override the parent `fire` method so we can create our implementation as well
     */
    public function fire()
    {
        parent::fire();

        if ($this->option('which') == 'contract') {
            $this->call('parse:repository', ['name' => $this->argument('name'), '--which' => 'implementation']);
        }
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
            ['which', null, InputOption::VALUE_REQUIRED, "'contract' or 'implementation'", 'contract'],
        ];
    }

    protected function getNameInput()
    {
        $name = parent::getNameInput();

        //if ($this->option('which') == 'implementation') {
        //    $name = 'Parse'.$name;
        //}

        return $name;
    }

    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);

        $path = $this->laravel['path'].'/'.str_replace('\\', '/', $name).'Repository.php';

        if ($this->option('which') == 'implementation') {
            $filename = $this->argument('name') . 'Repository.php';
            $path = str_replace($filename, 'Parse'.$filename, $path);
        }

        return $path;
    }
}
