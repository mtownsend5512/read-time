<?php

namespace Mtownsend\ReadTime\Providers;

use Exception;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Mtownsend\ReadTime\ReadTime;

class ReadTimeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang/', 'read-time');

        $this->publishes([
            __DIR__ . '/../config/read-time.php' => config_path('read-time.php')
        ], 'read-time-config');

        $this->publishes([
            __DIR__ . "/../resources/lang/" => resource_path("lang/vendor/read-time")
        ], 'read-time-language-files');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('read_time', function ($app, $data) {
            if (!isset($data['content'])) {
                throw new Exception('Content must be supplied to ReadTime class');
            }

            $this->mergeConfigFrom(__DIR__.'/../config/read-time.php', 'read-time');

            $content = $data['content'];
            $omitSeconds = isset($data['omit_seconds']) ? $data['omit_seconds'] : config('read-time.omit_seconds');
            $timeOnly = isset($data['time_only']) ? $data['time_only'] : config('read-time.time_only');
            $abbreviated = isset($data['abbreviated']) ? $data['abbreviated'] : config('read-time.abbreviate_time_measurements');
            $wordsPerMinute = isset($data['words_per_minute']) ? $data['words_per_minute'] : config('read-time.words_per_minute');
            $ltr = isset($data['ltr']) ? $data['ltr'] : __('read-time.reads_left_to_right');
            $translation = isset($data['translation']) ? $data['translation'] : __('read-time::read-time');

            return (new ReadTime($content))
                ->omitSeconds($omitSeconds)
                ->timeOnly($timeOnly)
                ->abbreviated($abbreviated)
                ->wpm($wordsPerMinute)
                ->ltr($ltr)
                ->setTranslation($translation);
        });
    }
}
