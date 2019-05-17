<?php

namespace LaravelEnso\Documents;

use LaravelEnso\Documents\app\Models\Document;
use LaravelEnso\Documents\app\Policies\DocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Document::class => DocumentPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
