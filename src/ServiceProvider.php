<?php

namespace Rushing\NavOrchard;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Str;

class ServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('nav-orchard')
            ->hasConfigFile()
            ->hasMigrations([
                'create_nav_orchard_table',
                'create_nav_orchard_node_table',
            ])
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    //->publishConfigFile()
                    ->publishMigrations()
                     ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('stephenr85/laravel-nav-orchard');
            });
    }

    public function bootingPackage()
    {

        //...

    }
}
