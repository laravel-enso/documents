# Documents

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3118ebe6bb4647df99675e83a9f56de2)](https://www.codacy.com/app/laravel-enso/documents?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/documents&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://github.styleci.io/repos/85587885/shield?branch=master)](https://github.styleci.io/repos/85587885)
[![License](https://poser.pugx.org/laravel-enso/datatable/license)](https://packagist.org/packages/laravel-enso/datatable)
[![Total Downloads](https://poser.pugx.org/laravel-enso/documents/downloads)](https://packagist.org/packages/laravel-enso/documents)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/documents/version)](https://packagist.org/packages/laravel-enso/documents)

Documents Manager for [Laravel Enso](https://github.com/laravel-enso/Enso).

This package works exclusively within the [Enso](https://github.com/laravel-enso/Enso) ecosystem.

There is a front end implementation for this this api in the [accessories](https://github.com/enso-ui/accessories) package.

For live examples and demos, you may visit [laravel-enso.com](https://www.laravel-enso.com)

[![Watch the demo](https://laravel-enso.github.io/documents/screenshots/bulma_019_thumb.png)](https://laravel-enso.github.io/documents/videos/bulma_demo_01.webm)

<sup>click on the photo to view a short demo in compatible browsers</sup>

## Installation

Comes pre-installed in Enso.

## Features

- permits the management (upload, download, delete, show) of documents in the application
- can attach documents to any other model
- uses [Files](https://github.com/laravel-enso/Files) for file operations
- uses the [ImageTransformer](https://github.com/laravel-enso/ImageTransformer) package for optimizing 
the uploaded image files
- security policies are used to enforce proper user authorization
- comes with a `Documentable` trait that can be quickly added to the model you want to give this functionality to
- offers various configuration options, including the option to delete all attached documents 
to a Documentable entity, when it gets deleted 
- creates a `Document` model that has a `documentable` morphTo relationship
- polymorphic relationships are used, which makes it possible to attach documents to any other entity
- once documents are attached to an entity, you should not be able to delete the entity without deciding what
you want to do with the associated documents. This is configurable in the options, see below

### Configuration & Usage

Be sure to check out the full documentation for this package available at [docs.laravel-enso.com](https://docs.laravel-enso.com/backend/documents-manager.html)

### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
