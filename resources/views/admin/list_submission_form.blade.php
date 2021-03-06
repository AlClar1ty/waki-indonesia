<?php
$menu_item_page = "submission";
$menu_item_second = "list_submission_form";
?>
@extends('admin.layouts.template')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">List Submmission</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a data-toggle="collapse"
                            href="#"
                            aria-expanded="false"
                            aria-controls="deliveryorder-dd">
                            Submmission
                        </a>
                    </li>
                    <li class="breadcrumb-item active"
                        aria-current="page">
                        List Submmission
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-12 grid-margin stretch-card" style="padding: 0;">
            <div class="card">
                <div class="card-body">
                    <h5 style="margin-bottom: 0.5em;">
                        Total: {{ $deliveryOrders->count() }} data
                    </h5>
                    <div class="table-responsive"
                        style="border: 1px solid #ebedf2;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Registration Date</th>
                                    <th>Member Name</th>
                                    <th>Type Register</th>
                                    <th>Branch</th>
                                    <th>CSO</th>
                                    @if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder'))
                                        <th colspan="2">Edit / Delete</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deliveryOrders as $key => $deliveryOrder)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ date("d/m/Y", strtotime($deliveryOrder->created_at)) }}
                                        </td>
                                        <td>
                                            {{ $deliveryOrder->name }}
                                        </td>
                                        <td>
                                            {{ $deliveryOrder->type_register }}
                                        </td>
                                        <td>
                                            {{ $deliveryOrder->branch->code }} - {{ $deliveryOrder->branch->name }}
                                        </td>
                                        <td>
                                            {{ $deliveryOrder->cso->code }} - {{ $deliveryOrder->cso->name }}
                                        </td>
                                        @can('edit-deliveryorder')
                                            <td style="text-align: center;">
                                                <a href="{{ route('edit_submission_form', ['id' => $deliveryOrder->id]) }}">
                                                    <i class="mdi mdi-border-color" style="font-size: 24px; color: #fed713;"></i>
                                                </a>
                                            </td>
                                        @endcan
                                        @can('delete-deliveryorder')
                                            <td style="text-align: center;">
                                                <button class="btn-delete"
                                                    data-toggle="modal"
                                                    data-target="#deleteDoModal"
                                                    value="{{ route('delete_submission_form', ['id' => $deliveryOrder->id]) }}">
                                                    <i class="mdi mdi-delete" style="font-size: 24px; color: #fe7c96;"></i>
                                                </button>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        {{ $deliveryOrders->appends($url)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- partial -->

<!-- Modal Delete -->
<div class="modal fade"
    id="deleteDoModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 style="text-align:center;">
                    Are you sure you want to delete this?
                </h5>
            </div>
            <div class="modal-footer">
                <form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-gradient-danger mr-2">
                        Yes
                    </button>
                </form>
                <button class="btn btn-light">No</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Delete -->
@endsection

@section('script')
<script>
$(document).ready(function (e) {
    $(".btn-delete").click(function (e) {
        $("#frmDelete").attr("action",  $(this).val());
    });
});
</script>
@endsection
