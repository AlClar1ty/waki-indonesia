@extends('layouts.template')

@section('content')
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


<section id="intro" class="clearfix">
    <div class="container">
        <div class="row justify-content-center">
            <h2>REGISTRATION FORM</h2>
            <form action="{{ Route('store_delivery_order') }}" method="post" role="form" class="contactForm col-md-9">
                @csrf
                <div class="form-group">
                    <input type="text" name="no_member" class="form-control" id="no_member" placeholder="Member Code (optional)"/>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" required data-msg="Please Fill in The Name" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone Number" required data-msg="Please Fill in The Phone Number" />
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <textarea class="form-control" name="address" rows="5" required data-msg="Please Fill in The Address" placeholder="Address"></textarea>
                    <div class="validation"></div>
                </div>

                @for($j=0;$j<2;$j++)
                    <div class="form-group" style="width: 82%; display: inline-block;">
                        <select class="form-control" name="product_{{ $j }}" data-msg="Please Choose The Promo" {{ $j>0 ? "":"required"}}>
                            <option selected disabled value="">Choose Promo{{ $j>0 ? " (optional)":""}}</option>

                            @foreach($promos as $key=>$promo)
                                <option value="{{ $key }}">{{ $promo['code'] }} - {{ $promo['name'] }} ( {{ $promo['harga'] }} )</option>
                            @endforeach
                        </select>
                        <div class="validation"></div>
                    </div>
                    <div class="form-group" style="width: 16%; display: inline-block; float: right;">
                        <select class="form-control" name="qty_{{ $j }}" data-msg="Please Choose The Quantity" {{ $j>0 ? "":"required"}}>
                            <option selected value="1">1</option>

                            @for($i=2; $i<=10;$i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="validation"></div>
                    </div>
                @endfor

                <div class="form-group">
                    <select class="form-control" id="branch" name="branch_id" data-msg="Please Choose The Branch" required>
                        <option selected disabled value="">Choose Branch</option>

                        @foreach($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['code'] }} - {{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                    <div class="validation"></div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="cso_id" id="cso" placeholder="CSO Code" required data-msg="Please Fill in The CSO Code" style="text-transform:uppercase"/>
                    <div class="validation" id="validation_cso"></div>
                </div>

                <div id="errormessage"></div>
                <div class="text-center"><button id="submit" type="submit" title="Send Message" disabled="">Save Registration Form</button></div>
            </form>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script>
    $(document).ready(function(){
        $("#cso").on("input", function(){
            var txtCso = $(this).val();
            $.get( '{{route("fetchCso")}}', { txt: txtCso })
            .done(function( result ) {
                if (result == 'true'){
                    $('#validation_cso').html('CSO Code Accepted');
                    $('#validation_cso').css('color', 'green');
                    $('#submit').removeAttr('disabled');
                }
                else{
                    $('#validation_cso').html('CSO Code Denied');
                    $('#validation_cso').css('color', 'red');
                    $('#submit').attr('disabled',"");
                }
            });
        });
    });
</script>
@endsection
