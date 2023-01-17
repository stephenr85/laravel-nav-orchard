# Laravel Nav Orchard

Navigation trees for Laravel.

## Install

```
composer require stephenr85/laravel-nav-orchard
php artisan nav-orchard:install
```

### Optionally, you may run the seeder

```
php artisan db:seed --class="\Rushing\NavOrchard\Seeders\NavOrchardSeeder"
```

## Usage

A quick way to implement without creating a whole editor is to seed a few main items.

```php
$orchard->nodes()->firstOrCreate([
    'name' => 'About',
    'slug' => 'about',
]);
```

In your existing page editor, make the NavOrchard trees available via a dropdown.
```php
$orchard = NavOrchard::with('roots')->firstWhere('slug', 'main')->flatTrees();
```

And in the save controller method for your page...
```php
$navParentId = $request->input('nav_parent_id');
if($navParentId) {
    $node = NavOrchardNode::firstOrCreateFromSubject($page, 'main', $navParentId);
}
```
### Display

In your controller or view, get the trees with a page. Sometimes a subject may be represented by more than one node, so this is a collection.
```php
$tree = orchard('main')->findTreesBySubject($page)->first();
```

