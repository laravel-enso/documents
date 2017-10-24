<!--h-->
# Documents Manager
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3118ebe6bb4647df99675e83a9f56de2)](https://www.codacy.com/app/laravel-enso/DocumentsManager?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/DocumentsManager&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/85587885/shield?branch=master)](https://styleci.io/repos/85587885)
[![License](https://poser.pugx.org/laravel-enso/datatable/license)](https://https://packagist.org/packages/laravel-enso/datatable)
[![Total Downloads](https://poser.pugx.org/laravel-enso/documentsmanager/downloads)](https://packagist.org/packages/laravel-enso/documentsmanager)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/documentsmanager/version)](https://packagist.org/packages/laravel-enso/documentsmanager)
<!--/h-->

Documents Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

[![Watch the demo](https://laravel-enso.github.io/documentsmanager/screenshots/bulma_019_thumb.png)](https://laravel-enso.github.io/documentsmanager/videos/bulma_demo_01.webm)

<sup>click on the photo to view a short demo in compatible browsers</sup>

### Features

- permits the management (upload, download, delete, show) of documents in the application
- can link documents to any other model
- comes with its own VueJS component
- uses [FileManager](https://github.com/laravel-enso/FileManager) for file operations
- uses the [ImageTransformer](https://github.com/laravel-enso/ImageTransformer) package for optimizing the uploaded image files
- security policies are used to enforce proper user authorization

### Under the Hood

- creates a `Document` model that has a `documentable` morphTo relationship
- polymorphic relationships are used, which makes it possible to attach documents to any other entity
- within the entity to which we want to attach documents, we must use the `Documentable` trait

### Installation Steps

1. Run the migrations

2. Publish the config file with `php artisan vendor:publish --tag=documents-config`. Define the `'model_alias' => 'App\Model'` mapping in the `config/documents.php` file

3. Publish the VueJS components with `php artisan vendor:publish --tag=vue-components`

4. Include the VueJS components in your `app.js` and then compile with `gulp` / `npm run dev`

````js
Vue.component('documents', require('./vendor/laravel-enso/components/documents/Documents.vue'));
Vue.component('document', require('./vendor/laravel-enso/components/documents/Document.vue'));
````

5. Add `use Documentable` in the Model that need documents and import the trait. Then you'll have access to the `$model->documents` relationship

6. Because users upload documents you can add `use Documents` to the User model. This trait will set the relationship between users and the documents that they create

7. Add the component inside your page/component

    ```
    <documents 
        id="modelId"
        type="model_alias">
    </documents>
    ```

### Options

- `id` - the id of the commentable model | required
- `type` - the commentable model alias you set at the installation step #3 | required
- `open` - boolean flag, makes the component start collapsed (default) or open | optional
- `title` - title for the component, if nothing is given 'Comments' is used | optional

### Publishes

- `php artisan vendor:publish --tag=vue-components` - the VueJS components
- `php artisan vendor:publish --tag=documents-config` - configuration file
- `php artisan vendor:publish --tag=enso-assets` - a common alias for when wanting to update the VueJS components,
once a newer version is released, can be used with the `--force` flag
- `php artisan vendor:publish --tag=enso-config` - a common alias for when wanting to update the config,
once a newer version is released, can be used with the `--force` flag

### Notes

The [Laravel Enso](https://github.com/laravel-enso/Enso) package comes with this package included.

Depends on:
 - [Core](https://github.com/laravel-enso/Core) for middleware and user model
 - [ImageTransformer](https://github.com/laravel-enso/ImageTransformer) for optimizing image files
 - [FileManager](https://github.com/laravel-enso/FileManager) for working with the uploaded files
 - [Structure manager](https://github.com/laravel-enso/StructureManager) for the migrations
 - [TrackWho](https://github.com/laravel-enso/TrackWho) for keeping track of the users making the changes to each contact
 - [VueComponents](https://github.com/laravel-enso/VueComponents) for the accompanying VueJS components
  
<!--h-->
### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
<!--/h-->