<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $createUser = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_USER, 'guard_name' => 'sanctum']);
        $viewUser = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_USER, 'guard_name' => 'sanctum']);
        $updateUser = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_USER, 'guard_name' => 'sanctum']);
        $deleteUser = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_USER, 'guard_name' => 'sanctum']);
        $enableUser = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_USER, 'guard_name' => 'sanctum']);
        $disableUser = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_USER, 'guard_name' => 'sanctum']);
        $resetUser = Permission::create(['name' => \App\Enums\PermissionEnum::RESET_USER, 'guard_name' => 'sanctum']);

        $createInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_INVOICE, 'guard_name' => 'sanctum']);
        $viewInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_INVOICE, 'guard_name' => 'sanctum']);
        $updateInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_INVOICE, 'guard_name' => 'sanctum']);
        $deleteInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_INVOICE, 'guard_name' => 'sanctum']);
        $cancelInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::CANCEL_INVOICE, 'guard_name' => 'sanctum']);
        $generateInvoice = Permission::create(['name' => \App\Enums\PermissionEnum::GENERATE_INVOICE, 'guard_name' => 'sanctum']);
        $invoiceStatistics = Permission::create(['name' => \App\Enums\PermissionEnum::INVOICE_STATISTICS, 'guard_name' => 'sanctum']);

        $createPayment = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_PAYMENT, 'guard_name' => 'sanctum']);
        $viewPayment = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_PAYMENT, 'guard_name' => 'sanctum']);
        $updatePayment = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_PAYMENT, 'guard_name' => 'sanctum']);
        $deletePayment = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_PAYMENT, 'guard_name' => 'sanctum']);
        $cancelPayment = Permission::create(['name' => \App\Enums\PermissionEnum::CANCEL_PAYMENT, 'guard_name' => 'sanctum']);
        $paymentStatistics = Permission::create(['name' => \App\Enums\PermissionEnum::PAYMENT_STATISTICS, 'guard_name' => 'sanctum']);

        $createStatus = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_STATUS, 'guard_name' => 'sanctum']);
        $viewStatus = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_STATUS, 'guard_name' => 'sanctum']);
        $updateStatus = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_STATUS, 'guard_name' => 'sanctum']);
        $deleteStatus = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_STATUS, 'guard_name' => 'sanctum']);
        $enableStatus = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_STATUS, 'guard_name' => 'sanctum']);
        $disableStatus = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_STATUS, 'guard_name' => 'sanctum']);

        $createRole = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_ROLE, 'guard_name' => 'sanctum']);
        $viewRole = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_ROLE, 'guard_name' => 'sanctum']);
        $updateRole = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_ROLE, 'guard_name' => 'sanctum']);
        $deleteRole = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_ROLE, 'guard_name' => 'sanctum']);

        $createPermission = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_PERMISSION, 'guard_name' => 'sanctum']);
        $viewPermission = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_PERMISSION, 'guard_name' => 'sanctum']);
        $updatePermission = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_PERMISSION, 'guard_name' => 'sanctum']);
        $deletePermission = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_PERMISSION, 'guard_name' => 'sanctum']);

        $createEntity = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_ENTITY, 'guard_name' => 'sanctum']);
        $viewEntity = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_ENTITY, 'guard_name' => 'sanctum']);
        $updateEntity = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_ENTITY, 'guard_name' => 'sanctum']);
        $deleteEntity = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_ENTITY, 'guard_name' => 'sanctum']);
        $enableEntity = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_ENTITY, 'guard_name' => 'sanctum']);
        $disableEntity = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_ENTITY, 'guard_name' => 'sanctum']);

        $createEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_ENTITY_TYPE, 'guard_name' => 'sanctum']);
        $viewEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_ENTITY_TYPE, 'guard_name' => 'sanctum']);
        $updateEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_ENTITY_TYPE, 'guard_name' => 'sanctum']);
        $deleteEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_ENTITY_TYPE, 'guard_name' => 'sanctum']);
        $enableEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_ENTITY_TYPE, 'guard_name' => 'sanctum']);
        $disableEntityType = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_ENTITY_TYPE, 'guard_name' => 'sanctum']);

        $createVehicle = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE, 'guard_name' => 'sanctum']);
        $viewVehicle = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE, 'guard_name' => 'sanctum']);
        $updateVehicle = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE, 'guard_name' => 'sanctum']);
        $deleteVehicle = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE, 'guard_name' => 'sanctum']);

        $createVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE_MODEL, 'guard_name' => 'sanctum']);
        $viewVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE_MODEL, 'guard_name' => 'sanctum']);
        $updateVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE_MODEL, 'guard_name' => 'sanctum']);
        $deleteVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE_MODEL, 'guard_name' => 'sanctum']);
        $enableVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_VEHICLE_MODEL, 'guard_name' => 'sanctum']);
        $disableVehicleModel = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_VEHICLE_MODEL, 'guard_name' => 'sanctum']);

        $createBrand = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_BRAND, 'guard_name' => 'sanctum']);
        $viewBrand = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_BRAND, 'guard_name' => 'sanctum']);
        $updateBrand = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_BRAND, 'guard_name' => 'sanctum']);
        $deleteBrand = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_BRAND, 'guard_name' => 'sanctum']);
        $enableBrand = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_BRAND, 'guard_name' => 'sanctum']);
        $disableBrand = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_BRAND, 'guard_name' => 'sanctum']);

        $createColor = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_COLOR, 'guard_name' => 'sanctum']);
        $viewColor = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_COLOR, 'guard_name' => 'sanctum']);
        $updateColor = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_COLOR, 'guard_name' => 'sanctum']);
        $deleteColor = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_COLOR, 'guard_name' => 'sanctum']);
        $enableColor = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_COLOR, 'guard_name' => 'sanctum']);
        $disableColor = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_COLOR, 'guard_name' => 'sanctum']);

        $createDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $viewDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $updateDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $deleteDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $enableDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $disableDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);
        $calculateDepreciationTable = Permission::create(['name' => \App\Enums\PermissionEnum::CALCULATE_DEPRECIATION_TABLE, 'guard_name' => 'sanctum']);


        $createVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE_AGE, 'guard_name' => 'sanctum']);
        $viewVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE_AGE, 'guard_name' => 'sanctum']);
        $updateVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE_AGE, 'guard_name' => 'sanctum']);
        $deleteVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE_AGE, 'guard_name' => 'sanctum']);
        $enableVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_VEHICLE_AGE, 'guard_name' => 'sanctum']);
        $disableVehicleAge = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_VEHICLE_AGE, 'guard_name' => 'sanctum']);

        $createVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);
        $viewVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);
        $updateVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);
        $deleteVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);
        $enableVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);
        $disableVehicleEnergy = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_VEHICLE_ENERGY, 'guard_name' => 'sanctum']);

        $createVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE_GENRE, 'guard_name' => 'sanctum']);
        $viewVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE_GENRE, 'guard_name' => 'sanctum']);
        $updateVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE_GENRE, 'guard_name' => 'sanctum']);
        $deleteVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE_GENRE, 'guard_name' => 'sanctum']);
        $enableVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_VEHICLE_GENRE, 'guard_name' => 'sanctum']);
        $disableVehicleGenre = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_VEHICLE_GENRE, 'guard_name' => 'sanctum']);

        $createCheck = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_CHECK, 'guard_name' => 'sanctum']);
        $viewCheck = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_CHECK, 'guard_name' => 'sanctum']);
        $updateCheck = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_CHECK, 'guard_name' => 'sanctum']);
        $deleteCheck = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_CHECK, 'guard_name' => 'sanctum']);

        $createBank = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_BANK, 'guard_name' => 'sanctum']);
        $viewBank = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_BANK, 'guard_name' => 'sanctum']);
        $updateBank = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_BANK, 'guard_name' => 'sanctum']);
        $deleteBank = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_BANK, 'guard_name' => 'sanctum']);
        $enableBank = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_BANK, 'guard_name' => 'sanctum']);
        $disableBank = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_BANK, 'guard_name' => 'sanctum']);

        $createPaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_PAYMENT_TYPE, 'guard_name' => 'sanctum']);
        $viewPaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_PAYMENT_TYPE, 'guard_name' => 'sanctum']);
        $updatePaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_PAYMENT_TYPE, 'guard_name' => 'sanctum']);
        $deletePaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_PAYMENT_TYPE, 'guard_name' => 'sanctum']);
        $enablePaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_PAYMENT_TYPE, 'guard_name' => 'sanctum']);
        $disablePaymentType = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_PAYMENT_TYPE, 'guard_name' => 'sanctum']);

        $createPaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_PAYMENT_METHOD, 'guard_name' => 'sanctum']);
        $viewPaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_PAYMENT_METHOD, 'guard_name' => 'sanctum']);
        $updatePaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_PAYMENT_METHOD, 'guard_name' => 'sanctum']);
        $deletePaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_PAYMENT_METHOD, 'guard_name' => 'sanctum']);
        $enablePaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_PAYMENT_METHOD, 'guard_name' => 'sanctum']);
        $disablePaymentMethod = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_PAYMENT_METHOD, 'guard_name' => 'sanctum']);

        $createClient = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_CLIENT, 'guard_name' => 'sanctum']);
        $viewClient = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_CLIENT, 'guard_name' => 'sanctum']);
        $updateClient = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_CLIENT, 'guard_name' => 'sanctum']);
        $deleteClient = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_CLIENT, 'guard_name' => 'sanctum']);
        $enableClient = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_CLIENT, 'guard_name' => 'sanctum']);
        $disableClient = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_CLIENT, 'guard_name' => 'sanctum']);

        $createQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_QR_CODE, 'guard_name' => 'sanctum']);
        $viewQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_QR_CODE, 'guard_name' => 'sanctum']);
        $updateQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_QR_CODE, 'guard_name' => 'sanctum']);
        $deleteQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_QR_CODE, 'guard_name' => 'sanctum']);
        $enableQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_QR_CODE, 'guard_name' => 'sanctum']);
        $disableQrCode = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_QR_CODE, 'guard_name' => 'sanctum']);

        $createUserAction = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_USER_ACTION, 'guard_name' => 'sanctum']);
        $viewUserAction = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_USER_ACTION, 'guard_name' => 'sanctum']);
        $updateUserAction = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_USER_ACTION, 'guard_name' => 'sanctum']);
        $deleteUserAction = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_USER_ACTION, 'guard_name' => 'sanctum']);

        $createUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);
        $viewUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);
        $updateUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);
        $deleteUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);
        $enableUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);
        $disableUserActionType = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_USER_ACTION_TYPE, 'guard_name' => 'sanctum']);

        $createFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_FNE_SETTING, 'guard_name' => 'sanctum']);
        $viewFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_FNE_SETTING, 'guard_name' => 'sanctum']);
        $updateFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_FNE_SETTING, 'guard_name' => 'sanctum']);
        $deleteFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_FNE_SETTING, 'guard_name' => 'sanctum']);
        $enableFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_FNE_SETTING, 'guard_name' => 'sanctum']);
        $disableFneSetting = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_FNE_SETTING, 'guard_name' => 'sanctum']);

        $createUsage = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_USAGE, 'guard_name' => 'sanctum']);
        $viewUsage = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_USAGE, 'guard_name' => 'sanctum']);
        $updateUsage = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_USAGE, 'guard_name' => 'sanctum']);
        $deleteUsage = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_USAGE, 'guard_name' => 'sanctum']);
        $enableUsage = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_USAGE, 'guard_name' => 'sanctum']);
        $disableUsage = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_USAGE, 'guard_name' => 'sanctum']);

        $createVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);
        $viewVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);
        $updateVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);
        $deleteVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);
        $enableVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);
        $disableVehicleCharacteristic = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_VEHICLE_CHARACTERISTIC, 'guard_name' => 'sanctum']);

        $createDealer = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_DEALER, 'guard_name' => 'sanctum']);
        $viewDealer = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_DEALER, 'guard_name' => 'sanctum']);
        $updateDealer = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_DEALER, 'guard_name' => 'sanctum']);
        $deleteDealer = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_DEALER, 'guard_name' => 'sanctum']);
        $enableDealer = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_DEALER, 'guard_name' => 'sanctum']);
        $disableDealer = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_DEALER, 'guard_name' => 'sanctum']);

        $createCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_CALCULATION, 'guard_name' => 'sanctum']);
        $viewCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_CALCULATION, 'guard_name' => 'sanctum']);
        $updateCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_CALCULATION, 'guard_name' => 'sanctum']);
        $deleteCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_CALCULATION, 'guard_name' => 'sanctum']);
        $enableCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_CALCULATION, 'guard_name' => 'sanctum']);
        $disableCalculation = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_CALCULATION, 'guard_name' => 'sanctum']);

        $createPrice = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_PRICE, 'guard_name' => 'sanctum']);
        $viewPrice = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_PRICE, 'guard_name' => 'sanctum']);
        $updatePrice = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_PRICE, 'guard_name' => 'sanctum']);
        $deletePrice = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_PRICE, 'guard_name' => 'sanctum']);
        $enablePrice = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_PRICE, 'guard_name' => 'sanctum']);
        $disablePrice = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_PRICE, 'guard_name' => 'sanctum']);

        $createTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);
        $viewTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);
        $updateTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);
        $deleteTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);
        $enableTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::ENABLE_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);
        $disableTransactionType = Permission::create(['name' => \App\Enums\PermissionEnum::DISABLE_TRANSACTION_TYPE, 'guard_name' => 'sanctum']);

        $createTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_TRANSACTION, 'guard_name' => 'sanctum']);
        $viewTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_TRANSACTION, 'guard_name' => 'sanctum']);
        $updateTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_TRANSACTION, 'guard_name' => 'sanctum']);
        $deleteTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::DELETE_TRANSACTION, 'guard_name' => 'sanctum']);
        $cancelTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::CANCEL_TRANSACTION, 'guard_name' => 'sanctum']);
        $validateTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::VALIDATE_TRANSACTION, 'guard_name' => 'sanctum']);
        $rejectTransaction = Permission::create(['name' => \App\Enums\PermissionEnum::REJECT_TRANSACTION, 'guard_name' => 'sanctum']);

        $createOrder = Permission::create(['name' => \App\Enums\PermissionEnum::CREATE_ORDER, 'guard_name' => 'sanctum']);
        $viewOrder = Permission::create(['name' => \App\Enums\PermissionEnum::VIEW_ORDER, 'guard_name' => 'sanctum']);
        $updateOrder = Permission::create(['name' => \App\Enums\PermissionEnum::UPDATE_ORDER, 'guard_name' => 'sanctum']);
        $validateOrder = Permission::create(['name' => \App\Enums\PermissionEnum::VALIDATE_ORDER, 'guard_name' => 'sanctum']);
        $rejectOrder = Permission::create(['name' => \App\Enums\PermissionEnum::REJECT_ORDER, 'guard_name' => 'sanctum']);
        $cancelOrder = Permission::create(['name' => \App\Enums\PermissionEnum::CANCEL_ORDER, 'guard_name' => 'sanctum']);

        $dashboard = Permission::create(['name' => \App\Enums\PermissionEnum::DASHBOARD, 'guard_name' => 'sanctum']);

        Role::create([
            'name' => \App\Enums\RoleEnum::SYSTEM_ADMIN,
            'label' => 'Administrateur système',
            'description' => "Chargé de l'administration et de la configuration de la plateforme.",
            'guard_name' => 'sanctum',
        ])->givePermissionTo([
            $createUser,
            $viewUser,
            $updateUser,
            $deleteUser,
            $enableUser,
            $disableUser,
            $resetUser,

            $viewInvoice,
            $generateInvoice,
            $invoiceStatistics,

            $viewPayment,
            $paymentStatistics,

            $createStatus,
            $viewStatus,
            $updateStatus,
            $deleteStatus,
            $enableStatus,
            $disableStatus,

            $createRole,
            $viewRole,
            $updateRole,
            $deleteRole,

            $createPermission,
            $viewPermission,
            $updatePermission,
            $deletePermission,

            $createEntity,
            $viewEntity,
            $updateEntity,
            $deleteEntity,
            $enableEntity,
            $disableEntity,

            $createEntityType,
            $viewEntityType,
            $updateEntityType,
            $deleteEntityType,
            $enableEntityType,
            $disableEntityType,

            $createVehicle,
            $viewVehicle,
            $updateVehicle,
            $deleteVehicle,

            $createVehicleGenre,
            $viewVehicleGenre,
            $updateVehicleGenre,
            $deleteVehicleGenre,
            $enableVehicleGenre,
            $disableVehicleGenre,

            $createVehicleEnergy,
            $viewVehicleEnergy,
            $updateVehicleEnergy,
            $deleteVehicleEnergy,
            $enableVehicleEnergy,
            $disableVehicleEnergy,

            $createVehicleAge,
            $viewVehicleAge,
            $updateVehicleAge,
            $deleteVehicleAge,
            $enableVehicleAge,
            $disableVehicleAge,

            $createVehicleModel,
            $viewVehicleModel,
            $updateVehicleModel,
            $deleteVehicleModel,
            $enableVehicleModel,
            $disableVehicleModel,

            $createBrand,
            $viewBrand,
            $updateBrand,
            $deleteBrand,
            $enableBrand,
            $disableBrand,

            $createColor,
            $viewColor,
            $updateColor,
            $deleteColor,
            $enableColor,
            $disableColor,

            $viewCheck,
            $updateCheck,
            $deleteCheck,

            $createBank,
            $viewBank,
            $updateBank,
            $deleteBank,
            $enableBank,
            $disableBank,

            $createDepreciationTable,
            $viewDepreciationTable,
            $updateDepreciationTable,
            $deleteDepreciationTable,
            $enableDepreciationTable,
            $disableDepreciationTable,

            $createPaymentType,
            $viewPaymentType,
            $updatePaymentType,
            $deletePaymentType,
            $enablePaymentType,
            $disablePaymentType,

            $createPaymentMethod,
            $viewPaymentMethod,
            $updatePaymentMethod,
            $deletePaymentMethod,
            $enablePaymentMethod,
            $disablePaymentMethod,

            $createClient,
            $viewClient,
            $updateClient,
            $deleteClient,
            $enableClient,
            $disableClient,

            $createQrCode,
            $viewQrCode,
            $updateQrCode,
            $deleteQrCode,
            $enableQrCode,
            $disableQrCode,

            $createUserAction,
            $viewUserAction,
            $updateUserAction,
            $deleteUserAction,

            $createUserActionType,
            $viewUserActionType,
            $updateUserActionType,
            $deleteUserActionType,
            $enableUserActionType,
            $disableUserActionType,

            $createFneSetting,
            $viewFneSetting,
            $updateFneSetting,
            $deleteFneSetting,
            $enableFneSetting,
            $disableFneSetting,

            $createUsage,
            $viewUsage,
            $updateUsage,
            $deleteUsage,
            $enableUsage,
            $disableUsage,

            $createVehicleCharacteristic,
            $viewVehicleCharacteristic,
            $updateVehicleCharacteristic,
            $deleteVehicleCharacteristic,
            $enableVehicleCharacteristic,
            $disableVehicleCharacteristic,

            $createDealer,
            $viewDealer,
            $updateDealer,
            $deleteDealer,
            $enableDealer,
            $disableDealer,

            $createCalculation,
            $viewCalculation,
            $updateCalculation,
            $deleteCalculation,
            $enableCalculation,
            $disableCalculation,

            $createTransactionType,
            $viewTransactionType,
            $updateTransactionType,
            $deleteTransactionType,
            $enableTransactionType,
            $disableTransactionType,

            $createTransaction,
            $viewTransaction,
            $updateTransaction,
            $deleteTransaction,
            $cancelTransaction,
            $validateTransaction,
            $rejectTransaction,

            $viewOrder,
            $validateOrder,
            $rejectOrder,

            $dashboard,
        ]);

        Role::create([
            'name' => \App\Enums\RoleEnum::ADMIN,
            'label' => 'Administrateur plateforme',
            'description' => 'Chargé de la gestion de la plateforme.',
            'guard_name' => 'sanctum',
        ])->givePermissionTo([
            $createUser,
            $viewUser,
            $updateUser,
            $deleteUser,
            $enableUser,
            $disableUser,
            $resetUser,

            $viewInvoice,
            $generateInvoice,
            $invoiceStatistics,

            $viewPayment,
            $paymentStatistics,

            $createStatus,
            $viewStatus,
            $updateStatus,
            $deleteStatus,
            $enableStatus,
            $disableStatus,

            $createRole,
            $viewRole,
            $updateRole,
            $deleteRole,

            $createPermission,
            $viewPermission,
            $updatePermission,
            $deletePermission,

            $createEntity,
            $viewEntity,
            $updateEntity,
            $deleteEntity,
            $enableEntity,
            $disableEntity,

            $createEntityType,
            $viewEntityType,
            $updateEntityType,
            $deleteEntityType,
            $enableEntityType,
            $disableEntityType,

            $createVehicle,
            $viewVehicle,
            $updateVehicle,
            $deleteVehicle,

            $createVehicleGenre,
            $viewVehicleGenre,
            $updateVehicleGenre,
            $deleteVehicleGenre,
            $enableVehicleGenre,
            $disableVehicleGenre,

            $createVehicleEnergy,
            $viewVehicleEnergy,
            $updateVehicleEnergy,
            $deleteVehicleEnergy,
            $enableVehicleEnergy,
            $disableVehicleEnergy,

            $createVehicleAge,
            $viewVehicleAge,
            $updateVehicleAge,
            $deleteVehicleAge,
            $enableVehicleAge,
            $disableVehicleAge,

            $createVehicleModel,
            $viewVehicleModel,
            $updateVehicleModel,
            $deleteVehicleModel,
            $enableVehicleModel,
            $disableVehicleModel,

            $createBrand,
            $viewBrand,
            $updateBrand,
            $deleteBrand,
            $enableBrand,
            $disableBrand,

            $createColor,
            $viewColor,
            $updateColor,
            $deleteColor,
            $enableColor,
            $disableColor,

            $viewCheck,
            $updateCheck,
            $deleteCheck,

            $createBank,
            $viewBank,
            $updateBank,
            $deleteBank,
            $enableBank,
            $disableBank,

            $createDepreciationTable,
            $viewDepreciationTable,
            $updateDepreciationTable,
            $deleteDepreciationTable,
            $enableDepreciationTable,
            $disableDepreciationTable,

            $createPaymentType,
            $viewPaymentType,
            $updatePaymentType,
            $deletePaymentType,
            $enablePaymentType,
            $disablePaymentType,

            $createPaymentMethod,
            $viewPaymentMethod,
            $updatePaymentMethod,
            $deletePaymentMethod,
            $enablePaymentMethod,
            $disablePaymentMethod,

            $createClient,
            $viewClient,
            $updateClient,
            $deleteClient,
            $enableClient,
            $disableClient,

            $createQrCode,
            $viewQrCode,
            $updateQrCode,
            $deleteQrCode,
            $enableQrCode,
            $disableQrCode,

            $createUserAction,
            $viewUserAction,
            $updateUserAction,
            $deleteUserAction,

            $createUserActionType,
            $viewUserActionType,
            $updateUserActionType,
            $deleteUserActionType,
            $enableUserActionType,
            $disableUserActionType,

            $createFneSetting,
            $viewFneSetting,
            $updateFneSetting,
            $deleteFneSetting,
            $enableFneSetting,
            $disableFneSetting,

            $createUsage,
            $viewUsage,
            $updateUsage,
            $deleteUsage,
            $enableUsage,
            $disableUsage,

            $createVehicleCharacteristic,
            $viewVehicleCharacteristic,
            $updateVehicleCharacteristic,
            $deleteVehicleCharacteristic,
            $enableVehicleCharacteristic,
            $disableVehicleCharacteristic,

            $createDealer,
            $viewDealer,
            $updateDealer,
            $deleteDealer,
            $enableDealer,
            $disableDealer,

            $createCalculation,
            $viewCalculation,
            $updateCalculation,
            $deleteCalculation,
            $enableCalculation,
            $disableCalculation,

            $createPrice,
            $viewPrice,
            $updatePrice,
            $deletePrice,
            $enablePrice,
            $disablePrice,

            $createTransactionType,
            $viewTransactionType,
            $updateTransactionType,
            $deleteTransactionType,
            $enableTransactionType,
            $disableTransactionType,

            $createTransaction,
            $viewTransaction,
            $updateTransaction,
            $deleteTransaction,
            $cancelTransaction,
            $validateTransaction,
            $rejectTransaction,

            $viewOrder,
            $validateOrder,
            $rejectOrder,

            $dashboard,
        ]);

        Role::create([
            'name' => \App\Enums\RoleEnum::ADMIN_ORGANIZATION,
            'label' => 'Administrateur d\'une organisation',
            'description' => 'Chargé de la gestion de la plateforme d\'une organisation.',
            'guard_name' => 'sanctum',
        ])->givePermissionTo([
            $createUser,
            $viewUser,
            $updateUser,
            $enableUser,
            $disableUser,
            $resetUser,

            $viewInvoice,
            $generateInvoice,
            $invoiceStatistics,

            $createStatus,
            $viewStatus,
            $updateStatus,
            $deleteStatus,
            $enableStatus,
            $disableStatus,

            $viewRole,

            $viewPermission,

            $viewEntity,

            $viewEntityType,

            $calculateDepreciationTable,

            $createPaymentType,
            $viewPaymentType,
            $updatePaymentType,
            $deletePaymentType,
            $enablePaymentType,
            $disablePaymentType,

            $createPaymentMethod,
            $viewPaymentMethod,
            $updatePaymentMethod,
            $deletePaymentMethod,
            $enablePaymentMethod,
            $disablePaymentMethod,

            $createClient,
            $viewClient,
            $updateClient,
            $deleteClient,
            $enableClient,
            $disableClient,

            $createQrCode,
            $viewQrCode,
            $updateQrCode,
            $deleteQrCode,
            $enableQrCode,
            $disableQrCode,

            $createUserAction,
            $viewUserAction,
            $updateUserAction,
            $deleteUserAction,

            $viewUserActionType,

            $createFneSetting,
            $viewFneSetting,
            $updateFneSetting,
            $enableFneSetting,
            $disableFneSetting,

            $createCalculation,
            $viewCalculation,
            $updateCalculation,

            $createOrder,
            $viewOrder,
            $updateOrder,
            $cancelOrder,

            $createTransaction,
            $viewTransaction,
            $updateTransaction,
            $deleteTransaction,
            $cancelTransaction,

            $createTransactionType,
            $viewTransactionType,
            $updateTransactionType,
            $deleteTransactionType,
            $enableTransactionType,
            $disableTransactionType,

            $dashboard,
        ]);
    }
}
