{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Categories" icon="la la-tags" :link="backpack_url('category')" />
<x-backpack::menu-item title="Products" icon="la la-box" :link="backpack_url('product')" />
<x-backpack::menu-item title="Customers" icon="la la-users" :link="backpack_url('customer')" />
<x-backpack::menu-item title="Suppliers" icon="la la-truck" :link="backpack_url('supplier')" />
<x-backpack::menu-item title="Imports" icon="la la-arrow-down" :link="backpack_url('import')" />
<x-backpack::menu-item title="Exports" icon="la la-arrow-up" :link="backpack_url('export')" />
