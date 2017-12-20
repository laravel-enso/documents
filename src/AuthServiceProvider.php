<?php

namespace LaravelEnso\DocumentsManager;

use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Policies\DocumentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies;

    public function boot()
    {
        $this->policies = [
            Document::class => DocumentPolicy::class,
        ];

        $this->registerPolicies();
    }
}
