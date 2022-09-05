<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $permissions = [
//
//            [
//                'model' => "Attribute",
//                'permissions' => ['attribute-create', 'attribute-list', 'attribute-edit', 'attribute-delete']
//            ],
//            [
//                'model' => "Branch",
//                'permissions' => ['branch-create', 'branch-list', 'branch-edit', 'branch-delete']
//            ],
//            [
//                'model' => "BranchType",
//                'permissions' => ['branchType-create', 'branchType-list', 'branchType-edit', 'branchType-delete']
//            ],
//            [
//                'model' => "Category",
//                'permissions' => ['category-create', 'category-list', 'category-edit', 'category-delete']
//            ],
//            [
//                'model' => "ProductDistribution",
//                'permissions' => ['productDistribution-create', 'productDistribution-list', 'productDistribution-edit', 'productDistribution-delete']
//            ],
//            [
//                'model' => "Product",
//                'permissions' => ['product-create', 'product-list', 'product-edit', 'product-delete']
//            ],
//            [
//                'model' => "ProductStock",
//                'permissions' => ['productStock-create', 'productStock-list', 'productStock-edit', 'productStock-delete']
//            ]
//        ];
//
//        foreach ($permissions as $permission) {
//            foreach ($permission['permissions'] as $name){
//            $permission = new Permission();
//
//            $permission->name = $name;
//
//            $permission->save();
//
//            $permission_id = DB::getPdo()->lastInsertId();
//
//            DB::table('permission_model')->insert([
//                'permission_id' => $permission_id,
//                'permission_model_name' => $permission['model'],
//                'created_at' => Carbon::now(),
//                'updated_at' => Carbon::now()
//            ]);
//
//            }
//        }
    }
}
