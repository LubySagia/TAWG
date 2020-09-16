@extends('layouts.admin')

@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Mật Khẩu</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"></span>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                   
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->
    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
       <div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
           <div class="kt-grid__item kt-grid__item--fluid kt-app__content">
                <div class="row" id="app">
                    <div class="col-xl-12">
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">Đổi mật khẩu tài khoản<small>{{Auth::user()->last_name.' '.Auth::user()->first_name.'('.Auth::user()->email.')'}}</small></h3>
                                </div>
                                <div class="kt-portlet__head-toolbar kt-hidden">
                                </div>
                            </div>
                           <change-password></change-password>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </div>
    <!-- end:: Content -->
</div>
@endsection
