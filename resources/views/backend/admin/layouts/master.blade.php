<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>@yield('title','IMP ADMIN')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keywords" content="">
    <!-- Favicon -->
    <link rel="icon" href="{{asset('backend/assets/img/brand/favicon.ico')}}" type="image/x-icon"/>
    <!-- Title -->
    <title>IMP SOFTWARE</title>
    <!---Fontawesome css-->
    <link href="{{asset('backend/assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <!---Ionicons css-->
{{--    <link href="{{asset('backend/assets/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">--}}
    <!---Typicons css-->
    <link href="{{asset('backend/assets/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
    <!---Feather css-->
    <link href="{{asset('backend/assets/plugins/feather/feather.css')}}" rel="stylesheet">
    <!---Falg-icons css-->
{{--    <link href="{{asset('backend/assets/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">--}}
<!---DataTables css-->
    <link href="{{asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('backend/assets/plugins/datatable/responsivebootstrap4.min.css')}}" rel="stylesheet"/>
{{--    <link href="{{asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.css')}}" rel="stylesheet"/>--}}
<!---Style css-->
    <link href="{{asset('backend/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/custom-style.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/skins.css')}}" rel="stylesheet">
{{--    <link href="{{asset('backend/assets/css/dark-style.css')}}" rel="stylesheet">--}}
{{--    <link href="{{asset('backend/assets/css/custom-dark-style.css')}}" rel="stylesheet">--}}
<!---Select2 css-->
    <link href="{{asset('backend/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <!--Mutipleselect css-->
{{--    <link rel="stylesheet" href="{{asset('backend/assets/plugins/multipleselect/multiple-select.css')}}">--}}
<!---Sidebar css-->
    <link href="{{asset('backend/assets/plugins/sidebar/sidebar.css')}}" rel="stylesheet">
    <!---Jquery.mCustomScrollbar css-->
    <link href="{{asset('backend/assets/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.css')}}"
          rel="stylesheet">
    <!---Sidemenu css-->
    <link href="{{asset('backend/assets/plugins/sidemenu/closed-sidemenu.css')}}" rel="stylesheet">
    <!---Datetimepicker css-->
    <link href="{{asset('backend/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')}}"
          rel="stylesheet">
    <link href="{{asset('backend/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')}}"
          rel="stylesheet">
    <link href="{{asset('backend/assets/plugins/pickerjs/picker.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('backend/my-assets/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/my-assets/viewerjs-master/dist/viewer.min.css')}}">
    <!---Specturm-color picker css-->
    {{--    <link href="{{asset('backend/assets/plugins/spectrum-colorpicker/spectrum.css')}}" rel="stylesheet">--}}
    <link rel="stylesheet" href="{{asset('backend/my-assets/CodeSeven-toastr-50092cc/build/toastr.min.css')}}">
    <style>
        #loadingNew {
            position: fixed;
            left: 0;
            top: 0;
            opacity: 0.8;
            width: 100%;
            height: 100%;
            z-index: 99999999999;
            background: url('{{asset('backend/assets/img/loader.gif')}}') 50% 50% no-repeat white;
        }
    </style>
    <script src="{{asset('backend/assets/plugins/jquery/jquery.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            let $loading = $('#loadingNew').hide();
            $(document)
                .ajaxStart(function () {
                    $loading.show();
                })
                .ajaxStop(function () {
                    $loading.hide();
                });
        });
    </script>
    @yield('extra-css')
</head>
<body>
<div id="loadingNew"></div>
<!-- Loader -->
{{--
<div id="global-loader">--}}
{{--    <img src="{{asset('backend/assets/img/loader.svg')}}" class="loader-img" alt="Loader">--}}
{{--
</div>
--}}
<!-- End Loader -->
<!-- Page -->
<div class="page">
@include('backend.admin.includes.sidebar')
<!-- Main Content-->
    <div class="main-content side-content pt-0">
        @include('backend.admin.includes.header')
        @yield('content')
    </div>
    <!-- End Main Content-->
    @include('backend.admin.includes.footer')
</div>
<!-- End Page -->
<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>
<!-- Bootstrap js-->
<script src="{{asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Ionicons js-->
{{--<script src="{{asset('backend/assets/plugins/ionicons/ionicons.js')}}"></script>--}}
<!-- Rating js-->
{{--<script src="{{asset('backend/assets/plugins/rating/jquery.rating-stars.js')}}"></script>--}}
<!-- Data Table js -->
<script src="{{asset('backend/assets/plugins/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('backend/assets/plugins/datatable/dataTables.bootstrap4.min.js')}}"></script>
{{--<script src="{{asset('backend/assets/js/table-data.js')}}"></script>--}}
<script src="{{asset('backend/assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/dataTables.buttons.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/buttons.bootstrap4.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/jszip.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/pdfmake.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/vfs_fonts.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/buttons.html5.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/buttons.print.min.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/datatable/fileexport/buttons.colVis.min.js')}}"></script>--}}
<!-- Flot js-->
{{--<script src="{{asset('backend/assets/plugins/jquery.flot/jquery.flot.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/js/chart.flot.sampledata.js')}}"></script>--}}
{{--<!-- Chart.Bundle js-->--}}
{{--<script src="{{asset('backend/assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>--}}
<!-- Peity js-->
{{--<script src="{{asset('backend/assets/plugins/peity/jquery.peity.min.js')}}"></script>--}}
<!-- Jquery-Ui js-->
<script src="{{asset('backend/assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!-- Select2 js-->
<script src="{{asset('backend/assets/plugins/select2/js/select2.min.js')}}"></script>
<!--MutipleSelect js-->
{{--<script src="{{asset('backend/assets/plugins/multipleselect/multiple-select.js')}}"></script>--}}
{{--<script src="{{asset('backend/assets/plugins/multipleselect/multi-select.js')}}"></script>--}}
<!-- Jquery.mCustomScrollbar js-->
<script
    src="{{asset('backend/assets/plugins/jquery.mCustomScrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<!-- Perfect-scrollbar js-->
<script src="{{asset('backend/assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<!-- Sidemenu js-->
<script src="{{asset('backend/assets/plugins/sidemenu/sidemenu.js')}}"></script>
<!-- Sidebar js-->
<script src="{{asset('backend/assets/plugins/sidebar/sidebar.js')}}"></script>
<!-- Sticky js-->
<script src="{{asset('backend/assets/js/sticky.js')}}"></script>
<!-- Dashboard js-->
<script src="{{asset('backend/assets/js/index.js')}}"></script>
<!-- Custom js-->
<script src="{{asset('backend/assets/js/custom.js')}}"></script>
<!-- Datetimepicker js-->
<script src="{{asset('backend/assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')}}"></script>
<!-- Simple-Datepicker js-->
<script src="{{asset('backend/assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')}}"></script>
<script src="{{asset('backend/assets/plugins/pickerjs/picker.min.js')}}"></script>
<!-- Specturm-colorpicker js-->
{{--<script src="{{asset('backend/assets/plugins/spectrum-colorpicker/spectrum.js')}}"></script>--}}

<script src="{{asset('backend/my-assets/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js')}}"></script>
<script src="{{asset('backend/my-assets/jquery-table2excel-master/dist/jquery.table2excel.min.js')}}"></script>
<script src="{{asset('backend/my-assets/viewerjs-master/dist/viewer.min.js')}}"></script>
<script src="{{asset('backend/my-assets/moment-js/moment-with-locales.min.js')}}"></script>
<script src="{{asset('backend/my-assets/CodeSeven-toastr-50092cc/build/toastr.min.js')}}"></script>

@yield('extra-js')
</body>
</html>
