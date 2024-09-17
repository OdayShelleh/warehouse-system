<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImportRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use App\Models\Product;

class ImportCrudController extends CrudController
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
        $response = $this->traitStore();
        $this->updateProductStock($this->crud->entry->product_id, $this->crud->entry->quantity);
        return $response;
    }
    protected function updateProductStock($productId, $importedQuantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->quantity += $importedQuantity;
            $product->save();
        }
    }


    public function update()
    {
        $import = $this->crud->model::find($this->crud->entry->id);
        $oldQuantity = $import->quantity;

        // $response = parent::update();
        $response = $this->traitUpdate();

        $newQuantity = $this->crud->entry->quantity;
        $this->updateProductStockOnEdit($import->product_id, $oldQuantity, $newQuantity);

        return $response;
    }


    protected function updateProductStockOnEdit($productId, $oldQuantity, $newQuantity)
    {
        $product = Product::find($productId);
        if ($product) {
            $product->quantity += ($newQuantity - $oldQuantity);
            $product->save();
        }
    }


    public function destroy($id)
    {
        $import = $this->crud->model::find($id);

        // $response = parent::destroy($id);
        $response = $this->traitDestroy($id);

        $this->updateProductStock($import->product_id, -$import->quantity);

        return $response;
    }







    public function setup()
    {
        CRUD::setModel(\App\Models\Import::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/import');
        CRUD::setEntityNameStrings('import', 'imports');
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
            'name' => 'supplier_id',
            'label' => 'Supplier',
            'type' => 'select',
            'entity' => 'supplier',
            'model' => "App\Models\Supplier",
            'attribute' => 'name',
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ImportRequest::class);

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
            'name' => 'supplier_id',
            'label' => 'Supplier',
            'type' => 'select',
            'entity' => 'supplier',
            'model' => "App\Models\Supplier",
            'attribute' => 'name',
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
