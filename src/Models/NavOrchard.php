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
    ];

    public function nodes()
    {
        return $this->hasMany(NavOrchardNode::class, 'nav_orchard_id');
    }

    public function trees()
    {
        $this->loadMissing('nodes');
        $tree = $this->nodes->where('parent_id', 0)->orderBy('order')->map([$this, 'buildTreeFrom']);
        return $tree;
    }

    public function buildTreeFrom($node, $all = null)
    {
        if($all === null) {
            $this->loadMissing('nodes');
            $all = $this->nodes;
        }

        $node->children = $all->where('parent_id', $node->parent_id)->map([$this, 'buildTreeFrom']);

        return $node;
    }
}
