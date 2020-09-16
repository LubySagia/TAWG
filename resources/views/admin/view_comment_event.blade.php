@extends('layouts.admin')

@section('content')
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Quản Lý Bình Luận Event</h3>
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
    <div id="app">
         <comment-event-list event_id="{{ $event_id }}"></comment-event-list>
    </div>                  
</div>
@endsection
@section('scripts')
<script src="{{ asset('assets/admin/plugins/ckeditor/ckeditor.js')}}" type="text/javascript"></script>
@endsection