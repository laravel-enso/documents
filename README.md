<!--h-->
# Documents Manager
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3118ebe6bb4647df99675e83a9f56de2)](https://www.codacy.com/app/laravel-enso/DocumentsManager?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/DocumentsManager&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/85587885/shield?branch=master)](https://styleci.io/repos/85587885)
[![License](https://poser.pugx.org/laravel-enso/datatable/license)](https://https://packagist.org/packages/laravel-enso/datatable)
[![Total Downloads](https://poser.pugx.org/laravel-enso/documentsmanager/downloads)](https://packagist.org/packages/laravel-enso/documentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/documentsmanager/version)](https://packagist.org/packages/laravel-enso/documentsmanager)
<!--/h-->

Documents Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/documentsmanager/screenshots/Selection_019_thumb.png)](https://laravel-enso.github.io/documentsmanager/videos/demo_01.webm)

<sup>click on the photo to view a short demo in compatible browsers</sup>

### Features

- permits the management (upload, download, delete, show) of documents in the application
- can link documents to any other model
- comes with its own VueJS component
- uses [FileManager](https://github.com/laravel-enso/FileManager) for file operations
- uses the [Image Transformer](https://github.com/laravel-enso/ImageTransformer) package for cropping and optimizing the avatar files
- security policies are used to enforce proper user authorization

### Under the Hood
- creates a `Document` model that has a `documentable` morphTo relationship
- polymorphic relationships are used, which makes it possible to attach documents to any other entity
- within the entity to which we want to attach documents, we must use the `Documentable` trait

### Installation Steps

1. Add `LaravelEnso\CommentsManager\DocumentsServiceProvider::class` to `config/app.php`

2. Run migrations.

3. Publish the config file with `php artisan vendor:publish --tag=documents-config`. Define the `'model_alias' => 'App\Model'` mapping in the `config/documents.php` file.

4. Publish the VueJS component with `php artisan vendor:publish --tag=documents-component`.

4. Include the VueJS component in your `app.js`. Compile.

5. Add `use Documentable` in the Model that need documents and import the trait. This way you can call the $model->documents relationship.

6. Because users upload documents you can add 'use Documents' to the User model. This trait will set the relationship between users and documents that they create.

7. Add to you blade

    ```
    <documents-manager :id="ownerId"
        :file-size-limit="5000000"
        type="model_alias">
    </documents-manager>
    ```

### Options

- `type` - the commentable model alias (required) you set at the installation step #3
- `id` - the id of the commentable model (required)
- `header-class` - header class for the box element: info (default option) / default / primary / warning / danger / default

### Publishes

- `php artisan vendor:publish --tag=documents-config` - configuration file
- `php artisan vendor:publish --tag=documents-component` - the VueJS component
- `php artisan vendor:publish --tag=enso-update` - a common alias for when wanting to update the VueJS component,
once a newer version is released
- `php artisan vendor:publish --tag=enso-config` - a common alias for when wanting to update the config,
once a newer version is released

<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->