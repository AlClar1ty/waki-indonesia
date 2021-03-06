<?php
    $menu_item_page = "category";
?>
@extends('admin.layouts.template')

@section('content')
<div class="main-panel">
	<div class="content-wrapper">
		<div class="page-header">
			<h3 class="page-title">Edit Category Product</h3>
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a data-toggle="collapse" href="#kategori-dd" aria-expanded="false" aria-controls="kategori-dd">Category Product</a></li>
					<li class="breadcrumb-item active" aria-current="page">Edit Category Product</li>
				</ol>
			</nav>
		</div>
	<div class="row">
		<div class="col-12 grid-margin stretch-card">
			<div class="card">
				<div class="card-body">
					<form id="actionUpdate" class="forms-sample" action="{{route('update_category')}}" method="POST">
						<div class="form-group">
							<label for="">Category</label>
							<input type="text" class="form-control" name="name" value="{{$categories['name']}}" required>
						</div>
						<div class="form-group d-none">
							<label for="">Kategori Produk</label>
							<select class="form-control" id="exampleSelectGender">
								<option>1</option>
								<option>2</option>
							</select>
						</div>

						<div class="form-group d-none">
							<div class="col-xs-12">
								<label>Gambar Kategori (400x400 pixel)</label>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 row productimg" style="border: 1px solid rgb(221, 221, 221, 0.5); border-radius: 4px; box-shadow: none; margin: 0;">
								<div class="col-xs-12 col-sm-4 imgUp" style="padding: 15px; float: left; text-align: center;">
									<div class="imagePreview" style="background-image: url(assets/images/dashboard/no-img.jpg);"></div>
									<label class="file-upload-browse btn btn-gradient-primary" style="margin: 15px 0 0; text-align: center;">Upload
									<input data-name="arr_image" id="gambars-0" type="file" accept=".jpg,.jpeg,.png" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;">
									</label>
									<i class="mdi mdi-window-close del"></i>
								</div>
							</div>
						</div>

						<input type="hidden" name="idCategory" value="{{$categories['id']}}">
						<button id="updateCategory" type="submit" class="btn btn-gradient-primary mr-2">Save</button>
						<button class="btn btn-light">Cancel</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- partial -->
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        var frmAdd;

	    $("#actionUpdate").on("submit", function (e) {
	        e.preventDefault();
	        frmAdd = _("actionUpdate");
	        frmAdd = new FormData(document.getElementById("actionUpdate"));
	        frmAdd.enctype = "multipart/form-data";
	        var URLNya = $("#actionUpdate").attr('action');
	        console.log(URLNya);

	        var ajax = new XMLHttpRequest();
	        ajax.upload.addEventListener("progress", progressHandler, false);
	        ajax.addEventListener("load", completeHandler, false);
	        ajax.addEventListener("error", errorHandler, false);
	        ajax.open("POST", URLNya);
	        ajax.setRequestHeader("X-CSRF-TOKEN",$('meta[name="csrf-token"]').attr('content'));
	        ajax.send(frmAdd);
	    });
	    function progressHandler(event){
	        document.getElementById("updateCategory").innerHTML = "UPLOADING...";
	    }
	    function completeHandler(event){
	        var hasil = JSON.parse(event.target.responseText);
	        console.log(hasil);

	        for (var key of frmAdd.keys()) {
	            $("#actionUpdate").find("input[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("select[name="+key+"]").removeClass("is-invalid");
	            $("#actionUpdate").find("textarea[name="+key+"]").removeClass("is-invalid");

	            $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text("");
	            $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text("");
	        }

	        if(hasil['errors'] != null){
	            for (var key of frmAdd.keys()) {
	                if(typeof hasil['errors'][key] === 'undefined') {
	                    
	                }
	                else {
	                    $("#actionUpdate").find("input[name="+key+"]").addClass("is-invalid");
	                    $("#actionUpdate").find("select[name="+key+"]").addClass("is-invalid");
	                    $("#actionUpdate").find("textarea[name="+key+"]").addClass("is-invalid");

	                    $("#actionUpdate").find("input[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                    $("#actionUpdate").find("select[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                    $("#actionUpdate").find("textarea[name="+key+"]").next().find("strong").text(hasil['errors'][key]);
	                }
	            }
	            alert("Input Error !!!");
	        }
	        else{
	            alert("Input Success !!!");
	            window.location.reload()
	        }

	        document.getElementById("updateCategory").innerHTML = "SAVE";
	    }
	    function errorHandler(event){
	        document.getElementById("updateCategory").innerHTML = "SAVE";
	    }
    });
</script>
@endsection