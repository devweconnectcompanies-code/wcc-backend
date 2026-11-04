<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Configuration\MenuModel;
use Spatie\Permission\Models\Permission;

class SyncMenuPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'app:sync-menu-permissions';
    protected $signature = 'sync:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Menu/Permissions Tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $menus = MenuModel::all();
        foreach ($menus as $menu) {
            $permission = Permission::query()
                ->firstOrCreate([
                    'name' => $menu->slug,
                    'menu_id' => $menu->id,
                ]);
            $this->info("Permission '{$permission->name}' synced to menu '{$menu->name}'");
        }
        $this->info('Sync Menu/Permissions tables successfully');
    }
}
