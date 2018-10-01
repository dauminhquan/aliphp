@extends('layout')
@section('content')
    <!-- Main cont!-- Main c  <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-default">
            <div class="page-header-content">
                <div class="page-title">
                    <h4><i class="icon-arrow-left52 position-left"></i>- Danh sách template</h4>
                </div>
            </div>

            <div class="breadcrumb-line">
                <ul class="breadcrumb">
                    <li><a href="#"><i class="icon-home2 position-left"></i> Home</a></li>
                    <li class="active">Template</li>
                </ul>
            </div>
        </div>
        <!-- /page header -->


        <!-- Content area -->
        <div class="content">

            <!-- Cell sizing title -->
            <h6 class="content-group text-semibold">
                Danh sách Cột
            </h6>

            <table-content></table-content>
            <!-- Footer -->
            <div class="footer text-muted">
                &copy; 2018. <a href="#">Aliexpress product to Amazon</a> by <a href="https://www.facebook.com/toi.cam.7" target="_blank">Đậu Quân</a>
            </div>
            <!-- /footer -->

        </div>
        <!-- /content area -->

    </div>
    <!-- /main content -->
@endsection
@section('script')
    <script src="{{asset('js/templates.js')}}"></script>
    @endsection
