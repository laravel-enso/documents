<?php

namespace LaravelEnso\Documents;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Documents\app\Policies\Policy;

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
