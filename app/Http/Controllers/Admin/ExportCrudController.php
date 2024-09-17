<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ExportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Product;
use Prologue\Alerts\Facades\Alert;

class ExportCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation {
        update as traitUpdate;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation {
        destroy as traitDestroy;
    }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;



    public function store()
    {

        $product = Product::find($this->crud->getRequest()->product_id);
        $requestedQuantity = $this->crud->getRequest()->quantity;

        if ($product->quantity < $requestedQuantity) {
            Alert::error("Not enough stock available for this product. we only have " . $product->quantity)->flash();
            return back();
        }
        $response = $this->traitStore();
        $this->updateProductStock($this->crud->entry->product_id, -$this->crud->entry->quantity);

        return $response;
    }

    protected function updateProductStock($productId, $exportedQuantity)
    {

        $product = Product::find($productId);
        if ($product) {
            $product->quantity -= $exportedQuantity;
            $product->save();
        }
    }

    public function update()
    {

        $export = $this->crud->model::find($this->crud->entry->id);
        $oldQuantity = $export->quantity;
        $response = $this->traitUpdate();
        $newQuantity = $this->crud->entry->quantity;
        $this->updateProductStockOnEdit($export->product_id, $oldQuantity, $newQuantity);

        return $response;
    }

    protected function updateProductStockOnEdit($productId, $oldQuantity, $newQuantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->quantity -= ($newQuantity - $oldQuantity);
            $product->save();
        }
    }



    public function destroy($id)
    {
        $export = $this->crud->model::find($id);

        $response = $this->traitDestroy($id);

        $this->updateProductStock($export->product_id, $export->quantity);

        return $response;
    }




    public function setup()
    {
        CRUD::setModel(\App\Models\Export::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/export');
        CRUD::setEntityNameStrings('export', 'exports');
    }



    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.
        CRUD::addColumn([
            'name' => 'product_id',
            'label' => 'Product',
            'type' => 'select',
            'entity' => 'product',
            'model' => "App\Models\Product",
            'attribute' => 'name',
        ]);

        CRUD::addColumn([
            'name' => 'quantity',
            'label' => 'Quantity',
            'type' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'date',
            'label' => 'Date',
            'type' => 'date',
        ]);

        CRUD::addColumn([
            'name' => 'customer_id',
            'label' => 'Customer',
            'type' => 'select',
            'entity' => 'customer',
            'model' => "App\Models\Customer",
            'attribute' => 'name',
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ExportRequest::class);

        CRUD::addField([
            'name' => 'product_id',
            'label' => 'Product',
            'type' => 'select',
            'entity' => 'product',
            'model' => "App\Models\Product",
            'attribute' => 'name',
        ]);

        CRUD::addField([
            'name' => 'quantity',
            'label' => 'Quantity',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'date',
            'label' => 'Date',
            'type' => 'date',
        ]);

        CRUD::addField([
            'name' => 'customer_id',
            'label' => 'Customer',
            'type' => 'select',
            'entity' => 'customer',
            'model' => "App\Models\Customer",
            'attribute' => 'name',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
