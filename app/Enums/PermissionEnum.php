<?php

namespace App\Enums;

use App\Concerns\UsefulEnums;

enum PermissionEnum: string
{
    use UsefulEnums;

    case VIEW_USER = 'user.view';
    case CREATE_USER = 'user.create';
    case UPDATE_USER = 'user.update';
    case DELETE_USER = 'user.delete';
    case ENABLE_USER = 'user.enable';
    case DISABLE_USER = 'user.disable';
    case RESET_USER = 'user.reset';

    case VIEW_INVOICE = 'invoice.view';
    case CREATE_INVOICE = 'invoice.create';
    case UPDATE_INVOICE = 'invoice.update';
    case CANCEL_INVOICE = 'invoice.cancel';
    case GENERATE_INVOICE = 'invoice.generate';
    case DELETE_INVOICE = 'invoice.delete';
    case INVOICE_STATISTICS = 'invoice.statistics';

    case VIEW_PAYMENT = 'payment.view';
    case CREATE_PAYMENT = 'payment.create';
    case UPDATE_PAYMENT = 'payment.update';
    case CANCEL_PAYMENT = 'payment.cancel';
    case DELETE_PAYMENT = 'payment.delete';
    case PAYMENT_STATISTICS = 'payment.statistics';

    case VIEW_STATUS = 'status.view';
    case CREATE_STATUS = 'status.create';
    case UPDATE_STATUS = 'status.update';
    case DELETE_STATUS = 'status.delete';
    case ENABLE_STATUS = 'status.enable';
    case DISABLE_STATUS = 'status.disable';

    case VIEW_ROLE = 'role.view';
    case CREATE_ROLE = 'role.create';
    case UPDATE_ROLE = 'role.update';
    case DELETE_ROLE = 'role.delete';

    case VIEW_PERMISSION = 'permission.view';
    case CREATE_PERMISSION = 'permission.create';
    case UPDATE_PERMISSION = 'permission.update';
    case DELETE_PERMISSION = 'permission.delete';

    case VIEW_ENTITY = 'entity.view';
    case CREATE_ENTITY = 'entity.create';
    case UPDATE_ENTITY = 'entity.update';
    case DELETE_ENTITY = 'entity.delete';
    case ENABLE_ENTITY = 'entity.enable';
    case DISABLE_ENTITY = 'entity.disable';

    case VIEW_ENTITY_TYPE = 'entity_type.view';
    case CREATE_ENTITY_TYPE = 'entity_type.create';
    case UPDATE_ENTITY_TYPE = 'entity_type.update';
    case DELETE_ENTITY_TYPE = 'entity_type.delete';
    case ENABLE_ENTITY_TYPE = 'entity_type.enable';
    case DISABLE_ENTITY_TYPE = 'entity_type.disable';
    
    case VIEW_VEHICLE = 'vehicle.view';
    case CREATE_VEHICLE = 'vehicle.create';
    case UPDATE_VEHICLE = 'vehicle.update';
    case DELETE_VEHICLE = 'vehicle.delete';

    case VIEW_VEHICLE_MODEL = 'vehicle_model.view';
    case CREATE_VEHICLE_MODEL = 'vehicle_model.create';
    case UPDATE_VEHICLE_MODEL = 'vehicle_model.update';
    case DELETE_VEHICLE_MODEL = 'vehicle_model.delete';
    case ENABLE_VEHICLE_MODEL = 'vehicle_model.enable';
    case DISABLE_VEHICLE_MODEL = 'vehicle_model.disable';

    case VIEW_BRAND = 'brand.view';
    case CREATE_BRAND = 'brand.create';
    case UPDATE_BRAND = 'brand.update';
    case DELETE_BRAND = 'brand.delete';
    case ENABLE_BRAND = 'brand.enable';
    case DISABLE_BRAND = 'brand.disable';

    case VIEW_COLOR = 'color.view';
    case CREATE_COLOR = 'color.create';
    case UPDATE_COLOR = 'color.update';
    case DELETE_COLOR = 'color.delete';
    case ENABLE_COLOR = 'color.enable';
    case DISABLE_COLOR = 'color.disable';

