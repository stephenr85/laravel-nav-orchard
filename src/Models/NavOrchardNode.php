<?php

namespace Rushing\NavOrchard\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Spatie\Translatable\HasTranslations;
use Rushing\NavOrchard\Traits\NavOrchardNodeSubject;

class NavOrchardNode extends Model
{
    use HasFactory, HasRecursiveRelationships, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'nav_orchard_id',
        'parent_id',
        'name',
        'slug',
        'extra',
        'subject_type',
        'subject_id',
    ];

    public function getCustomPaths()
    {
        return [
            [
                'name' => 'slug_path',
                'column' => 'slug',
                'separator' => '/',
            ],
        ];
    }

    public function extra() : Attribute
    {
        return Attribute::make(
            get: function($value) {
                return $value === '' || $value === null ? null : unserialize($value);
            },
            set: function($value) {
                return serialize($value);
            }
        );
    }

    public static function whereSubject($subject)
    {
        return self::where([
            'subject_type' => get_class($subject),
            'subject_id' => $subject->id,
        ]);
    }

    public static function firstOrCreateFromSubject($subject, $orchard, $parentNode = null)
    {
        if(is_numeric($orchard)) $orchard = NavOrchard::find($orchard);
        else if(is_string($orchard)) $orchard = NavOrchard::firstWhere('slug', 'like', $orchard);

        $where = [
            'nav_orchard_id' => $orchard->id,
            'subject_type' => get_class($subject),
        ];

        if(isset($subject->id)) {
            $where['subject_id'] = $subject->id;
        }

        if(count(func_get_args()) == 3) {
            if(is_object($parentNode)) $where['parent_id'] = $parentNode->id;
            else if($parentNode == 0) $where['parent_id'] = null;
            else $where['parent_id'] = $parentNode; // let it error if they passed something else in
        }

        $attrs = [
            'url' => '#',
        ];

        if(in_array(NavOrchardNodeSubject::class, class_uses_recursive($subject))) {
            $attrs['name'] = $subject->navOrchardNodeName();
            $attrs['slug'] = $subject->navOrchardNodeSlug();
            $attrs['url'] = $subject->navOrchardNodeUrl();
        } else {
            if(isset($subject->name)) $attrs['name'] = $subject->name;
            else if(isset($subject->title)) $attrs['name'] = $subject->title;
            if(isset($subject->slug)) $attrs['slug'] = $subject->slug;
            if(isset($subject->url)) $attrs['url'] = $subject->url;
        }

        $node = self::firstOrCreate($where, $attrs);

        return $node;
    }
}
