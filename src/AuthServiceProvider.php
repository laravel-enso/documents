<?php

namespace LaravelEnso\Documents;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Documents\App\Models\Document;
use LaravelEnso\Documents\App\Policies\Document as Policy;

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
