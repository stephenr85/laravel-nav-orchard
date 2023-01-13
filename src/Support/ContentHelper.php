<?php

namespace Rushing\ContentTools\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DOMWrap\Document;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class ContentHelper
{

    public function dom($html)
    {
        $dom = new Document();
        $dom->html($html);
        return $dom;
    }

    public function processEditorContent($html)
    {
        $dom = $this->dom($html);
        $pipes = config('content-tools.editor_content_pipeline', []);
        $dom = app(Pipeline::class)
            ->send($dom)
            ->through($pipes)
            ->then(function($dom) {
                return $dom;
            });

        return rescue(fn() => $dom->find('body')->html(), $dom->html());
        //return $dom->html();
    }

    public function renderEditorContent($html, $data = [])
    {
        $html = new HtmlString($this->processEditorContent($html));
        return Blade::render((string)$html, $data);
    }

}
