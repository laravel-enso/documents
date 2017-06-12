<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\DocumentsManager\app\Models\Document;
use LaravelEnso\DocumentsManager\app\Policies\DocumentPolicy;

class DocumentsAuthServiceProvider extends ServiceProvider
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
