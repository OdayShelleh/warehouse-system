<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \App\Http\Controllers\Admin\Operations\DisableProductOperation;





    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Product Name',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'code',
            'label' => 'Product Code',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'image',
            'label' => 'Product Image',
            'type' => 'image',
        ]);

        CRUD::addColumn([
            'name' => 'quantity',
            'label' => 'Quantity',
            'type' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'min_quantity',
            'label' => 'Min Quantity',
            'type' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'is_active',
            'label' => 'Is active',
            'type' => 'boolean',

        ]);

        CRUD::addColumn([
            'name' => 'price',
            'label' => 'Price',
            'type' => 'number',
            'prefix' => '$',
        ]);


        CRUD::addColumn([
            'name' => 'category_id',
            'label' => 'Category',
            'type' => 'select',
            'entity' => 'category',
            'model' => "App\Models\Category",
            'attribute' => 'name',
            'linkTo' => 'category.show'
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Product Name',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'code',
            'label' => 'Product Code',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'min_quantity',
            'label' => 'Minimum Quantity',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'quantity',
            'label' => 'Quantity',
            'type' => 'number',
        ]);

        CRUD::addField([
            'name' => 'price',
            'label' => 'Price',
            'type' => 'number',
            'prefix' => '$',
        ]);

        CRUD::addField([
            'name' => 'image',
            'label' => 'Product Image',
            'type' => 'url',
            'upload' => true,

        ]);

        CRUD::addField([
            'name' => 'category_id',
            'label' => 'Category',
            'type' => 'select',
            'entity' => 'category',
            'model' => "App\Models\Category",
            'attribute' => 'name',
        ]);


        CRUD::addField([
            'name' => 'is_active',
            'label' => 'Is Active',
            'type' => 'checkbox'
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
