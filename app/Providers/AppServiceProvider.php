<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        if (!\Cache::has('git-hash')) {
            \Cache::put('git-hash', shell_exec('git log --pretty=format:\'' . (\App::environment('production') ? '%h' : '%H') . '\' -n 1'), 1);
        }
        \View::share('git_hash', \Cache::get('git-hash'));
        \Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }
}
