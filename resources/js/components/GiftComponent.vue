<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-gifts"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Quà Tặng
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                     <a href="/admin/gift_log" class="btn btn-brand btn-icon-sm">
                       <i class="flaticon-event-calendar-symbol"></i> Quản lý lịch sử
                    </a>  
                    <button type="button" class="ml-3   btn btn-brand btn-icon-sm" v-on:click="openNewForm()">
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
                          
                        </div>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-gifts"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_gift_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết" aria-hidden="true">
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
                               <td width="30%"><b>Ảnh </b></td>
                               <td><img :src="selectGift.image" width="120px;"></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tên</b></td>
                               <td>{{selectGift.name}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Mô Tả</b></td>
                               <td>{{selectGift.description}}</td>
                           </tr>
                            <tr>
                               <td width="30%"><b>Số điểm để đổi quà</b></td>
                               <td>{{selectGift.request_point}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Số lượng quà hiện có</b></td>
                               <td>{{selectGift.request_point}}</td>
                           </tr>
                           <tr>
                            <td colspan="2">
                                <p><b>Lịch sử đổi quà</b></p>
                                <table class="table">
                                    <tr v-for="(item,key) in selectGift.log">
                                        <td>{{item.created_at}}</td>
                                        <td>{{item.user.first_name}}  {{item.user.last_name}} {{(item.user.email)}}</td>
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
        <!-- begin: Modal Form -->
        <div class="modal  fade" id="kt_modal_gift_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Quà Tặng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formGift" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
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
                                <label><b>Ảnh Quà</b></label>
                                <div class="row">
                                    <div class="col-4">
                                        <img :src="formGift.image"  class="img-fluid"/>
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
                                <label><b>Tên</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Vui lòng nhập tên" v-model="formGift.name">
                                <span class="form-text text-muted">Tối đa 500 ký tự</span>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Mô Tả</b></label>
                                <textarea class="form-control" v-model="formGift.description" rows="5"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Số điểm để đổi quà</b><span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control" aria-describedby="emailHelp" v-model="formGift.request_point">
                                
                                <hr/>
                            </div>
                             <div class="form-group">
                                <label><b>Số lượng quà </b><span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control" aria-describedby="emailHelp" v-model="formGift.quantity">
                                
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
                categories:'',
                selectGift:{'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''},
                formGift:{'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''}
            }
        },
        mounted() {
            this.initDataTable();
            this.settingDropzone();
            
        },
        methods: {
           
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
                        _self.formGift.image=response.data;
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
                     _self.formGift.image='';
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
                                url: '/admin/gift/data',
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
                            title: 'Tên',
                        },
                        {
                            field: 'image',
                            title: 'Danh Mục',
                            template: function(row) {
                                return '<img width="100px" src="'+row.image+'"/>'
                            },
                        },
                        {
                            field: 'description',
                            title: 'Mô tả',
                        },
                        {
                            field: 'request_point',
                            title: 'Điểm Đổi Quà',
                        },
                        {
                            field: 'quantity',
                            title: 'Số Lượng',
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
                        _self.viewGift(id);
                    });

                    $('.edit-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openEditForm(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa quà tặng?',
                          text: "Bạn có chắc rằng muốn xóa quà tặng này, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteGift(id);
                          }
                        })
                    });
                    
                })
                

            },
            openNewForm:function(){
                 this.formGift={'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''};
                 this.myDropzoneForm.removeAllFiles();
                 $('#kt_modal_gift_form').modal('show');
            },
            openEditForm:function(id){
                var _self=this;
                 this.formGift={'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''};
                 this.myDropzoneForm.removeAllFiles();
                 axios.post('/admin/gift/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formGift.id=response.data.data.id;
                            _self.formGift.name=response.data.data.name;
                            _self.formGift.description=response.data.data.description;
                            _self.formGift.request_point=response.data.data.request_point;
                            _self.formGift.image=response.data.data.image;
                            _self.formGift.quantity=response.data.data.quantity;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_gift_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_gift_form').modal('show'); 
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
            deleteGift:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectGift={'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''};
                var _self=this;
                axios.post('/admin/gift/delete', {'id':id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Xóa quà tặng thành công';
                        $(".alert").focus();
                        _self.datatable.reload();
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_gift_view').modal('hide'); 
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
            viewGift:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectGift={'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''};
                var _self=this;

                axios.post('/admin/gift/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectGift.id=response.data.data.id;
                            _self.selectGift.name=response.data.data.name;
                            _self.selectGift.description=response.data.data.description;
                            _self.selectGift.request_point=response.data.data.request_point;
                            _self.selectGift.image=response.data.data.image;
                            _self.selectGift.quantity=response.data.data.quantity;
                            _self.selectGift.log=response.data.data.log;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_gift_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_gift_view').modal('hide'); 
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
                if(this.formGift.id == null || this.formGift.id == '')
                {
                    url='/admin/gift/create';
                }
                else{
                    url='/admin/gift/update';
                }
               
                axios.post(url, this.formGift) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            if(_self.formGift.id == null || _self.formGift.id == '')
                            {
                                _self.showSuccess=true;
                                _self.alertMessage='Thêm quà tặng mới thành công'
                            }
                            else{
                                _self.showSuccess=true;
                                _self.alertMessage='Cập nhật quà tặng mới thành công'
                            }
                            _self.datatable.reload();
                            _self.myDropzoneForm.removeAllFiles();

                        }
                        _self.formGift={'id':'','name':'','image':'','description':'','request_point':'','quantity':'','log':''};
                        _self.onSubmit=false;
                       $('#kt_modal_gift_form').modal('hide'); 
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_gift_form').modal('hide'); 
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
