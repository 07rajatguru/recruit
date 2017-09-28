<?php

namespace App\Listeners;

use App\Events\PermissionSeederEvent;
use App\Permission;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\File;

class PermissionSeederEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PermissionSeederEvent  $event
     * @return void
     */
    public function handle(PermissionSeederEvent $event)
    {
        //
        $basePath = base_path() . '/database/seeds/';
        $permissionSeederPath = $basePath . '/PermissionTableSeeder.php';
        $todayDate = date('Y-m-d H:i:s');

        // Permission table seeder

//        $this->info('Generating PermissionTableSeeder Seeder');
        File::delete($permissionSeederPath);
        $permissions = Permission::all();

        $permissionSeederContent = '';
        foreach ($permissions as $permission) {
            $permissionSeederContent .= "\t\t\tarray('id' => '{$permission->id}', 'name' => '{$permission->name}', 'display_name' => '{$permission->display_name}','description' => '{$permission->description}', 'created_at' => '{$permission->created_at}', 'updated_at' => '{$permission->updated_at}'),\n";
        }

        $success = $this->writeSeederFile($permissionSeederPath,'PermissionTableSeeder','permissions',$permissionSeederContent);

        if ($success === false){
//            $this->error('Error in Generating Permission Seeder');
        } else{
//            $this->info('Permission Seeder successfully generated');
        }

//        $this->info ("\nGenerating optimized class loader");
        \Artisan::call('clear-compiled', []);
        \Artisan::call('optimize', []);

//        $this->info ("\nPlease run following command to insert new seeds. Also, please commit new seeder files");
//        $this->info ("\nphp artisan db:seed --class=PermissionTableSeeder");

    }




    private function writeSeederFile ($filePath, $seederName, $tableName, $seederContent) {
        $fileContent = $this->getFileTemplate ();

        $fileContent = preg_replace ('/\{SEEDER_NAME\}/', $seederName, $fileContent);
        $fileContent = preg_replace ('/\{SEEDER_TABLE_NAME\}/', $tableName, $fileContent);
        $fileContent = preg_replace ('/\{SEEDER_CONTENT\}/', $seederContent, $fileContent);

        $written = File::put($filePath, $fileContent);
        return $written;
    }

    private function getFileTemplate () {
        return '<?php
            use Illuminate\Database\Seeder;

            class {SEEDER_NAME} extends Seeder
            {
                public function run()
                {
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                    DB::table("{SEEDER_TABLE_NAME}")->truncate();

                    $data = array(
                        {SEEDER_CONTENT}
                    );

                    DB::table("{SEEDER_TABLE_NAME}")->insert($data);
                }
            }
            ';
    }
}
