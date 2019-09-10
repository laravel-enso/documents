<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Documents\app\Policies\Policy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Document::class => Policy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
