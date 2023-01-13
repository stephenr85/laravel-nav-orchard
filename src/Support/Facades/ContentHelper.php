<?php

namespace Rushing\ContentTools\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ContentHelper extends Facade{

    protected static function getFacadeAccessor() {

        return \Rushing\ContentTools\Support\ContentHelper::class;
    }
}
