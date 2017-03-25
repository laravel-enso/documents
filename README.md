# Documents Manager
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/3118ebe6bb4647df99675e83a9f56de2)](https://www.codacy.com/app/laravel-enso/DocumentsManager?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/DocumentsManager&amp;utm_campaign=Badge_Grade)

Library for attaching uploaded documents to documentables

# Don't forget to

artisan vendor:publish --tag=documents-migrations
artisan vendor:publish --tag=documents-component

php artisan migrate

include the vue-component in your app.js

run gulp

## You can

Build a partial to use with the vue component in your app/resources/views/partials/documents-labels.blade.php

```
<span slot="documents-manager-title">{{ __("Documents") }}</span>
@include('laravel-enso/core::partials.modal')
```

and then you can use

```
<documents-manager options>
@include('partials.documents-labels')
</documents-manager>
```
