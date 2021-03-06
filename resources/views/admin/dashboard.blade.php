@extends('admin.layouts.template')

@section('content')
@if(Auth::user()->roles[0]['slug'] != 'head-admin')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
          Welcome, {{ Auth::user()->name }}
        </h3>
        <nav aria-label="breadcrumb">

        </nav>
    </div>
  </div>
</div>
@endif
@can('show-dashboard')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
              	<i class="mdi mdi-home"></i>
            </span> Dashboard </h3>
            <nav aria-label="breadcrumb">

            </nav>
      	</div>
        <div class="row">
          	<div class="col-md-4 stretch-card grid-margin">
            	<div class="card bg-gradient-danger card-img-holder text-white">
              		<div class="card-body">
                		<img src="{{asset('sources/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                		<h4 class="font-weight-normal mb-3">Month Sale <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                		</h4>
                		<h2 class="mb-5">Rp 6.000.000.000</h2>
                		<h6 class="card-text">Increase 60%</h6>
              		</div>
            	</div>
         	</div>
          	<div class="col-md-4 stretch-card grid-margin">
            	<div class="card bg-gradient-info card-img-holder text-white">
              		<div class="card-body">
                		<img src="{{asset('sources/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                		<h4 class="font-weight-normal mb-3">Month Order <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                		</h4>
                		<h2 class="mb-5">5.633</h2>
                		<h6 class="card-text">Increase 10%</h6>
              		</div>
            	</div>
          	</div>
          	<div class="col-md-4 stretch-card grid-margin">
            	<div class="card bg-gradient-success card-img-holder text-white">
              		<div class="card-body">
                		<img src="{{asset('sources/dashboard/circle.svg')}}" class="card-img-absolute" alt="circle-image" />
                		<h4 class="font-weight-normal mb-3">Website Visitors <i class="mdi mdi-diamond mdi-24px float-right"></i>
                		</h4>
                		<h2 class="mb-5">15.741</h2>
                		<h6 class="card-text">Increase 5%</h6>
              		</div>
           		</div>
          	</div>
        </div>
        <div class="row">
          	<div class="col-md-7 grid-margin stretch-card">
            	<div class="card">
              		<div class="card-body">
                		<div class="clearfix">
                  			<h4 class="card-title float-left">Sale Statistic</h4>
                  			<div id="visit-sale-chart-legend" class="rounded-legend legend-horizontal legend-top-right float-right"></div>
                		</div>
                		<canvas id="visit-sale-chart" class="mt-4"></canvas>
             		</div>
            	</div>
          	</div>
          	<div class="col-md-5 grid-margin stretch-card">
            	<div class="card">
              		<div class="card-body">
                		<h4 class="card-title">Visitor Traffic</h4>
                		<canvas id="traffic-chart"></canvas>
                		<div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
              		</div>
            	</div>
          	</div>
        </div>
    </div>
</div>
@endcan
@endsection