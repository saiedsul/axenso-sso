<?php

namespace Axenso\Sso\Providers;

use Illuminate\Support\ServiceProvider;

class SsoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //php artisan vendor:publish --provider="Axenso\Sso\Providers\SsoServiceProvider" --tag="sso-config"
        if (app()->runningInConsole()) {
            $this->registerMigrations();
            $this->publishes([
                __DIR__.'/../../config/sso.php' => config_path('sso.php'),
            ], 'sso-config');
          }
          $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }

    /**
     * Register Sanctum's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
            if (! class_exists('CreateSsoTokensTable')) {
              $this->publishes([
                __DIR__ . '/../../database/migrations/create_sso_tokens_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_sso_tokens_table.php'),
              ], 'sso-migrations');
            }
    }
}
