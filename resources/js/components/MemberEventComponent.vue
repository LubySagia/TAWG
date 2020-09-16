<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-member"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Thành Viên Tham Gia Sự Kiện
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
            </div>
        </div>
        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <div class="row align-items-center">
                    <div class="col-12">
                           <div class="alert alert-danger" role="alert" v-if="showErrors">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">{{alertMessage}}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-success" role="alert" v-if="showSuccess">
                                <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                <div class="alert-text">{{alertMessage}}!</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                    </div>
                    <div class="col-xl-8 order-2 order-xl-1">
                        <div class="row align-items-center">
                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row align-items-center mt-3">
                        <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                            <div class="kt-form__group kt-form__group--inline">
                                <div class="kt-form__label">
                                    <label>Điểm Danh:</label>
                                </div>
                                <div class="kt-form__control">
                                    <div class="input-group ">
                                        <select class="form-control" id="kt_checkin_query_from">
                                            <option value="">Tất Cả</option>
                                            <option value="1">Đã Điểm Danh</option>
                                            <option value="0">Chưa Điểm Danh</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center mt-2 array-action" style="display: none">
                    <div class="col-12">
                        
                        <button class="btn btn-warning" v-on:click="checkInArray()">Điểm danh/hủy điểm danh {{selectArrayID.length}}Thành Viên  Đã Chọn</button>
                        <button class="btn btn-danger" v-on:click="deleteArray()">Xóa {{selectArrayID.length}} Thành Viên Đã Chọn</button>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-member"></div>

            <!--end: Datatable -->
        </div>
            <!-- Modal -->

    </div>
</div>

<!-- end:: Content -->
</template>

