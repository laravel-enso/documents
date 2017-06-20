# Documents Manager
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3118ebe6bb4647df99675e83a9f56de2)](https://www.codacy.com/app/laravel-enso/DocumentsManager?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/DocumentsManager&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/85587885/shield?branch=master)](https://styleci.io/repos/85587885)
[![Total Downloads](https://poser.pugx.org/laravel-enso/documentsmanager/downloads)](https://packagist.org/packages/laravel-enso/documentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/documentsmanager/version)](https://packagist.org/packages/laravel-enso/documentsmanager)

Documents Manager for Laravel Enso. This plugin creates a Document model that has a `documentable` morphTo relation.

### Installation Steps

1. Add `LaravelEnso\CommentsManager\DocumentsManagerServiceProvider::class` to `config/app.php`. (included if you use LaravelEnso/coreplus)

2. Run migrations.

3. Publish the config file with `php artisan vendor:publish --tag=documents-config`.

4. Publish the vue component with `php artisan vendor:publish --tag=documents-component`.

5. Include the vue-component in your app.js.

6. Run gulp.

7. Add the following relationship to the Model that need documents.

```php
public function documents()
{
    return $this->morphMany('LaravelEnso\DocumentsManager\app\Models\Document', 'documentable');
}
```

8. Define the 'model' => 'App\Model' mapping in the config.

9. Because users upload documents you can add the following relationship to the User model.

```php
public function documents()
{
    return $this->hasMany('LaravelEnso\DocumentsManager\app\Models\Document', 'created_by');
}
```

10. Add to you blade

```
<documents-manager :id="ownerId"
    :file-size-limit="5000000"
    type="owner">
    <span slot="documents-manager-title">{{ __("Documents") }}</span>
    @include('laravel-enso/core::partials.modal')
</documents-manager>
```

### Options

	`type` - the commentable model (required)
	`id` - the id of the commentable model (required)
    `header-class` - header class for the box element: info (default option) / default / primary / warning / danger / default

### Contributions

...are welcome
