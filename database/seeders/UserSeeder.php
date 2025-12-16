<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Entity;
use App\Models\Status;
use App\Enums\StatusEnum;
use App\Enums\ProfileEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Actions\User\CreateUserAction;
use Spatie\Permission\PermissionRegistrar;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        User::create([
            'username' => 'U001',
            'code' => 'U001',
            'last_name' => 'SUPER',
            'first_name' => 'ADMIN',
            'email' => 'brahimafane06@gmail.com',
            'telephone' => '01050635899',
            'password' =>'12345678',
            'entity_id' => Entity::firstWhere('code', 'CIEAMI')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::SYSTEM_ADMIN)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        User::create([
            'username' => 'U002',
            'code' => 'U002',
            'last_name' => 'ADMIN',
            'first_name' => 'ADMIN',
            'email' => 'admin@gmail.com',
            'telephone' => '01050635899',
            'password' =>'12345678',
            'entity_id' => Entity::firstWhere('code', 'CIEAMI')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::ADMIN)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        User::create([
            'username' => 'U003',
            'code' => 'U003',
            'last_name' => 'ADMIN',
            'first_name' => 'LCA',
            'email' => 'adminlca@gmail.com',
            'telephone' => '01050635899',
            'password' => '12345678',
            'entity_id' => Entity::firstWhere('code', 'LCA')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::ADMIN_ORGANIZATION)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        User::create([
            'username' => 'U012',
            'code' => 'U012',
            'last_name' => 'ADMIN',
            'first_name' => 'BCA-CI',
            'email' => 'adminbca@gmail.com',
            'telephone' => '01050635899',
            'password' => '12345678',
            'entity_id' => Entity::firstWhere('code', 'BCA_CI')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::ADMIN_ORGANIZATION)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        User::create([
            'username' => 'U013',
            'code' => 'U013',
            'last_name' => 'CEO',
            'first_name' => 'BCA-CI',
            'email' => 'ceobca@gmail.com',
            'telephone' => '01050635899',
            'password' => '12345678',
            'entity_id' => Entity::firstWhere('code', 'BCA_CI')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::ADMIN_ORGANIZATION)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        User::create([
            'username' => 'U014',
            'code' => 'U014',
            'last_name' => 'ADMIN',
            'first_name' => 'SGA',
            'email' => 'adminsga@gmail.com',
            'telephone' => '01050635899',
            'password' => '12345678',
            'entity_id' => Entity::firstWhere('code', 'SGA')->id,
            'current_role_id' => Role::firstWhere('name', \App\Enums\RoleEnum::ADMIN_ORGANIZATION)->id,
            'status_id' => Status::firstWhere('code', \App\Enums\StatusEnum::ACTIVE)->id,
        ]);

        // Assign roles to all users
        $users = User::all();
            
        foreach ($users as $user) {
            app(PermissionRegistrar::class)->setPermissionsTeamId($user->current_role_id);
            $role = Role::find($user->current_role_id);
            if ($role) {
                $user->assignRole($role->name);
            }
        }
    }
}
