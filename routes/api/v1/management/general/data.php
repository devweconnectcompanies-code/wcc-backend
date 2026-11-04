<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\v1\management\general\BillingController;
use App\Http\Controllers\v1\management\general\ClientController;
use App\Http\Controllers\v1\management\general\ConfigurationController;
use App\Http\Controllers\v1\management\general\DataController;
use App\Http\Controllers\v1\management\general\DTEController;
use App\Http\Controllers\v1\management\general\InfrastructureController;
use App\Http\Controllers\v1\management\general\InternetController;
use App\Http\Controllers\v1\management\general\ManagementController;
use App\Http\Controllers\v1\management\general\SupportsController;

Route::prefix('v1/general')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('estados', [DataController::class, 'generalStatus']);
        Route::get('states', [DataController::class, 'statesList']);
        Route::get('municipalities', [DataController::class, 'municipalitiesList']);
        Route::get('state/{id}/municipalities', [DataController::class, 'municipalitiesByState']);
        Route::get('districts', [DataController::class, 'districtsList']);
        Route::get('countries', [DataController::class, 'countriesList']);
        Route::get('countries/phones', [DataController::class, 'countriesWithCode']);
        Route::get('municipality/{id}/districts', [DataController::class, 'districtsByMunicipality']);
        Route::get('genders', [DataController::class, 'gendersList']);
        Route::get('marital', [DataController::class, 'maritalStatusList']);
        Route::get('branches', [DataController::class, 'branchesList']);
        Route::get('contract/status', [DataController::class, 'contractStatusList']);

        Route::group(['prefix' => 'management'], function () {

            Route::group(['prefix' => 'roles'], function () {
                Route::get('/', [ManagementController::class, 'rolesList']);
                Route::get('{id}/permissions', [ManagementController::class, 'permissionsByRoleId']);
            });

            Route::group(['prefix' => 'users'], function () {
                Route::get('list', [ManagementController::class, 'usersList']);
                Route::get('technicians', [ManagementController::class, 'technicianList']);
            });
            Route::get('permissions', [ManagementController::class, 'permissionsList']);
        });

        Route::group(['prefix' => 'billing'], function () {
            Route::get('activities', [BillingController::class, 'billingActivitiesList']);
            Route::get('documents', [BillingController::class, 'billingDocumentsList']);
            Route::get('statuses', [BillingController::class, 'statusesList']);
            Route::get('payment-methods', [BillingController::class, 'paymentMethodsList']);
            Route::get('discounts/list', [BillingController::class, 'discountList']);
        });

        Route::group(['prefix' => 'client'], function () {
            Route::get('types', [ClientController::class, 'clientTypesList']);
            Route::get('documents', [ClientController::class, 'personalDocumentsList']);
            Route::get('phones', [ClientController::class, 'phoneCategoriesList']);
            Route::get('references', [ClientController::class, 'referencesList']);
        });

        Route::group(['prefix' => 'infrastructure'], function () {
            Route::get('servers', [InfrastructureController::class, 'authServersList']);
            Route::get('types', [InfrastructureController::class, 'equipmentTypesList']);
            Route::get('brands', [InfrastructureController::class, 'equipmentBrandsList']);
            Route::get('status', [InfrastructureController::class, 'equipmentStatusList']);
            Route::get('nodes', [InfrastructureController::class, 'nodesList']);
            Route::get('models', [InfrastructureController::class, 'modelsList']);
            Route::get('brand/{id}/models', [InfrastructureController::class, 'modelsByBrand']);
            Route::get('node/{id}/equipment', [InfrastructureController::class, 'equipmentByNode']);
        });

        Route::group(['prefix' => 'configuration'], function () {
            Route::get('menu', [ConfigurationController::class, 'menuList']);
        });

        Route::group(['prefix' => 'profiles'], function () {
            Route::group(['prefix' => 'select'], function () {
                Route::get('internet', [InternetController::class, 'internetPlansList']);
                Route::get('iptv', [InternetController::class, 'iptvPlansList']);
            });
            Route::get('internet', [InternetController::class, 'internetProfilesList']);
            Route::get('mikrotik', [InternetController::class, 'mikrotikProfilesList']);
        });

        Route::group(['prefix' => 'supports'], function () {
            Route::get('status', [SupportsController::class, 'supports_status']);
            Route::get('types', [SupportsController::class, 'supports_types']);
        });

        Route::group(['prefix' => 'dte'], function () {
            Route::get('status', [DTEController::class, 'status']);
        });
    });
