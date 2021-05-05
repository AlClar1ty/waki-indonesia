<?php
$menu_item_page = "user";
?>
@extends('admin.layouts.template')

@section('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<style type="text/css">
    .imagePreview {
        width: 100%;
        height: 150px;
        background-position: center center;
        background-color: #fff;
        background-size: cover;
        background-repeat: no-repeat;
        display: inline-block;
    }

    .div-CheckboxGroup {
        border:solid 1px rgba(128, 128, 128, 0.32941);
        padding:10px;
        border-radius:3px;
        }

    .del {
        position: absolute;
        top: 0px;
        right: 10px;
        width: 30px;
        height: 30px;
        text-align: center;
        line-height: 30px;
        background-color: rgba(255,255,255,0.6);
        cursor: pointer;
    }

    #intro {
        padding-top: 2em;
    }
    button{
        background: #1bb1dc;
        border: 0;
        border-radius: 3px;
        padding: 8px 30px;
        color: #fff;
        transition: 0.3s;
    }

    .validation{
        color: red;
        font-size: 9pt;
    }

    input, select, textarea{
        border-radius: 0 !important;
        box-shadow: none !important;
        border: 1px solid #dce1ec !important;
        font-size: 14px !important;
    }

    .select2-selection__rendered {
        line-height: 40px !important;
    }

    .select2-container .select2-selection--single {
        height: 40px !important;
    }

    .select2-selection__arrow {
        height: 40px !important;
    }
