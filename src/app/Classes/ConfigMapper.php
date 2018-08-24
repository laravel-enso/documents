<?php

namespace LaravelEnso\DocumentsManager\app\Classes;

use LaravelEnso\Helpers\app\Classes\MorphableConfigMapper;

class ConfigMapper extends MorphableConfigMapper
{
    protected $configPrefix = 'enso.documents';
    protected $morphableKey = 'documentables';
}
