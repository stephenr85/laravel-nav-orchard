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
        $trees = NavOrchardNode::tree()->where('nav_orchard_id', $this->id)->orderBy('depth')->orderBy('order')->get();
        return $trees;
    }

    public function trees()
    {
        $trees = NavOrchardNode::tree()->where('nav_orchard_id', $this->id)->orderBy('order')->get()->toTree();
        return $trees;
    }

    public function findTreesBySubject($subject, $constraint = null)
    {
        $nodes = NavOrchardNode::with('rootAncestor')->whereSubject($subject)->where('nav_orchard_id', $this->id)->get();

        $trees = NavOrchardNode::treeOf(function($query) use ($nodes, $constraint) {
            $query->whereIn('id', $nodes->pluck('rootAncestor.id'));
            if(is_callable($constraint)) {
                $constraint($query);
            }
        })->get()->toTree();

        return $trees;
    }
}
