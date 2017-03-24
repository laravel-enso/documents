# Documents Manager

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