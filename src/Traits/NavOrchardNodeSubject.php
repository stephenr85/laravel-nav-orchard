<?php

namespace Rushing\NavOrchard\Traits;

use Rushing\NavOrchard\Models\NavOrchardNode;

trait NavOrchardNodeSubject {

    public static function bootNavOrchardNodeSubject()
    {
        self::saving(function($subject) {
            dispatch(function() use ($subject) {
                $subject->navOrchardNodes()->get()->each(function($node) use ($subject) {
                    $name = $subject->navOrchardNodeName();
                    if($name) {
                        $node->name = $name;
                    }
                    $url = $subject->navOrchardNodeUrl();
                    if($url) {
                        $node->url = $url;
                    }
                    $slug = $subject->navOrchardNodeSlug();
                    if($url) {
                        $node->slug = $slug;
                    }
                    $node->save();
                });
            });
        });
    }

    public function navOrchardNodes()
    {
        // TODO: eager loading
        return $this->hasMany(NavOrchardNode::class, 'subject_id')->where('subject_type', self::class);
    }

    public function navOrchardNodeName()
    {
        return null;
    }

    public function navOrchardNodeSlug()
    {
        return isset($this->slug) ? $this->slug : null;
    }

    public function navOrchardNodeUrl()
    {
        return null;
    }
}
