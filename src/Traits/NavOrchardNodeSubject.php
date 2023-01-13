<?php

namespace Rushing\NavOrchard\Traits;

use Rushing\NavOrchard\Models\NavOrchardNode;

trait NavOrchardNodeSubject {

    public function bootNavOrchardTreeNodeSubject()
    {
        self::updating(function($subject) {
            dispatch(function() use ($subject) {
                $subject->navOrchardNodes()->get()->each(function($node) use ($subject) {
                    if($node->subject_name_property) {
                        $node->name = $subject->$node->subject_name_property;
                        $node->url = $subject->$node->subject_name_property;
                        $node->save();
                    }
                });
            });
        });
    }

    public function navOrchardNodes()
    {
        // TODO: eager loading
        return $this->hasMany(NavOrchardNode::class)->where([
            'subject_type' => self::class,
            'subject_id' => $this->id,
        ]);
    }

}
