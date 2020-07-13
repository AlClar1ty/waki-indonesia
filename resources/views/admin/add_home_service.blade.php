<?php
    $menu_item_page = "homeservice";
    $menu_item_second = "add_home_service";
?>
@extends('admin.layouts.template')

@section('style')
<style type="text/css">
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
</style>
@endsection

@section('content')
<div class="main-panel">
  	<div class="content-wrapper">
    	<div class="page-header">
      		<h3 class="page-title">Add Home Service</h3>
      		<nav aria-label="breadcrumb">
	        	<ol class="breadcrumb">
	          		<li class="breadcrumb-item"><a data-toggle="collapse" href="#cso-dd" aria-expanded="false" aria-controls="cso-dd">Home Service</a></li>
	          		<li class="breadcrumb-item active" aria-current="page">Add Home Service</li>
	        	</ol>
      		</nav>
    	</div>
	    <div class="row">
	      	<div class="col-12 grid-margin stretch-card">
	        	<div class="card">
	          		<div class="card-body">
	            		<form id="actionAdd" class="forms-sample" method="POST" action="{{ route('admin_add_homeService') }}">
	            			{{ csrf_field() }}
	              			<div class="form-group">
                                <label for=""><h2>Data Pelanggan</h2></label><br/>
	                			<label for="">No. Member (optional)</label>
	                			<input type="number" class="form-control" id="no_member" name="no_member" placeholder="No. Member (optional)">
	                			<div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Name</label>
				                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Phone Number</label>
				                <input type="number" class="form-control" name="phone" id="phone" placeholder="Nomor Telepon" required data-msg="Mohon Isi Nomor Telepon"/>
				                <div class="validation"></div>
	              			</div>
	              			<div class="form-group">
				                <label for="">Province</label>
								<select class="form-control" id="province" name="province_id" data-msg="Mohon Pilih Provinsi" required>
									<option selected disabled value="">Pilihan Provinsi</option>
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
	              			<div class="form-group">
				                <label for="exampleTextarea1">Kota</label>
				                <select class="form-control" id="city" name="city" data-msg="Mohon Pilih Kota" required>
									<option selected disabled value="">Pilihan Kota</option>
								</select>
				                <div class="validation"></div>
							</div>
							<div class="form-group">
				                <label for="exampleTextarea1">Alamat</label>
				                <textarea class="form-control" name="address" rows="5" required data-msg="Mohon Isi Alamat" placeholder="Alamat"></textarea>
				                <div class="validation"></div>
	              			</div>
	              			<br>

	              			<div class="form-group">
								<label for=""><h2>Data CSO</h2></label><br/>  
	              				<label for="">Cabang</label>
								  <select class="form-control" id="branch" name="branch_id" data-msg="Mohon Pilih Cabang" required>
									<option selected disabled value="">Pilihan Cabang</option>
			
									@foreach($branches as $branch)
										<option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
									@endforeach
								</select>
			                    <div class="validation"></div>
							</div>
							
							<div class="form-group">
								<label for="">Kode CSO</label>
									<input type="text" class="form-control" name="cso_id" id="cso" placeholder="Kode CSO" required data-msg="Mohon Isi Kode CSO" style="text-transform:uppercase"/>
									<div class="validation" id="validation_cso"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			                    <div class="validation"></div>
							</div>
							
							<div class="form-group">
								<label for="">No Telepon CSO</label>
									<input type="number" class="form-control" name="cso_phone" id="cso_phone" placeholder="No. Telepon CSO" required data-msg="Mohon Isi Nomor Telepon" />
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			                    <div class="validation"></div>
							</div>
							

							<div class="form-group">
								<label for="">Kode Partner CSO(Optional)</label>
								<input type="text" class="form-control" name="cso2_id" id="cso2" placeholder="Kode Partner CSO (opsional)" style="text-transform:uppercase"/>
									<div class="validation"></div>
									<span class="invalid-feedback">
										<strong></strong>
									</span>
			                    <div class="validation"></div>
			                </div>


							<div class="form-group">
								<label for=""><h2>Waktu Home Service</h2></label><br/>  
	              				<label for="">Tanggal</label>
								  <input type="date" class="form-control" name="date" id="date" placeholder="Tanggal Janjian" value="<?php echo date('Y-m-j'); ?>" required data-msg="Mohon Isi Tanggal" />
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>

							<div class="form-group">
								<label for="">Jam Janjian</label>
								<input type="time" class="form-control" name="time" id="time" placeholder="Jam Janjian" value="<?php echo date('H:i'); ?>" required data-msg="Mohon Isi Jam" min="10:00" max="20:00"/>
								<div class="validation"></div>
								<span class="invalid-feedback">
									<strong></strong>
								</span>
							</div>
			                
	              			<div id="errormessage"></div>

	              			<div class="form-group">
	              				<button id="addHomeService" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
	              				<button class="btn btn-light">Cancel</button>
	              			</div>
	            		</form>

	          		</div>
	        	</div>
	      	</div>
	    </div>
	</div>
</div>
@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>
    $(document).ready(function(){
        $("#cso, #cso2").on("input", function(){
            var txtCso = $(this).val();
            var obj = $('#validation_cso');
            if($(this)[0].id == "cso2"){
                obj = $('#validation_cso2');
            }
            $.get( '{{route("fetchCso")}}', { txt: txtCso })
            .done(function( result ) {
                if (result == 'true'){
                    obj.html('Kode CSO Benar');
                    obj.css('color', 'green');
                    $('#submit').removeAttr('disabled');
                }
                else{
                    obj.html('Kode CSO Salah');
                    obj.css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });

        $("#province").on("change", function(){
            var id = $(this).val();
            $( "#city" ).html("");
            $.get( '{{ route("fetchCity", ['province' => ""]) }}/'+id )
            .done(function( result ) {
                result = result['rajaongkir']['results'];
                var arrCity = "<option selected disabled value=\"\">Pilihan Kota</option>";
                if(result.length > 0){
                    $.each( result, function( key, value ) {
                        if(value['type'] == "Kota"){                            
                            arrCity += "<option value=\"Kota "+value['city_name']+"\">Kota "+value['city_name']+"</option>";
                        }
                    });
                    $( "#city" ).append(arrCity);
                }
            });
        });

        $('#submit').click(function(){
            var appointment = 
            $.ajax({
                type: 'POST',
                data: {
                    date: date
                },
                success: function(data){
                    console.log(data.data);
                },
                error: function(xhr){
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection