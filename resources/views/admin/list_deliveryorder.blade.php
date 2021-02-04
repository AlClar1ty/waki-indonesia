<?php
    $menu_item_page = "deliveryorder";
    $menu_item_second = "list_deliveryorder";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
  			<h3 class="page-title">List Registration</h3>
  			<nav aria-label="breadcrumb">
    			<ol class="breadcrumb">
      				<li class="breadcrumb-item"><a data-toggle="collapse" href="#" aria-expanded="false" aria-controls="deliveryorder-dd">Registration</a></li>
      				<li class="breadcrumb-item active" aria-current="page">List Registration</li>
    			</ol>
  			</nav>
		</div>

			<div class="col-12 grid-margin" style="padding: 0;">
			@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso')
			@if(Utils::$lang=='id')
      <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
    		<div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
    			<div class="form-group">
    				<label for="">Filter By City</label>
    					<select class="form-control" id="filter_province" name="filter_province">
    						<option value="" selected="">All Province</option>
    						@php
    						$result = RajaOngkir::FetchProvince();
    						$result = $result['rajaongkir']['results'];
    						$arrProvince = [];
    						if(sizeof($result) > 0){
    							foreach ($result as $value) {
    								echo "<option value=\"". $value['province_id']."\">".$value['province']."</option>";
    							}
    						}
    						@endphp
    						</select>
    					<div class="validation"></div>
    				</div>
        </div>
    		<div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
    			<div class="form-group">
    			<label style="opacity: 0;" for=""> s</label>
    				<select class="form-control" id="filter_city" name="filter_city">
    				<option value="">All City</option>
    				@if(isset($_GET['filter_city']))
    					<option selected="" value="{{$_GET['filter_city']}}">{{$_GET['filter_city']}}</option>
    				@endif
    				</select>
    				<div class="validation"></div>
    			</div>
    		</div>
    		<div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
    			<div class="form-group">
    			<label style="opacity: 0;" for=""> s</label>
    				<select class="form-control" id="filter_district" name="filter_district">
    				<option value="">All District</option>
    				@if(isset($_GET['filter_district']))
    					<option selected="" value="{{$_GET['filter_district']}}">{{$_GET['filter_district']}}</option>
    				@endif
    				</select>
    				<div class="validation"></div>
    			</div>
    		</div>
      </div>
			@endif


      <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
        <div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
          <div class="form-group">
            <label for="">Filter By Type Register</label>
            <select class="form-control" id="filter_type_register" name="filter_type_register">
              <option value="" selected="">All Type</option>
              <option {{ isset($_GET['filter_type_register']) ? ($_GET['filter_type_register'] == "Normal Register" ? "selected" : "") : "" }} value="Normal Register">Normal Register</option>
              <option {{ isset($_GET['filter_type_register']) ? ($_GET['filter_type_register'] == "MGM" ? "selected" : "") : "" }} value="MGM">MGM</option>
              <option {{ isset($_GET['filter_type_register']) ? ($_GET['filter_type_register'] == "Refrensi" ? "selected" : "") : "" }} value="Refrensi">Refrensi</option>
              <option {{ isset($_GET['filter_type_register']) ? ($_GET['filter_type_register'] == "Take Away" ? "selected" : "") : "" }} value="Take Away">Take Away</option>
            </select>
            <div class="validation"></div>
          </div>
        </div>
				<div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<label for="">Filter By Team</label>
						<select class="form-control" id="filter_branch" name="filter_branch">
							<option value="" selected="">All Branch</option>
							@foreach($branches as $branch)
							@php
								$selected = "";
								if(isset($_GET['filter_branch'])){
								if($_GET['filter_branch'] == $branch['id']){
									$selected = "selected=\"\"";
								}
								}
							@endphp

							<option {{$selected}} value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
							@endforeach
						</select>
						<div class="validation"></div>
					</div>
				</div>
				<div class="col-xs-6 col-sm-4" style="padding: 0;display: inline-block;">
					<div class="form-group">
						<label for="">Filter By CSO</label>
						<select class="form-control" id="filter_cso" name="filter_cso">
							<option value="">All CSO</option>
							@php
							if(isset($_GET['filter_branch'])){
								$csos = App\Cso::Where('branch_id', $_GET['filter_branch'])->where('active', true)->get();

								foreach ($csos as $cso) {
								if(isset($_GET['filter_cso'])){
									if($_GET['filter_cso'] == $cso['id']){
									echo "<option selected=\"\" value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
									continue;
									}
								}
								echo "<option value=\"".$cso['id']."\">".$cso['code']." - ".$cso['name']."</option>";
								}
							}
							@endphp
						</select>
						<div class="validation"></div>
					</div>
				</div>
			</div>
			@endif

			@if(Auth::user()->roles[0]['slug'] != 'branch' && Auth::user()->roles[0]['slug'] != 'cso' && Auth::user()->roles[0]['slug'] != 'area-manager')
  		  <div class="col-xs-12 col-sm-12 row" style="margin: 0;padding: 0;">
  			<div class="col-xs-6 col-sm-6" style="padding: 0;display: inline-block;">
  				<label for=""></label>
  				<div class="form-group">
  				<button id="btn-filter" type="button" class="btn btn-gradient-primary m-1" name="filter" value="-"><span class="mdi mdi-filter"></span> Apply Filter</button>
  			  </div>
  			</div>
  		  </div>
			@endif

				<div class="col-sm-12 col-md-12" style="padding: 0; border: 1px solid #ebedf2;">
					<div class="col-xs-12 col-sm-11 col-md-6 table-responsive" id="calendarContainer" style="padding: 0; float: left;"></div>
					<div class="col-xs-12 col-sm-11 col-md-6" id="organizerContainer" style="padding: 0; float: left;"></div>
				</div>
			</div>

  		<div class="col-12 grid-margin stretch-card" style="padding: 0;">
    		<div class="card">
      		<div class="card-body">
      			<h5 style="margin-bottom: 0.5em;">Total : {{ $countDeliveryOrders }} data</h5>
        		  <div class="table-responsive" style="border: 1px solid #ebedf2;">
        				<table class="table table-bordered">
      						<thead>
				            <tr>
				              	<th> No. </th>
				              	<th> Registration Code </th>
				              	<th> Registration Date </th>
				              	<th> Member Name </th>
				              	<th> Type Register </th>
				              	<th> Branch </th>
				              	<th> CSO </th>
				              	@if(Gate::check('edit-deliveryorder') || Gate::check('delete-deliveryorder'))
					              	<th colspan="2"> Edit / Delete </th>
					            @endif
				            </tr>
      						</thead>
      						<tbody>
      							@foreach($deliveryOrders as $key => $deliveryOrder)
  	                        <tr>
  	                        	<td>{{$key+1}}</td>
  	                            <td><a href="{{ route('detail_deliveryorder') }}?code={{ $deliveryOrder['code'] }}">{{ $deliveryOrder['code'] }}</a></td>
  	                            <td>{{ date("d/m/Y", strtotime($deliveryOrder['created_at'])) }}</td>
  	                            <td>{{ $deliveryOrder['name'] }}</td>
                                <td>{{ $deliveryOrder['type_register'] }}</td>
  	                            <td>{{ $deliveryOrder->branch['code'] }} - {{ $deliveryOrder->branch['name'] }}</td>
  	                            <td>{{ $deliveryOrder->cso['code'] }} - {{ $deliveryOrder->cso['name'] }}</td>
  	                            @can('edit-deliveryorder')
  		                            <td style="text-align: center;"><a href="{{ route('edit_deliveryorder', ['id' => $deliveryOrder['id']])}}"><i class="mdi mdi-border-color" style="font-size: 24px; color:#fed713;"></i></a></td>
  	                            @endcan
  	                            @can('delete-deliveryorder')
                        					<td style="text-align: center;"><button value="{{ route('delete_deliveryorder', ['id' => $deliveryOrder['id']])}}" data-toggle="modal" data-target="#deleteDoModal" class="btn-delete"><i class="mdi mdi-delete" style="font-size: 24px; color:#fe7c96;"></i></button></td>
                        				@endcan
  	                        </tr>
  	                    @endforeach
      						</tbody>
							</table>
							<br/>
							{{ $deliveryOrders->appends($url)->links() }}
        				</div>
      			</div>
    		 </div>
  		</div>
	</div>
