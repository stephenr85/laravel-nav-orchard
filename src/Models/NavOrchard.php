<?php

namespace Rushing\NavOrchard\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class NavOrchard extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'slug',
        'text',
    ];

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

    public function nodes()
    {
        return $this->hasMany(NavOrchardNode::class);
    }

    public function roots()
    {
        return $this->nodes()->where('parent_id', 0);
    }

    public function flatTrees()
    {
        $trees = NavOrchardNode::tree()->where('nav_orchard_id', $this->id)->get();
        return $trees;
    }

    public function trees()
    {
        $trees = NavOrchardNode::tree()->where('nav_orchard_id', $this->id)->get()->toTree();
        return $trees;
    }
}
