<?php

namespace Rappasoft\LaravelLivewireTables;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\ComponentHookRegistry;
use Rappasoft\LaravelLivewireTables\Commands\MakeCommand;
use Rappasoft\LaravelLivewireTables\Commands\CleanupExpiredPersistStates;
use Rappasoft\LaravelLivewireTables\Features\AutoInjectRappasoftAssets;
use Rappasoft\LaravelLivewireTables\Mechanisms\RappasoftFrontendAssets;

class LaravelLivewireTablesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        if (class_exists(AboutCommand::class) && class_exists(\Composer\InstalledVersions::class)) {
            AboutCommand::add('Rappasoft Laravel Livewire Tables', [
                'Version' => \Composer\InstalledVersions::getPrettyVersion('rappasoft/laravel-livewire-tables'),
            ]);
        }

        $this->mergeConfigFrom(
            __DIR__.'/../config/livewire-tables.php', 'livewire-tables'
        );

        // Load Default Translations
        if (config('livewire-tables.use_json_translations', false)) {
            // Load Default Translations
            $this->loadJsonTranslationsFrom(
                __DIR__.'/../resources/lang/json'
            );

            // Override if Published
            $this->loadJsonTranslationsFrom(
                $this->app->langPath('vendor/livewire-tables')
            );

        } else {
            $this->loadTranslationsFrom(__DIR__.'/../resources/lang/php', 'livewire-tables');
        }
        $this->addBladeLoopDirective();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-tables');
        Blade::componentNamespace('Rappasoft\\LaravelLivewireTables\\View\\Components', 'livewire-tables');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->consoleCommands();

        if (config('livewire-tables.inject_core_assets_enabled') || config('livewire-tables.inject_third_party_assets_enabled') || config('livewire-tables.enable_blade_directives')) {
            (new RappasoftFrontendAssets)->boot();
        }

    }

    public function consoleCommands(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../resources/lang/php' => $this->app->langPath('vendor/livewire-tables'),
            ], 'livewire-tables-translations');

            $this->publishes([
                __DIR__.'/../resources/lang/json' => $this->app->langPath('vendor/livewire-tables'),
            ], 'livewire-tables-translations-json');

            $this->publishes([
                __DIR__.'/../config/livewire-tables.php' => config_path('livewire-tables.php'),
            ], 'livewire-tables-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/livewire-tables'),
            ], 'livewire-tables-views');

            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/rappasoft/livewire-tables/js'),
                __DIR__.'/../resources/css' => public_path('vendor/rappasoft/livewire-tables/css'),
            ], 'livewire-tables-public');

            $this->publishes([
                __DIR__.'/../database/migrations/create_table_persist_states_table.php' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_table_persist_states_table.php'),
            ], 'livewire-tables-migrations');

            $this->commands([
                MakeCommand::class,
                CleanupExpiredPersistStates::class,
            ]);
        }
    }

    public function addBladeLoopDirective(): void
    {
        Blade::directive('tableloop', function ($expression) {
            return "<?php foreach ($expression): ?>";
        });

        Blade::directive('endtableloop', function ($expression) {
            return '<?php endforeach; ?>';
        });

    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/livewire-tables.php', 'livewire-tables'
        );
        if (config('livewire-tables.inject_core_assets_enabled') || config('livewire-tables.inject_third_party_assets_enabled') || config('livewire-tables.enable_blade_directives')) {
            (new RappasoftFrontendAssets)->register();
            ComponentHookRegistry::register(AutoInjectRappasoftAssets::class);
        }
    }
}
