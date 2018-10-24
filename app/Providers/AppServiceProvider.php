<?php

namespace App\Providers;

use App\Services\DevBouncer;
use App\Services\SkypeBotMan;
use App\Services\SkypeMessageFormatter;
use App\Services\UserIntervalParser;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'dev_bouncer' => DevBouncer::class,
        'skype_formatter' => SkypeMessageFormatter::class,
        'user_interval_converter' => UserIntervalParser::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        $this->app->singleton(SkypeBotMan::class, function (Application $app) {
            return new SkypeBotMan($app->get('botman'));
        });
    }

}
