<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeedControllerUrl::class,
            removeUnusedMenu::class,
            seedMenuJenisLayanan::class,
            seedChangeMenu::class,
            seedMenuMikrotik::class,
            seedMenuToPrivileges::class,
        ]);
    }
}
