<?php

namespace Seekerliu\ViewComposerGenerator;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ViewComposerMakeCommand extends Command
{
    protected $files;
    protected $config;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view-composer {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view composer class';

    /**
     * Create a new command instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->setConfig();

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error('view composer already exists!');

            return false;
        }

        $this->makeDirectory($path);

        $this->files->put($path, $this->buildClass($name));

        $this->info('view composer created successfully.');
    }

    /**
     * Get config.
     *
     */
    protected function setConfig()
    {
        $this->config = \Config::get('view-composer-generator');
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->config['view_composer_path'].DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';
    }

    /**
     * Get the destination dir path.
     *
     * @return string
     */
    protected function getDirPath()
    {
        return $this->config['view_composer_path'];
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }

        return $path;
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($rawName));
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Get the stub files for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/view.composer.stub';
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyDefaultNamespace', 'DummyNamespace'],
            [$this->getDefaultNamespace($this->rootNamespace()), $this->getNamespace($name)],
            $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = explode('\\',$name);

        return str_replace('DummyClass', array_pop($class), $stub);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return str_replace('\\', '', $this->laravel->getNamespace());
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');

        return $namespace ? '\\' .$namespace : '';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $path = str_replace(app_path(), '', $this->getDirPath());
        return $rootNamespace .'\\' .trim(implode('\\', array_slice(explode(DIRECTORY_SEPARATOR, $path), 1)), '\\');
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function qualifyClass($name)
    {
        //暂时不处理
        return $name;
//        $rootNamespace = $this->rootNamespace();
//
//        if (Str::startsWith($name, $rootNamespace)) {
//            return $name;
//        }
//
//        $name = str_replace('/', '\\', $name);
//
//        return $this->qualifyClass(
//            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
//        );
    }
}
