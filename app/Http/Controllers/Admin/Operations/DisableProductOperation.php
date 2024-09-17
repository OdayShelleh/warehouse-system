<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait DisableProductOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupDisableProductRoutes($segment, $routeName, $controller)
    {
        Route::get($segment . '/disable-product', [
            'as'        => $routeName . '.disableProduct',
            'uses'      => $controller . '@disableProduct',
            'operation' => 'disableProduct',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupDisableProductDefaults()
    {
        CRUD::allowAccess('disableProduct');

        CRUD::operation('disableProduct', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'disable_product', 'view', 'crud::buttons.disable_product');
            // CRUD::addButton('line', 'disable_product', 'view', 'crud::buttons.disable_product');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function disableProduct($id)
    {
        CRUD::hasAccessOrFail('disableProduct');

        $product = \App\Models\Product::find($id);

        if ($product) {
            $product->is_active = false; // تعطيل المنتج
            $product->save();

            \Alert::success('Product disabled successfully.')->flash();
        } else {
            \Alert::error('Product not found.')->flash();
        }

        return true;
        // return redirect()->back();

        // // prepare the fields you need to show
        // $this->data['crud'] = $this->crud;
        // $this->data['title'] = CRUD::getTitle() ?? 'Disable Product ' . $this->crud->entity_name;

        // // load the view
        // return view('crud::operations.disable_product', $this->data);
    }
}
