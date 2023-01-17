<?php

if(!function_exists('orchard')) {
    function orchard($slug) {
        return \Rushing\NavOrchard\Models\NavOrchard::firstWhere('slug', $slug);
    }
}
