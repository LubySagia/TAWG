<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-categories"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Danh Mục
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
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-categories"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_category_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết Category" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Thông Tin Chi Tiết</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                       <table class="table table-striped">
                            <tr>
                               <td><b>Ảnh Đại Diện</b></td>
                               <td><img :src="selectCategory.image" width="120px;"></td>
                           </tr>
                           <tr>
                               <td><b>Tiêu Đề</b></td>
                               <td>{{selectCategory.title}}</td>
                           </tr>
                           <tr>
                               <td><b>Tóm Tắt</b></td>
                               <td>{{selectCategory.description}}</td>
                           </tr>
                           <tr>
                               <td><b>Thứ tự</b></td>
                               <td>{{selectCategory.order}}</td>
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
        <div class="modal  fade" id="kt_modal_category_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Danh Mục</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formCategory" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
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
                                        <img :src="formCategory.image"  class="img-fluid"/>
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
                                <label><b>Tiêu đề</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Vui lòng nhập tiêu đề" v-model="formCategory.title">
                                <span class="form-text text-muted">Tối đa 500 ký tự</span>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Mô Tả</b></label>
                                <textarea class="form-control" v-model="formCategory.description" rows="5"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Thứ tự</b></label>
                                <input type="number" min="0" max="999" class="form-control" aria-describedby="emailHelp" v-model="formCategory.order">
                                <span class="form-text text-muted">Thứ tự hiển thị khi sử dựng API. Xếp theo tứ tự tăng dần 1 -> n++</span>

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
                selectCategory:{'id':'','title':'','description':'','image':'','order':''},
                formCategory:{'id':'','title':'','description':'','image':'','order':''}
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
                        console.log(response);
                        _self.formCategory.image=response.data;
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
                     _self.formCategory.image='';
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
                                url: '/admin/category/data',
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
                        height: 550, // datatable's body's fixed height
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
                            field: 'title',
                            title: 'Tiêu Đề',
                        },
                        {
                            field: 'order',
                            title: 'Thứ Tự',
                            type: 'number',
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
                        _self.viewCategory(id);
                    });

                    $('.edit-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openEditForm(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa danh mục?',
                          text: "Bạn có chắc rằng muốn xóa danh mục này, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteCategory(id);
                          }
                        })
                    });
                    
                })
                
            },
            openNewForm:function(){
                 this.formCategory={'id':'','title':'','description':'','image':'','order':''};
                 this.myDropzoneForm.removeAllFiles();
                 $('#kt_modal_category_form').modal('show');
            },
            openEditForm:function(id){
                var _self=this;
                 this.formCategory={'id':'','title':'','description':'','image':'','order':''};
                 this.myDropzoneForm.removeAllFiles();
                 axios.post('/admin/category/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formCategory.id=response.data.data.id;
                            _self.formCategory.title=response.data.data.title;
                            _self.formCategory.description=response.data.data.description;
                            _self.formCategory.image=response.data.data.image;
                            _self.formCategory.order=response.data.data.order;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_category_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_category_form').modal('show'); 
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
            deleteCategory:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectCategory={'id':'','title':'','description':'','image':'','order':''};
                var _self=this;
                axios.post('/admin/category/delete', {'id':id})
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
                        $('#kt_modal_category_view').modal('hide'); 
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
            viewCategory:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectCategory={'id':'','title':'','description':'','image':'','order':''};
                var _self=this;

                axios.post('/admin/category/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectCategory.id=response.data.data.id;
                            _self.selectCategory.title=response.data.data.title;
                            _self.selectCategory.description=response.data.data.description;
                            _self.selectCategory.image=response.data.data.image;
                            _self.selectCategory.order=response.data.data.order;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_category_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_category_view').modal('hide'); 
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
                if(this.formCategory.id == null || this.formCategory.id == '')
                {
                    url='/admin/category/create';
                }
                else{
                    url='/admin/category/update';
                }

                axios.post(url, this.formCategory) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            if(_self.formCategory.id == null || _self.formCategory.id == '')
                            {
                                _self.showSuccess=true;
                                _self.alertMessage='Thêm danh mục mới thành công'
                            }
                            else{
                                _self.showSuccess=true;
                                _self.alertMessage='Cập nhật danh mục mới thành công'
                            }
                            _self.datatable.reload();
                            _self.myDropzoneForm.removeAllFiles();
                        }
                        _self.formCategory={'id':'','title':'','description':'','image':'','order':''};
                        _self.onSubmit=false;
                       $('#kt_modal_category_form').modal('hide'); 
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_category_form').modal('hide'); 
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