<!-- partial -->
	<!-- Modal Delete -->
	<div class="modal fade" id="deleteDoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          	<div class="modal-content">
            	<div class="modal-header">
              		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                		<span aria-hidden="true">&times;</span>
              		</button>
            	</div>
            	<div class="modal-body">
              		<h5 style="text-align:center;">Are You Sure to Delete this Delivery Order ?</h5>
            	</div>
            	<div class="modal-footer">
            		<form id="frmDelete" method="post" action="">
                    {{csrf_field()}}
                    	<button type="submit" class="btn btn-gradient-danger mr-2">Yes</button>
                	</form>
              		<button class="btn btn-light">No</button>
            	</div>
          	</div>
        </div>
    </div>
    <!-- End Modal Delete -->
</div>
@endsection

@section('script')
<script>
	$(document).ready(function(e){
		$("#filter_branch").on("change", function(){
		  console.log("test")
		  var id = $(this).val();
		  $.get( '{{ route("fetchCsoByIdBranch", ['branch' => ""]) }}/'+id )
		  .done(function( result ) {
			  $( "#filter_cso" ).html("");
			  var arrCSO = "<option selected value=\"\">All CSO</option>";
			  if(result.length > 0){
				  $.each( result, function( key, value ) {
					arrCSO += "<option value=\""+value['id']+"\">"+value['code']+" - "+value['name']+"</option>";
				  });
				  $( "#filter_cso" ).append(arrCSO);
				}
			});
		if(id == ""){
		  $( "#filter_cso" ).html("<option selected value=\"\">All CSO</option>");
	  }
		});
		$("#filter_province").on("change", function(){
      var id = $(this).val();
      $( "#filter_city" ).html("");
      $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
      .done(function( result ) {
          result = result['rajaongkir']['results'];
          var arrCity = "<option selected value=\"\">All City</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {
                  if(value['type'] == "Kota"){
                      arrCity += "<option value=\"Kota "+value['city_id']+"\">Kota "+value['city_name']+"</option>";
                  }
              });
              $( "#filter_city" ).append(arrCity);
            }
        });
    });
    $("#filter_city").on("change", function(){
      var id = $(this).val();
      $( "#filter_district" ).html("");
      $.get( '{{ route("fetchDistrict", ['city' => ""]) }}/'+id )
      .done(function( result ) {
          result = result['rajaongkir']['results'];
          var arrdistrict = "<option selected value=\"\">All District</option>";
          if(result.length > 0){
              $.each( result, function( key, value ) {
                arrdistrict += "<option value=\""+value['subdistrict_id']+"\">Kota "+value['subdistrict_name']+"</option>";
              });
              $( "#filter_district" ).append(arrdistrict);
            }
        });
    });
	  $(".btn-delete").click(function(e) {
		$("#frmDelete").attr("action",  $(this).val());
		});
	});
	$(document).on("click", "#btn-filter", function(e){
	  var urlParamArray = new Array();
	  var urlParamStr = "";
	  if($('#filter_city').val() != ""){
		urlParamArray.push("filter_city=" + $('#filter_city').val());
	  }
	  if($('#filter_district').val() != ""){
		urlParamArray.push("filter_district=" + $('#filter_district').val());
	  }
	  if($('#filter_branch').val() != ""){
		urlParamArray.push("filter_branch=" + $('#filter_branch').val());
	  }
	  if($('#filter_cso').val() != ""){
		urlParamArray.push("filter_cso=" + $('#filter_cso').val());
	  }
	  for (var i = 0; i < urlParamArray.length; i++) {
		if (i === 0) {
		  urlParamStr += "?" + urlParamArray[i]
		} else {
		  urlParamStr += "&" + urlParamArray[i]
		}
	  }

	  window.location.href = "{{route('list_deliveryorder')}}" + urlParamStr;
	});
</script>
@endsection
