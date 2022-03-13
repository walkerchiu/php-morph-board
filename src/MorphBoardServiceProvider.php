<?php

namespace WalkerChiu\MorphBoard;

use Illuminate\Support\ServiceProvider;

class MorphBoardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/morph-board.php' => config_path('wk-morph-board.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_morph_board_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_morph_board_table.php'
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-morph-board');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-morph-board'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-morph-board.command.cleaner')
            ]);
        }

        config('wk-core.class.morph-board.board')::observe(config('wk-core.class.morph-board.boardObserver'));
        config('wk-core.class.morph-board.boardLang')::observe(config('wk-core.class.morph-board.boardLangObserver'));
    }

    /**
     * Register the blade directives
     *
     * @return void
     */
    private function bladeDirectives()
    {
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-morph-board')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/morph-board.php', 'wk-morph-board'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/morph-board.php', 'morph-board'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