<script>    
    export default {
        props: ['event_id'],
        data() {
            return {
                csrfToken: window.TAWG.csrfToken,
                showErrors:false,
                showSuccess:false,
                onSubmit:false,
                alertMessage:'',
                datatable:'',
                selectMemberEvent:{'id':'', 'user_id':'','event_id':'', 'check_in':'','check_in_time':'', 'created_at':'','created_at':''},
                selectArrayID:[]
            }
        },
        mounted() {
            this.initDataTable();
        },
        methods: {
            initDataTable:function(){
                var _self=this;
                var options = {
                    // datasource definition
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/admin/event/member_list/'+_self.event_id,
                                headers: {
                                    'X-CSRF-TOKEN': _self.csrfToken
                                },
                                method:'POST',

                            },
                        },
                        pageSize: 20, // display 20 records per page
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true,
                    },

                    // layout definition
                    layout: {
                        scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                        height: 1000, // datatable's body's fixed height
                        footer: false, // display/hide footer
                    },

                    // column sorting
                    sortable: true,

                    pagination: true,

                    search: {
                        input: $('#generalSearch'),
                    },

                    // columns definition
                    columns: [
                       {
                            field: 'id',
                            title: '#',
                            width: 30,
                            type: 'number',
                            selector: {
                                class: "kt-checkbox--solid"
                            },
                            textAlign: 'center',
                        },
                        {
                            field: 'user_id',
                            title: 'Thành Viên',
                            template: function(row) {
                               return row.user.last_name+ ' ' +row.user.first_name+'('+row.user.email+')';
                            }
                        },
                        {
                            field: 'check_in',
                            title: 'Thông tin',
                            template: function(row) {
                                var checkin='';
                                if(row.check_in==true)
                                {
                                    checkin='<p class="text-success">Được điểm danh lúc: '+row.check_in_time+'</p>';
                                }
                                else{
                                    checkin='<p class="text-danger">Chưa được điểm danh</p>';
                                }
                                return '<p>Đăng ký tham dự lúc: '+row.created_at+'</p>'+checkin;

                            }
                        },
                        {
                            field: 'Actions',
                            title: 'Thao Tác',
                            sortable: false,
                            width: 110,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(row) {
                                return '\
                                \<button type="button" class="btn btn-danger btn-sm btn-icon delete-action" data-id="'+row.id+'"><i class="fa fa-trash"></i></button>\
                            ';
                            },
                        }
                    ]

                };
                _self.datatable = $('.kt-datatable').KTDatatable(options);
                $('.kt-datatable').on('kt-datatable--on-layout-updated',function(){
                    $('.view-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.viewMemberEvent(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa thành viên?',
                          text: "Bạn có chắc rằng muốn xóa thành viên này khỏi sự kiện, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteMemberEvent(id);
                          }
                        })
                    });
                    
                });
                _self.datatable.on("kt-datatable--on-check kt-datatable--on-uncheck", function(t) {
                    var tempSelectID=[];
                    for (var a = _self.datatable.rows(".kt-datatable__row--active").nodes().find('.kt-checkbox--single > [type="checkbox"]').map(function(t, e) {
                            return $(e).val()
                        }), n = document.createDocumentFragment(), l = 0; l < a.length; l++) {
                        tempSelectID.push(a[l]);
                    }
                    _self.selectArrayID=tempSelectID;
                    if(tempSelectID.length>0){
                        $('.array-action').show();
                    }
                    else{
                        $('.array-action').hide();
                    }
                })
                 $('#kt_checkin_query_from').on('change', function() {
                    _self.datatable.search($(this).val().toLowerCase(), 'check_in');
                });
            },
            deleteMemberEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectMemberEvent={'id':'', 'user_id':'','event_id':'', 'check_in':'','check_in_time':'', 'created_at':''};
                var _self=this;
                axios.post('/admin/event/delete_member', {'id':id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Xóa tài khoản thành công';
                        $(".alert").focus();
                        _self.datatable.reload();
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_suggestion_view').modal('hide'); 
                        if(typeof errors.response.data != "undefined"){
                            if(errors.response.status==422)
                            {
                                var message=errors.response.data.message;
                 
                                if(typeof message == 'string')
                                {
                                     _self.alertMessage=message;
                                }
                                else{
                                    for (var property in message) {
                                      _self.alertMessage+=' '+ message[property];
                                    } 
                                }
                            }
                            else if(errors.response.status==500){
                                var message=errors.response.data.message;
                                _self.alertMessage=message;
                            }
                            else{
                                _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                            }
                        }
                        else{
                            _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                        }
                    });
            },
            viewMemberEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectMemberEvent={'id':'', 'user_id':'','event_id':'', 'check_in':'','check_in_time':'', 'created_at':''};
                var _self=this;
                axios.post('/admin/event/view_member', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectMemberEvent.id=response.data.data.id;
                            _self.selectMemberEvent.suggestion_id=response.data.data.suggestion_id;
                            _self.selectMemberEvent.event_id=response.data.data.event_id;
                            _self.selectMemberEvent.content=response.data.data.content;
                            _self.selectMemberEvent.created_at=response.data.data.created_at;
                            
                        }
                        _self.onSubmit=false;
                        $('#kt_modal_suggestion_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_suggestion_view').modal('hide'); 
                        if(typeof errors.response.data != "undefined"){
                            if(errors.response.status==422)
                            {
                                var message=errors.response.data.message;
                 
                                if(typeof message == 'string')
                                {
                                     _self.alertMessage=message;
                                }
                                else{
                                    for (var property in message) {
                                      _self.alertMessage+=' '+ message[property];
                                    } 
                                }
                            }
                            else if(errors.response.status==500){
                                var message=errors.response.data.message;
                                _self.alertMessage=message;
                            }
                            else{
                                _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                            }
                        }
                        else{
                            _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                        }
                    });
            },
            deleteArray(){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;
                axios.post('/admin/event/delete_member_array', {'member_id_array':this.selectArrayID,'event_id':this.event_id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Xóa thành công';
                        $(".alert").focus();
                        _self.datatable.reload();
                        _self.selectArrayID=[];
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        if(typeof errors.response.data != "undefined"){
                            if(errors.response.status==422)
                            {
                                var message=errors.response.data.message;
                 
                                if(typeof message == 'string')
                                {
                                     _self.alertMessage=message;
                                }
                                else{
                                    for (var property in message) {
                                      _self.alertMessage+=' '+ message[property];
                                    } 
                                }
                            }
                            else if(errors.response.status==500){
                                var message=errors.response.data.message;
                                _self.alertMessage=message;
                            }
                            else{
                                _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                            }
                        }
                        else{
                            _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                        }
                    });
            },
            checkInArray(){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;
                axios.post('/admin/event/checkin_member_array', {'member_id_array':this.selectArrayID,'event_id':this.event_id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Thay đổi thành công';
                        $(".alert").focus();
                        _self.datatable.reload();
                        _self.selectArrayID=[];
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        if(typeof errors.response.data != "undefined"){
                            if(errors.response.status==422)
                            {
                                var message=errors.response.data.message;
                 
                                if(typeof message == 'string')
                                {
                                     _self.alertMessage=message;
                                }
                                else{
                                    for (var property in message) {
                                      _self.alertMessage+=' '+ message[property];
                                    } 
                                }
                            }
                            else if(errors.response.status==500){
                                var message=errors.response.data.message;
                                _self.alertMessage=message;
                            }
                            else{
                                _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                            }
                        }
                        else{
                            _self.alertMessage='Hệ thống đã xảy ra lỗi vui lòng thử lại hoặc liên hệ quản trị viên';
                        }
                    });
            }
        }
        
    }
</script>
