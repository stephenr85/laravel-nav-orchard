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

```
$orchard->nodes()->firstOrCreate([
    'name' => 'About',
    'slug' => 'about',
]);
```

In your existing page editor, make the NavOrchard trees available via a dropdown.
```
$orchard = NavOrchard::with('roots')->firstWhere('slug', 'main')->flatTrees();
```

And in the save controller method for your page...
```
$navParentId = $request->input('nav_parent_id');
if($navParentId) {
    $node = NavOrchardNode::firstOrCreateFromSubject($page, 'main', $navParentId);
}
```
