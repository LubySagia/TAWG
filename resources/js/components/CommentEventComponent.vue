<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-comment"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Bình Luận  Sự Kiện
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
                            <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Tình trạng:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_report">
                                            <option value="">Tất Cả</option>
                                            <option value="1">Bị báo cáo</option>
                                            <option value="0">Không bị báo cáo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                    <div class="row align-items-center mt-2 array-action" style="display: none">
                    <div class="col-12">
                        <button class="btn btn-warning" v-on:click="deleteArray()">Xóa {{selectArrayID.length}} Bình Luận Đã Chọn</button>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-comment"></div>

            <!--end: Datatable -->
        </div>
                 <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_comment_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Thông Tin Chi Tiết</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                       <table class="table table-striped">
                            <tr>
                               <td width="30%"><b>Thành Viên</b></td>
                               <td>{{selectCommentEvent.user.last_name+ ' ' +selectCommentEvent.user['first_name']+'('+selectCommentEvent.user.email+')'}}
                               </td>
                            </tr>
                            <tr>
                               <td width="30%"><b>Thời gian</b></td>
                               <td>{{selectCommentEvent.created_at}}
                               </td>
                            </tr>
                            <tr>
                               <td width="30%"><b>Nội dung bình luận</b></td>
                               <td>{{ selectCommentEvent.content }}
                               </td>
                            </tr>
                            <tr>
                               <td width="20%" class="text-danger"><b>Tố Cáo</b></td>
                               <td>
                                    <table class="table">
                                       <tr v-for="(item,index) in selectCommentEvent.report">
                                           <td>
                                               {{item.user.last_name+ ' ' +item.user.first_name+'('+item.user.email+')'}}
                                           </td>
                                           <td>
                                               {{item.content}}
                                           </td>
                                           <td>
                                               {{item.created_at}}
                                           </td>
                                           <td>
                                               <button type="button" v-on:click="deleteReport(item.id)" class="btn btn-danger btn-sm btn-icon">
                                                   <i class="fa fa-times"></i>
                                               </button>
                                           </td>
                                       </tr>
                                    </table>
                               </td> 
                            </tr>
                       </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: Modal View -->

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
                selectCommentEvent:{'id':'', 'user_id':'','event_id':'', 'content':'','user':'','report':'', 'created_at':''},
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
                                url: '/admin/event/comment_list/'+_self.event_id,
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
                            field: 'content',
                            title: 'Bình Luận',
                            template: function(row) {
                                let report='';
                                if(row.report.length>0)
                                {
                                    report='<p class="text-danger"><b>Có '+row.report.length+' báo cáo về bình luận này</b></p><hr/>';
                                }
                               return report+row.content;
                            }
                        },
                         {
                            field: 'created_at',
                            title: 'Thời gian'
                            
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
                                \<button type="button" class="btn btn-primary btn-sm btn-icon view-action" data-id="'+row.id+'"><i class="fa fa-eye"></i></button>\
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
                        _self.viewCommentEvent(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa Bình Luận?',
                          text: "Bạn có chắc rằng muốn xóa bình luận này khỏi sự kiện, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteCommentEvent(id);
                          }
                        })
                    });
                    
                });
                $('#kt_form_report').on('change', function() {
                    _self.datatable.search($(this).val().toLowerCase(), 'report');
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
                });
            },
            deleteCommentEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectCommentEvent={'id':'', 'user_id':'','event_id':'', 'content':'','user':'','report':'', 'created_at':''};
                var _self=this;
                axios.post('/admin/event/delete_comment', {'id':id})
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
                        $('#kt_modal_comment_view').modal('hide'); 
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
            viewCommentEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectCommentEvent={'id':'', 'user_id':'','event_id':'', 'content':'','user':'','report':'', 'created_at':''};
                var _self=this;
                axios.post('/admin/event/view_comment', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            console.log(response.data.data);
                            _self.selectCommentEvent.id=response.data.data.id;
                            _self.selectCommentEvent.event_id=response.data.data.event_id;
                            _self.selectCommentEvent.content=response.data.data.content;
                            _self.selectCommentEvent.report=response.data.data.report;
                            _self.selectCommentEvent.user=response.data.data.user;
                            _self.selectCommentEvent.created_at=response.data.data.created_at;
                            
                        }
                        _self.onSubmit=false;
                        $('#kt_modal_comment_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_comment_view').modal('hide'); 
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
                axios.post('/admin/event/delete_comment_array', {'comment_id_array':this.selectArrayID,'event_id':this.event_id})
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
            deleteReport(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;
                axios.post('/admin/event/delete_comment_report', {'id':id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Xóa thành công';
                        $(".alert").focus();
                        
                        if(response.data.success)
                        {
                            _self.selectCommentEvent.id=response.data.data.id;
                            _self.selectCommentEvent.event_id=response.data.data.event_id;
                            _self.selectCommentEvent.content=response.data.data.content;
                            _self.selectCommentEvent.report=response.data.data.report;
                            _self.selectCommentEvent.user=response.data.data.user;
                            _self.selectCommentEvent.created_at=response.data.data.created_at;
                            _self.datatable.reload();
                        }
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
