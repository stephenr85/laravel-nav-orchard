<?php

namespace Rushing\NavOrchard\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Rushing\NavOrchard\Models\NavOrchardTree;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NavOrchardTreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $tree = NavOrchardTree::firstOrCreate(['slug' => 'main'], [
            'name' => 'Main Navigation',
        ]);

        $tree->nodes()->firstOrCreate()

    }
}