</style>
@endsection

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">Update Admin</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#admin-dd"
                            aria-expanded="false"
                            aria-controls="admin-dd">
                            Admin
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Update Admin
                    </li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form id="actionUpdate"
                            class="forms-sample"
                            action="{{ route('update_useradmin', ['id' => $users['id']]) }}"
                            method="POST">
                            @csrf
                            <?php $get_roleId = $role_users[0]->role_id; ?>
                            <div class="form-group">
                                <label for="username">USERNAME ADMIN</label>
                                <input type="text"
                                    class="form-control"
                                    name="username"
                                    id="username"
                                    value="{{ $users['username'] }}"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">ADMIN'S NAME</label>
                                <input type="text"
                                    class="form-control"
                                    value="{{ $users['name'] }}"
                                    name="name"
                                    id="name"
                                    required />
                                <span class="invalid-feedback">
                                    <strong></strong>
                                </span>
                            </div>

                            @if ($get_roleId == 3)
                                <!-- CSO -->
                                <div id="form-cso" class="form-group">
                                    <span>CSO</span>
                                    <select id="dropdown-cso"
                                        style="margin-top: 0.5em;"
                                        class="form-control"
                                        style="height: auto;"
                                        name="cso_id">
                                        <option value="">Choose CSO</option>
                                        @foreach ($csos as $cso)
                                            @if ($users['cso_id'] == $cso->id)
                                                <option value="{{ $cso->id }}"
                                                    selected>
                                                    {{ $cso->code }} - {{ $cso->name }}
                                                </option>
                                            @else
                                                <option value="{{ $cso->id }}">
                                                    {{ $cso->code }} - {{ $cso->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong></strong>
                                    </span>
                                </div>
                                <!-- End CSO -->
                            @elseif ($get_roleId == 5)
                                <?php
                                $getBranches = json_decode($users['branches_id'], true);
                                $total_branch = count($getBranches);
                                ?>

                                <input type="hidden"
                                    name="role_id"
                                    id="role_id"
                                    value="{{ $get_roleId }}" />
                                <input type="hidden"
                                    name="total_branch"
                                    id="total_branch"
                                    value="{{ $total_branch }}" />

                                <!-- Branch -->
                                <div id="form-branch" class="container-branch">
                                    @for ($i = 0; $i < $total_branch; $i++)
                                        <div id="branch_{{ $i }}" class="form-group"
                                            style="width: 90%; display: inline-block;">
                                            <span>BRANCH {{ $i + 1 }}</span>
                                            <select class="form-control"
                                                name="branch_{{ $i }}"
                                                data-msg="Please choose the Branch">
                                                <option selected
                                                    disabled
                                                    value="">
                                                    Choose Branch
                                                </option>
                                                @foreach ($branches as $branch)
                                                    @if ($getBranches[$i] == $branch->id)
                                                        <option value="{{ $branch->id }}"
                                                            selected>
                                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $branch->id }}">
                                                            {{ $branch['code'] }} - {{ $branch['name'] }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <div class="validation"></div>
                                        </div>

                                        @if ($i == 0)
                                            <div class="text-center"
                                                style="display: inline-block; float: right;">
                                                <button id="tambah_branch"
                                                    title="Add branch"
                                                    style="padding: 0.4em 0.7em;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="text-center"
                                                style="display: inline-block; float: right;">
                                                <button class="hapus_branch"
                                                    value="{{ $i }}"
                                                    title="Hapus Branch"
                                                    style="padding: 0.4em 0.7em; background-color: red;">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endfor
                                    <div id="tambahan_branch"></div>
                                </div>
                                <!-- End Branch -->
                            @endif

                            <?php
                            $get_birthdate = Carbon\Carbon::parse($users->birth_date)->format('Y-m-d');
                            ?>
                            <div class="form-group">
                                <label for="birth-date">
                                    ADMIN'S BIRTH DATE
                                </label>
                                <input type="date"
                                    class="form-control"
                                    value="{{ $get_birthdate }}"
                                    name="birth_date"
                                    id="birth-date"
                                    required />
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>PROFILE IMAGE (750x750 pixel)</label>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row productimg"
                                    style="border: 1px solid rgb(221, 221, 221, 0.5); border-radius: 4px; box-shadow: none; margin: 0;">
                                    <div class="col-xs-12 col-sm-6 imgUp"
                                        style="padding: 15px; float: left; text-align: center;">
                                        @if (!empty($users['user_image']))
                                            <div class="imagePreview"
                                                style="background-image: url({{ route('avatar_useradmin', ['id' => Auth::user()->user_image]) }});">
                                            </div>
                                        @else
                                            <div class="imagePreview"
                                                style="background-image: url({{ asset('sources/dashboard/no-img-banner.jpg') }});">
                                            </div>
                                        @endif
                                        <label class="file-upload-browse btn btn-gradient-primary"
                                            style="margin: 15px 0 0; text-align: center;"
                                            for="user-image">
                                            Upload
                                            <input name="user_image"
                                                type="file"
                                                accept=".jpg,.jpeg,.png"
                                                class="uploadFile img"
                                                value="Upload Photo"
                                                id="user-image"
                                                style="width: 0px;height: 0px; overflow: hidden;" />
                                        </label>
                                        <i class="mdi mdi-window-close del"></i>
                                    </div>
                                </div>
                            </div>

                            <h3 style="margin-top:10px; margin-bottom:10px; text-decoration: underline;">
                                PERMISSIONS
                            </h4>

                            <?php $permissions = $users['permissions']; ?>
                            <input type="hidden"
                                id="permissions"
                                value="{{ $permissions }}" />

                            <div class="form-group" id="group-product">
                                <span style="display:block;">DASHBOARD</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="show-dashboard">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="show-dashboard" />
                                            Show Dashboard
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">FRONT END CMS</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-frontendcms">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-frontendcms" />
                                            Browse Front End CMS
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">DELIVERY ORDER</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-deliveryorder">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-deliveryorder" />
                                            Add Delivery Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-deliveryorder">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-deliveryorder" />
                                            Browse Delivery Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-deliveryorder">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="detail-deliveryorder" />
                                            Detail Delivery Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-deliveryorder">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-deliveryorder" />
                                            Edit Delivery Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-deliveryorder">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-deliveryorder" />
                                            Delete Delivery Order
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">ORDER</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-order">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-order" />
                                            Add Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-order">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-order" />
                                            Browse Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-order">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="detail-order" />
                                            Detail Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-order">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-order" />
                                            Edit Order
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-order">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-order" />
                                            Delete Order
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">HOME SERVICE</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-home_service">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-home_service" />
                                            Add Home Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-home_service">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-home_service" />
                                            Browse Home Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-home_service">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="detail-home_service" />
                                            Detail Home Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-home_service">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-home_service" />
                                            Edit Home Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label" for="delete-home_service">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-home_service" />
                                            Delete Home Service
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">CSO</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-cso">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-cso" />
                                            Add CSO
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-cso">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-cso" />
                                            Browse CSO
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-cso">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-cso" />
                                            Edit CSO
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-cso">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-cso" />
                                            Delete CSO
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">BRANCH</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-branch">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-branch" />
                                            Add Branch
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-branch">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-branch" />
                                            Browse Branch
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-branch">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-branch" />
                                            Edit Branch
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-branch">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-branch" />
                                            Delete Branch
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">CATEGORY PRODUCT</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-category">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-category" />
                                            Add Category
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-category">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-category" />
                                            Browse Category
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-category">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-category" />
                                            Edit Category
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-category">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-category" />
                                            Delete Category
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">PRODUCT</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-product">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-product" />
                                            Add Product
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-product">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-product" />
                                            Browse Product
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-product">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-product" />
                                            Edit Product
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-product">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-product" />
                                            Delete Product
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">PROMO</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-promo">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-promo" />
                                            Add Promo
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-promo">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-promo" />
                                            Browse Promo
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-promo">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-promo" />
                                            Edit Promo
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-promo">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-promo" />
                                            Delete Promo
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display:block;">USER ADMIN</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-user">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="add-user" />
                                            Add User
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-user">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="browse-user" />
                                            Browse User
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-user">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="edit-user" />
                                            Edit User
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-user">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                id="delete-user" />
                                            Delete User
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display: block;">ACCEPTANCE</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="add-acceptance"
                                                value="add-acceptance" />
                                            Add Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="browse-acceptance"
                                                value="browse-acceptance" />
                                            Browse Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="detail-acceptance"
                                                value="detail-acceptance" />
                                            Detail Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="edit-acceptance"
                                                value="edit-acceptance" />
                                            Edit Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-approval-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-approval-acceptance"
                                                value="change-status-approval-acceptance" />
                                            Change Status Approval Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-complete-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-complete-acceptance"
                                                value="change-status-complete-acceptance" />
                                            Change Status Complete Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-reject-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-reject-acceptance"
                                                value="change-status-reject-acceptance" />
                                            Change Status Reject Acceptance
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-acceptance">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="delete-acceptance"
                                                value="delete-acceptance" />
                                            Delete Acceptance
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display: block;">UPGRADE</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="browse-upgrade"
                                                value="browse-upgrade" />
                                            Browse Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="detail-upgrade"
                                                value="detail-upgrade" />
                                            Detail Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="edit-upgrade"
                                                value="edit-upgrade" />
                                            Edit Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-approval-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-approval-upgrade"
                                                value="change-status-approval-upgrade" />
                                            Change Status Approval Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="-change-status-processupgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-process-upgrade"
                                                value="change-status-process-upgrade" />
                                            Change Status Process Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-complete-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-complete-upgrade"
                                                value="change-status-complete-upgrade" />
                                            Change Status Complete Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-reject-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-reject-upgrade"
                                                value="change-status-reject-upgrade" />
                                            Change Status Reject Upgrade
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-upgrade">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="delete-upgrade"
                                                value="delete-upgrade" />
                                            Delete Upgrade
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display: block;">SERVICE</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="add-service"
                                                value="add-service" />
                                            Add Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="browse-service"
                                                value="browse-service" />
                                            Browse Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="detail-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="detail-service"
                                                value="detail-service" />
                                            Detail Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="edit-service"
                                                value="edit-service" />
                                            Edit Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-process-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-process-service"
                                                value="change-status-process-service" />
                                            Change Status Process Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-repaired-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-repaired-service"
                                                value="change-status-repaired-service" />
                                            Change Status Repaired Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-qc-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-qc-service"
                                                value="change-status-qc-service" />
                                            Change Status QC Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-delivery-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-delivery-service"
                                                value="change-status-delivery-service" />
                                            Change Status Delivery Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="change-status-complete-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="change-status-complete-service"
                                                value="change-status-complete-service" />
                                            Change Status Complete Service
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-service">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="delete-service"
                                                value="delete-service" />
                                            Delete Service
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-product">
                                <span style="display: block;">SPAREPART</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-sparepart">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="add-sparepart"
                                                value="add-sparepart" />
                                            Add Sparepart
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-sparepart">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="browse-sparepart"
                                                value="browse-sparepart" />
                                            Browse Sparepart
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-sparepart">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="edit-sparepart"
                                                value="edit-sparepart" />
                                            Edit Sparepart
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-sparepart">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="delete-sparepart"
                                                value="delete-sparepart" />
                                            Delete Sparepart
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="group-submission">
                                <span style="display: block;">SUBMISSION</span>
                                <div class="div-CheckboxGroup">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="add-submission">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="add-submission"
                                                value="add-submission" />
                                            Add Submission
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="browse-submission">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="browse-submission"
                                                value="browse-submission" />
                                            Browse Submission
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="edit-submission">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="edit-submission"
                                                value="edit-submission" />
                                            Edit Submission
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label"
                                            for="delete-submission">
                                            <input type="checkbox"
                                                class="form-check-input"
                                                id="delete-submission"
                                                value="delete-submission" />
                                            Delete Submission
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden"
                                name="idUserAdmin"
                                id="idUserAdmin"
                                value="{{ $users['id'] }}" />
                            <button id="updateUserAdmin"
                                type="submit"
                                class="btn btn-gradient-primary mr-2">
                                Simpan
                            </button>
                            <button class="btn btn-light">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" defer></script>
<script type="application/javascript" src="{{ asset('js/admin/tags-input.js') }}"></script>
<script type="application/javascript">
    $(document).ready(function () {
        var getpermission = $('#permissions').val();
        var id = $('#idUserAdmin').val();
        var permissions = JSON.parse(getpermission);

        // List checkbox
        $(".form-check-input").each(function (e) {
            $(this)[0].checked = permissions[$(this)[0].id];
        });
    });
</script>
<script type="application/javascript">
    var branch = $('#total_branch').val();
    var val = $('#role_id').val();
    var total_branch = branch - 1;

    $(document).ready(function () {
        $("#dropdown-cso").select2();

        $('#tambah_branch').click(function (e) {
            e.preventDefault();
            total_branch++;
            branch++;
            if (val == 4) {
                if(total_branch <= 1) {
                    strIsi = "<div id=\"branch_" + total_branch + "\" class=\"form-group\" style=\"width: 90%; display: inline-block;\"><span>BRANCH " + branch + "</span><select class=\"form-control\" name=\"branch_" + total_branch + "\" data-msg=\"Please choose the Branch\"><option selected disabled value=\"\">Choose Branch</option>@foreach($branches as $branch)<option value=\"{{ $branch->id }}\">{{ $branch['code'] }} - {{ $branch['name'] }}</option>@endforeach</select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_branch\" value=\"" + total_branch + "\" title=\"Hapus Branch\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
                    $('#tambahan_branch').html($('#tambahan_branch').html() + strIsi);
                } else {
                    alert("Maksimum choice of Branch is 2");
                }
            } else if(val == 5) {
                if (total_branch <= 4) {
                    strIsi = "<div id=\"branch_" + total_branch + "\" class=\"form-group\" style=\"width: 90%; display: inline-block;\"><span>BRANCH " + branch + "</span><select class=\"form-control\" name=\"branch_" + total_branch + "\" data-msg=\"Please choose the Branch\"><option selected disabled value=\"\">Choose Branch</option>@foreach($branches as $branch)<option value=\"{{ $branch->id }}\">{{ $branch['code'] }} - {{ $branch['name'] }}</option>@endforeach</select><div class=\"validation\"></div></div><div class=\"text-center\" style=\"display: inline-block; float: right;\"><button class=\"hapus_branch\" value=\"" + total_branch + "\" title=\"Hapus Branch\" style=\"padding: 0.4em 0.7em; background-color: red;\"><i class=\"fas fa-minus\"></i></button></div>";
                    $('#tambahan_branch').html($('#tambahan_branch').html() + strIsi);
                } else {
                    alert("Maksimum choice of Branch is 5");
                }
            }
        });

        $(document).on("click",".hapus_branch", function (e) {
            e.preventDefault();
            total_branch--;
            branch--;
            $('#branch_'+$(this).val()).remove();
            $(this).remove();
        });

        var frmUpdate;
        $("#actionUpdate").on("submit", function (e) {
            e.preventDefault();
            frmUpdate = _("actionUpdate");
            frmUpdate = new FormData(document.getElementById("actionUpdate"));
            frmUpdate.enctype = "multipart/form-data";

            $(".form-check-input").each(function (e) {
                frmUpdate.append($(this)[0].id, $(this)[0].checked);
            });

            frmUpdate.append('total_branch', branch);

            var URLNya = $("#actionUpdate").attr('action');
            console.log(URLNya);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.open("POST", URLNya);
            ajax.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            ajax.send(frmUpdate);
        });

        function progressHandler(event) {
            document.getElementById("updateUserAdmin").innerHTML = "UPLOADING...";
        }

        function completeHandler(event) {
            var hasil = JSON.parse(event.target.responseText);

            for (var key of frmUpdate.keys()) {
                $("#actionUpdate").find("input[name=" + key.name + "]").removeClass("is-invalid");
                $("#actionUpdate").find("select[name=" + key.name + "]").removeClass("is-invalid");
                $("#actionUpdate").find("textarea[name=" + key.name + "]").removeClass("is-invalid");

                $("#actionUpdate").find("input[name=" + key.name + "]").next().find("strong").text("");
                $("#actionUpdate").find("select[name=" + key.name + "]").next().find("strong").text("");
                $("#actionUpdate").find("textarea[name=" + key.name + "]").next().find("strong").text("");
            }

            console.log(hasil);

            if(hasil['errors'] != null) {
                for (var key of frmUpdate.keys()) {
                    if(typeof hasil['errors'][key] === 'undefined') {

                    } else {
                        $("#actionUpdate").find("input[name=" + key + "]").addClass("is-invalid");
                        $("#actionUpdate").find("select[name=" + key + "]").addClass("is-invalid");
                        $("#actionUpdate").find("textarea[name=" + key + "]").addClass("is-invalid");

                        $("#actionUpdate").find("input[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("select[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                        $("#actionUpdate").find("textarea[name=" + key + "]").next().find("strong").text(hasil['errors'][key]);
                    }
                }

                alert("Input Error !!!");
            } else {
                alert("Input Success !!!");
                window.location.reload();
            }

            document.getElementById("updateUserAdmin").innerHTML = "SAVE";
        }

        function errorHandler(event) {
            document.getElementById("updateUserAdmin").innerHTML = "SAVE";
        }
    });
</script>
@endsection
