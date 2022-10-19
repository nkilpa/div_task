<?php

namespace App\Providers;

use App\Repositories\Impls\RequestRepository;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //регистрация интерфейса и класса репозитория
        $this->app->singleton(RequestRepositoryInterface::class, RequestRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
