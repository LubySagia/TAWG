<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-events"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Tin Tức
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <button type="button" class="btn btn-brand btn-icon-sm" v-on:click="openNewForm()">
                        <i class="flaticon2-plus"></i> Thêm Mới
                    </button>
                </div>
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
                    <div class="col-xl-8 status-2 status-xl-1">
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
                                        <label>CLB:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_club_id">
                                            <option value="">Tất Cả</option>
                                            
                                            <option v-for="(item,index) in clubs" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Bắt đầu:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" readonly placeholder="Select date & time" id="kt_datetimepicker_query_from"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Kết Thúc:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <div class="input-group date">
                                            <input type="text" class="form-control" readonly placeholder="Select date & time" id="kt_datetimepicker_query_to"/>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-events"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_event_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Thông Tin Chi Tiết</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                       <table class="table table-striped">
                            <tr>
                               <td  width="30%"><b>Ảnh Đại Diện</b></td>
                               <td><img :src="selectEvent.image" width="120px;"></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tiêu Đề</b></td>
                               <td>{{selectEvent.name}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tóm Tắt</b></td>
                               <td>{{selectEvent.description}}</td>
                           </tr>
                            <tr>
                               <td width="30%"><b>Nội Dung </b></td>
                               <td><div v-html="selectEvent.content"></div></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Thời Gian Bắt đầu</b></td>
                               <td>{{selectEvent.from}}</td>
                           </tr>
                            <tr>
                               <td width="30%"><b>Thời Gian Kết thúc</b></td>
                               <td>{{selectEvent.to}}</td>
                            </tr>
                            <tr>
                               <td width="30%"><b>Giới Hạn Số Lượng Thành Viên</b></td>
                               <td v-if="selectEvent.number_member!=0">{{selectEvent.number_member}}</td>
                               <td v-if="selectEvent.number_member==0">Không giới hạn số lượng thành viên</td>
                            </tr>
                             <tr>
                               <td width="30%"><b>Trạng Thái</b></td>
                               <td v-if="selectEvent.status">Sự kiện đang diễn ra</td>
                               <td v-if="!selectEvent.status">Sự kiện đã kết thúc</td>
                            </tr>
                            <tr>
                               <td width="30%"><b>Điều Kiện Tham Gia</b></td>
                               <td v-if="selectEvent.private">Chỉ thành viên trong CLB </td>
                               <td v-if="!selectEvent.private">Tất cả thành viên</td>
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
        <!-- begin: Modal Form -->
        <div class="modal  fade" id="kt_modal_event_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Sự Kiện</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formEvent" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
                    <div class="modal-body">
                        <div class="kt-portlet__body">
                            <div class="form-group form-group-last">
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
                            </div>
                            <div class="form-group">
                                <label><b>Ảnh Đại Diện</b></label>
                                <div class="row">
                                    <div class="col-4">
                                        <img :src="formEvent.image"  class="img-fluid"/>
                                    </div>
                                    <div class="col-8">
                                          <div class="dropzone dropzone-default" id="myDropzoneForm">
                                            <div class="dropzone-msg dz-message needsclick">
                                                <h3 class="dropzone-msg-title">Kéo thả hoặc nhập chuột để upload hình ảnh</h3>
                                                <span class="dropzone-msg-desc">Ảnh có định dạng jpg, jpeg,png,gif. Tối đa 2MB</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>CLB</b><span class="text-danger">*</span></label>
                                <select class="form-control" v-model="formEvent.club_id">
                                    <option v-for="(item,index) in clubs" :value="item.id">{{ item.name }}</option>
                                </select>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Tiêu đề</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Vui lòng nhập tiêu đề" v-model="formEvent.name">
                                <span class="form-text text-muted">Tối đa 500 ký tự</span>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Mô Tả Ngắn</b></label>
                                <textarea class="form-control" v-model="formEvent.description" rows="5"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Chi tiết</b></label>
                                <textarea class="form-control" v-model="formEvent.content" rows="5" id="content"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Thời gian bắt đầu</b></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" readonly placeholder="Select date & time" id="kt_datetimepicker_submit_from" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Thời gian kết thúc</b></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control" readonly placeholder="Select date & time" id="kt_datetimepicker_submit_to" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="la la-calendar-check-o glyphicon-th"></i></span>
                                    </div>
                                </div>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Số lượng thành viên tham gia</b></label>
                                <input type="number" class="form-control" min="0" step="1" v-model="formEvent.number_member">
                                <span class="form-text text-muted">Nhập 0 nếu không giới hạn số lượng</span>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Điều Kiện Tham Gia</b></label>
                                  <select class="form-control" v-model="formEvent.private">
                                    <option value="1">Chỉ thành viên trong CLB</option>
                                    <option value="0">Tất cả các thành viên</option>
                                </select>
                                <hr/>
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                    </form>
                </div>
                
            </div>
        </div>
        <!-- end: Modal Form -->
    </div>
</div>

<!-- end:: Content -->
</template>

<script>
    export default {
         data() {
            return {
                csrfToken: window.TAWG.csrfToken,
                showErrors:false,
                showSuccess:false,
                onSubmit:false,
                alertMessage:'',
                datatable:'',
                myDropzoneForm:'',
                clubs:'',
                selectEvent:{'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''},
                formEvent:{'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''}
            }
        },
        mounted() {
            this.initDataTable();
            this.settingDropzone();
            this.getClubs();
            this.setEditor();
            this.setDateTimePicker();
        },
        methods: {
            setDateTimePicker:function(){
                $("#kt_datetimepicker_query_from, #kt_datetimepicker_query_to, #kt_datetimepicker_submit_from, #kt_datetimepicker_submit_to").datetimepicker({
                    todayHighlight: !0,
                    autoclose: !0,
                    pickerPosition: "bottom-left",
                    format: "yyyy-mm-dd hh:ii"
                })
            },
            setEditor:function(){
                var _self=this;
                if(CKEDITOR.instances.content)
                {
                 CKEDITOR.instances.content.destroy();
                }
                CKEDITOR.replace('content', {
                    filebrowserImageBrowseUrl: '/filemanager?type=Images',
                    filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token='+_self.csrfToken,
                    filebrowserBrowseUrl: '/filemanager?type=Files',
                    filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='+_self.csrfToken
                });
            },
            getClubs:function(){
                var _self=this;
                axios.post('/admin/club/all')
                .then(function (response) 
                {
                    if(response.data.success)
                    {
                        _self.clubs=response.data.data
                    }
                })
            },
            settingDropzone:function(){
                var _self=this;
                this.myDropzoneForm=new Dropzone("#myDropzoneForm",{
                    url: "/admin/upload", // Set the url for your upload script location
                    paramName: "file", // The name that will be used to transfer the file
                    maxFiles: 1,
                    maxFilesize: 2, // MB
                    addRemoveLinks: true,
                    acceptedFiles: "image/*",
                    headers: {
                        'X-CSRF-TOKEN': _self.csrfToken
                    },
                    success: function(file, response) {
                        _self.onSubmit=false;
                        _self.showErrors=false;
                        _self.alertMessage='';
                        _self.formEvent.image=response.data;
                    },
                    error: function(file, response) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();  
                        if(typeof response.response.data.message != 'undefined'){
                            _self.alertMessage=response.response.data.message;
                        }
                        else{
                            _self.alertMessage=response.message;
                        }
                    }
                });

                this.myDropzoneForm.on("removedfile", function(file) {
                     _self.formEvent.image='';
                });
            },
            initDataTable:function(){
                var _self=this;
                var options = {
                    // datasource definition
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '/admin/event/data',
                                    headers: {
                                        'X-CSRF-TOKEN': _self.csrfToken
                                    },
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
                            selector: false,
                            sortable: false,
                            textAlign: 'center',
                        },
                        {
                            field: 'name',
                            title: 'Tên Sự Kiện',
                            template: function(row) {
                                return row.name+'<br/><p><a href="/admin/event/member/'+row.id+'" class="text-primary view-member">Quản Lý Thành Viên Tham Gia</a></p><p><a href="/admin/event/comment/'+row.id+'" class="text-success view-comment">Quản Lý Bình Luận('+row.comment.length+') <i class="text-danger">'+row.report+' tố cáo</i></a></p>';
                            },
                        },
                         {
                            field: 'club_id',
                            title: 'Câu Lạc Bộ',
                            template: function(row) {
                                return row.club.name
                            },
                        },
                        {
                            field: 'from',
                            title: 'Thời gian',
                            template: function(row) {
                                return '<p>Thời gian bắt đầu: <b>'+row.from+'</b></p><p>Thời gian kết thúc:<b>'+row.to+'</b></p>'
                            }
                        },
                        {
                            field: 'status',
                            title: 'Trạng Thái',
                            textAlign: 'center',
                            template: function(row) {
                                var status,status_button;
                                var private_text,private_button;
                                if(row.status)
                                {
                                   status="Sự kiện đang diễn ra";
                                   status_button="text-success";
                                }
                                else{
                                    status="Sự kiện đã kết thúc";
                                    status_button="text-secondary";
                                }
                                if(row.private)
                                {
                                    private_text="Chỉ dành cho thành viên clb";
                                    private_button="text-danger";
                                }
                                else{
                                    private_text="Dành cho tất cả thành viên";
                                    private_button="text-primary";
                                }
                                return '<p><a href="#" data-id="'+row.id+'" class="status-action '+status_button+'">'+status+'</a></p><p><a href="#" data-id="'+row.id+'" class="private-action '+private_button+'">'+private_text+'</a></p>';
                            },
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
                                \<button type="button" class="btn btn-warning btn-sm btn-icon edit-action" data-id="'+row.id+'"><i class="fa fa-pencil-alt"></i></button>\
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
                        _self.viewEvent(id);
                    });

                    $('.edit-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openEditForm(id);
                    });

                    $('.status-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.changeStatus(id);
                    });

                    $('.private-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.changePrivate(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa sự kiện?',
                          text: "Bạn có chắc rằng muốn xóa sự kiện này, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteEvent(id);
                          }
                        })
                    });
                    
                })
                $('#kt_form_club_id').on('change', function() {
                    _self.datatable.search($(this).val().toLowerCase(), 'club_id');
                });
                $('#kt_datetimepicker_query_from').on('change', function() {
                    console.log($(this).val())
                     _self.datatable.search($(this).val(), 'from');
                })
                $('#kt_datetimepicker_query_to').on('change', function() {
                    console.log($(this).val())
                     _self.datatable.search($(this).val(), 'to');
                })

            },
            openNewForm:function(){
                 this.formEvent={'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''};
                 this.myDropzoneForm.removeAllFiles();
                 CKEDITOR.instances.content.setData('');
                 $('#kt_modal_event_form').modal('show');
            },
            openEditForm:function(id){
                var _self=this;
                 this.formEvent={'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''};
                 this.myDropzoneForm.removeAllFiles();
                 axios.post('/admin/event/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formEvent.id=response.data.data.id;
                            _self.formEvent.name=response.data.data.name;
                            _self.formEvent.description=response.data.data.description;
                            _self.formEvent.club_id=response.data.data.club_id;
                            _self.formEvent.image=response.data.data.image;
                            _self.formEvent.content=response.data.data.content;
                            _self.formEvent.status=response.data.data.status;
                            _self.formEvent.from=response.data.data.from;
                            _self.formEvent.to=response.data.data.to;
                            _self.formEvent.private=response.data.data.private;
                            _self.formEvent.number_member=response.data.data.number_member;
                            CKEDITOR.instances.content.setData(response.data.data.content);
                            $('#kt_datetimepicker_submit_from').val(_self.formEvent.from)
                            $('#kt_datetimepicker_submit_to').val(_self.formEvent.to);
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_event_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_event_form').modal('show'); 
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
            deleteEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectEvent={'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''};
                var _self=this;
                axios.post('/admin/event/delete', {'id':id})
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
                        $('#kt_modal_event_view').modal('hide'); 
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
            viewEvent:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectEvent={'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''};
                var _self=this;

                axios.post('/admin/event/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectEvent.id=response.data.data.id;
                            _self.selectEvent.name=response.data.data.name;
                            _self.selectEvent.club_id=response.data.data.club_id;
                            _self.selectEvent.description=response.data.data.description;
                            _self.selectEvent.image=response.data.data.image;
                            _self.selectEvent.content=response.data.data.content;
                            _self.selectEvent.status=response.data.data.status;
                            _self.selectEvent.from=response.data.data.from;
                            _self.selectEvent.to=response.data.data.to;
                            _self.selectEvent.private=response.data.data.private;
                            _self.selectEvent.number_member=response.data.data.number_member;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_event_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_event_view').modal('hide'); 
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
            submitForm:function(){
                var _self=this;
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                var url='';
                if(this.formEvent.id == null || this.formEvent.id == '')
                {
                    url='/admin/event/create';
                }
                else{
                    url='/admin/event/update';
                }
                this.formEvent.content=CKEDITOR.instances.content.getData();
                this.formEvent.from=$('#kt_datetimepicker_submit_from').val();
                this.formEvent.to=$('#kt_datetimepicker_submit_to').val();
                axios.post(url, this.formEvent) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            if(_self.formEvent.id == null || _self.formEvent.id == '')
                            {
                                _self.showSuccess=true;
                                _self.alertMessage='Thêm sự kiện mới thành công'
                            }
                            else{
                                _self.showSuccess=true;
                                _self.alertMessage='Cập nhật sự kiện mới thành công'
                            }
                            _self.datatable.reload();
                            _self.myDropzoneForm.removeAllFiles();
                            CKEDITOR.instances.content.setData('');
                             _self.formEvent={'id':'','club_id':'', 'name':'', 'description':'', 'image':'','content':'', 'from':'', 'to':'', 'number_member':'', 'status':'', 'private':''};
                            $('#kt_datetimepicker_submit_from').val('');
                            $('#kt_datetimepicker_submit_to').val('');
                            $('#kt_modal_event_form').modal('hide'); 
                        }
                        _self.onSubmit=false;
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        //$('#kt_modal_event_form').modal('hide'); 
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
            changeStatus:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;

                axios.post('/admin/event/status', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                             _self.onSubmit=false;
                             _self.showErrors=false;
                             _self.showSuccess=false;
                             _self.datatable.reload();
                        }
                        
                        _self.onSubmit=false;
          
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_event_view').modal('hide'); 
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
            changePrivate:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;

                axios.post('/admin/event/private_status', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                             _self.onSubmit=false;
                             _self.showErrors=false;
                             _self.showSuccess=false;
                             _self.datatable.reload();
                        }
                        
                        _self.onSubmit=false;
          
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_event_view').modal('hide'); 
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
