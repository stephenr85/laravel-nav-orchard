<?php

namespace Rushing\NavOrchard\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Rushing\NavOrchard\Models\NavOrchard;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NavOrchardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $orchard = NavOrchard::firstOrCreate(['slug' => 'main'], [
            'name' => 'Main Navigation',
        ]);

        $orchard->nodes()->firstOrCreate([
            'name' => 'Home',
            'slug' => 'home',
        ]);

    }
}