    case VIEW_DEPRECIATION_TABLE = 'depreciation_table.view';
    case CREATE_DEPRECIATION_TABLE = 'depreciation_table.create';
    case UPDATE_DEPRECIATION_TABLE = 'depreciation_table.update';
    case DELETE_DEPRECIATION_TABLE = 'depreciation_table.delete';
    case ENABLE_DEPRECIATION_TABLE = 'depreciation_table.enable';
    case DISABLE_DEPRECIATION_TABLE = 'depreciation_table.disable';
    case CALCULATE_DEPRECIATION_TABLE = 'depreciation_table.calculate';

    case VIEW_VEHICLE_AGE = 'vehicle_age.view';
    case CREATE_VEHICLE_AGE = 'vehicle_age.create';
    case UPDATE_VEHICLE_AGE = 'vehicle_age.update';
    case DELETE_VEHICLE_AGE = 'vehicle_age.delete';
    case ENABLE_VEHICLE_AGE = 'vehicle_age.enable';
    case DISABLE_VEHICLE_AGE = 'vehicle_age.disable';

    case VIEW_VEHICLE_ENERGY = 'vehicle_energy.view';
    case CREATE_VEHICLE_ENERGY = 'vehicle_energy.create';
    case UPDATE_VEHICLE_ENERGY = 'vehicle_energy.update';
    case DELETE_VEHICLE_ENERGY = 'vehicle_energy.delete';
    case ENABLE_VEHICLE_ENERGY = 'vehicle_energy.enable';
    case DISABLE_VEHICLE_ENERGY = 'vehicle_energy.disable';

    case VIEW_VEHICLE_GENRE = 'vehicle_genre.view';
    case CREATE_VEHICLE_GENRE = 'vehicle_genre.create';
    case UPDATE_VEHICLE_GENRE = 'vehicle_genre.update';
    case DELETE_VEHICLE_GENRE = 'vehicle_genre.delete';
    case ENABLE_VEHICLE_GENRE = 'vehicle_genre.enable';
    case DISABLE_VEHICLE_GENRE = 'vehicle_genre.disable';

    case VIEW_BODYWORK = 'bodywork.view';
    case CREATE_BODYWORK = 'bodywork.create';
    case UPDATE_BODYWORK = 'bodywork.update';
    case DELETE_BODYWORK = 'bodywork.delete';
    case ENABLE_BODYWORK = 'bodywork.enable';
    case DISABLE_BODYWORK = 'bodywork.disable';

    case VIEW_CHECK = 'check.view';
    case CREATE_CHECK = 'check.create';
    case UPDATE_CHECK = 'check.update';
    case DELETE_CHECK = 'check.delete';

    case VIEW_BANK = 'bank.view';
    case CREATE_BANK = 'bank.create';
    case UPDATE_BANK = 'bank.update';
    case DELETE_BANK = 'bank.delete';
    case ENABLE_BANK = 'bank.enable';
    case DISABLE_BANK = 'bank.disable';

    case VIEW_PAYMENT_TYPE = 'payment_type.view';
    case CREATE_PAYMENT_TYPE = 'payment_type.create';
    case UPDATE_PAYMENT_TYPE = 'payment_type.update';
    case DELETE_PAYMENT_TYPE = 'payment_type.delete';
    case ENABLE_PAYMENT_TYPE = 'payment_type.enable';
    case DISABLE_PAYMENT_TYPE = 'payment_type.disable';

    case VIEW_PAYMENT_METHOD = 'payment_method.view';
    case CREATE_PAYMENT_METHOD = 'payment_method.create';
    case UPDATE_PAYMENT_METHOD = 'payment_method.update';
    case DELETE_PAYMENT_METHOD = 'payment_method.delete';
    case ENABLE_PAYMENT_METHOD = 'payment_method.enable';
    case DISABLE_PAYMENT_METHOD = 'payment_method.disable';

    case VIEW_CLIENT = 'client.view';
    case CREATE_CLIENT = 'client.create';
    case UPDATE_CLIENT = 'client.update';
    case DELETE_CLIENT = 'client.delete';
    case ENABLE_CLIENT = 'client.enable';
    case DISABLE_CLIENT = 'client.disable';

