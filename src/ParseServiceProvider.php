<?php

namespace LaraParse;

use Illuminate\Support\ServiceProvider;
use LaraParse\Auth\ParseUserProvider;
use LaraParse\Subclasses\User;
use Parse\ParseClient;

class ParseServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerAuthProvider();
        $this->registerCustomValidation();
        $this->registerSubclasses();
        $this->bootParseClient();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Register our user registrar service
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'LaraParse\Services\Registrar'
        );

        // Register our custom commands
        $this->app['command.parse.subclass.make'] = $this->app->share(function ($app) {
            return $app->make('LaraParse\Console\SubclassMakeCommand');
        });

        $this->commands('command.parse.subclass.make');
    }

    private function registerConfig()
    {
        $configPath = __DIR__ . '/../config/parse.php';
        $this->publishes([$configPath => config_path('parse.php')], 'config');
        $this->mergeConfigFrom($configPath, 'parse');
    }

    private function registerAuthProvider()
    {
        $this->app['auth']->extend('parse', function () {
            return new ParseUserProvider;
        });
    }

    private function registerSubclasses()
    {
        User::registerSubclass();

        foreach ($this->app['config']->get('parse.subclasses') as $subclass) {
            call_user_func("$subclass::registerSubclass");
        }
    }

    private function registerCustomValidation()
    {
        $this->app['validator']->extend('parse_user_unique', 'LaraParse\Validation\Validator@parseUserUnique');
    }

    private function bootParseClient()
    {
        $config = $this->app['config']->get('parse');

        // Init the parse client
        ParseClient::initialize($config['app_id'], $config['rest_key'], $config['master_key']);
    }
}