    case VIEW_QR_CODE = 'qr_code.view';
    case CREATE_QR_CODE = 'qr_code.create';
    case UPDATE_QR_CODE = 'qr_code.update';
    case DELETE_QR_CODE = 'qr_code.delete';
    case ENABLE_QR_CODE = 'qr_code.enable';
    case DISABLE_QR_CODE = 'qr_code.disable';

    case VIEW_USER_ACTION = 'user_action.view';
    case CREATE_USER_ACTION = 'user_action.create';
    case UPDATE_USER_ACTION = 'user_action.update';
    case DELETE_USER_ACTION = 'user_action.delete';

    case VIEW_USER_ACTION_TYPE = 'user_action_type.view';
    case CREATE_USER_ACTION_TYPE = 'user_action_type.create';
    case UPDATE_USER_ACTION_TYPE = 'user_action_type.update';
    case DELETE_USER_ACTION_TYPE = 'user_action_type.delete';
    case ENABLE_USER_ACTION_TYPE = 'user_action_type.enable';
    case DISABLE_USER_ACTION_TYPE = 'user_action_type.disable';

    case VIEW_FNE_SETTING = 'fne_setting.view';
    case CREATE_FNE_SETTING = 'fne_setting.create';
    case UPDATE_FNE_SETTING = 'fne_setting.update';
    case DELETE_FNE_SETTING = 'fne_setting.delete';
    case ENABLE_FNE_SETTING = 'fne_setting.enable';
    case DISABLE_FNE_SETTING = 'fne_setting.disable';


    case VIEW_USAGE = 'usage.view';
    case CREATE_USAGE = 'usage.create';
    case UPDATE_USAGE = 'usage.update';
    case DELETE_USAGE = 'usage.delete';
    case ENABLE_USAGE = 'usage.enable';
    case DISABLE_USAGE = 'usage.disable';

    case VIEW_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.view';
    case CREATE_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.create';
    case UPDATE_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.update';
    case DELETE_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.delete';
    case ENABLE_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.enable';
    case DISABLE_VEHICLE_CHARACTERISTIC = 'vehicle_characteristic.disable';

    case VIEW_DEALER = 'dealer.view';
    case CREATE_DEALER = 'dealer.create';
    case UPDATE_DEALER = 'dealer.update';
    case DELETE_DEALER = 'dealer.delete';
    case ENABLE_DEALER = 'dealer.enable';
    case DISABLE_DEALER = 'dealer.disable';

    case VIEW_CALCULATION = 'calculation.view';
    case CREATE_CALCULATION = 'calculation.create';
    case UPDATE_CALCULATION = 'calculation.update';
    case DELETE_CALCULATION = 'calculation.delete';
    case ENABLE_CALCULATION = 'calculation.enable';
    case DISABLE_CALCULATION = 'calculation.disable';

    case VIEW_PRICE = 'price.view';
    case CREATE_PRICE = 'price.create';
    case UPDATE_PRICE = 'price.update';
    case DELETE_PRICE = 'price.delete';
    case ENABLE_PRICE = 'price.enable';
    case DISABLE_PRICE = 'price.disable';

    case VIEW_TRANSACTION_TYPE = 'transaction_type.view';
    case CREATE_TRANSACTION_TYPE = 'transaction_type.create';
    case UPDATE_TRANSACTION_TYPE = 'transaction_type.update';
    case DELETE_TRANSACTION_TYPE = 'transaction_type.delete';
    case ENABLE_TRANSACTION_TYPE = 'transaction_type.enable';
    case DISABLE_TRANSACTION_TYPE = 'transaction_type.disable';
    
    case VIEW_TRANSACTION = 'transaction.view';
    case CREATE_TRANSACTION = 'transaction.create';
    case UPDATE_TRANSACTION = 'transaction.update';
    case DELETE_TRANSACTION = 'transaction.delete';
    case CANCEL_TRANSACTION = 'transaction.cancel';
    case VALIDATE_TRANSACTION = 'transaction.validate';
    case REJECT_TRANSACTION = 'transaction.reject';

    case VIEW_ORDER = 'order.view';
    case CREATE_ORDER = 'order.create';
    case UPDATE_ORDER = 'order.update';
    case VALIDATE_ORDER = 'order.validate';
    case CANCEL_ORDER = 'order.cancel';
    case REJECT_ORDER = 'order.reject';

    case DASHBOARD = 'dashboard.view';
}
