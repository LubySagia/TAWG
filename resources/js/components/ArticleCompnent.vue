<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-articles"></i>
                    
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
                                        <label>Danh mục:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_category_id">
                                            <option value="">Tất Cả</option>
                                            
                                            <option v-for="(item,index) in categories" :value="item.id">{{ item.title }}</option>
                                        </select>
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
            <div class="kt-datatable" id="dt-articles"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_article_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết" aria-hidden="true">
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
                               <td  width="30%"><b>Ảnh Đại Diện</b></td>
                               <td><img :src="selectArticle.image" width="120px;"></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tiêu Đề</b></td>
                               <td>{{selectArticle.title}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tóm Tắt</b></td>
                               <td>{{selectArticle.description}}</td>
                           </tr>
                            <tr>
                               <td width="30%"><b>Nội Dung </b></td>
                               <td><div v-html="selectArticle.content"></div></td>
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
        <div class="modal  fade" id="kt_modal_article_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Tin Tức</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formArticle" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
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
                                        <img :src="formArticle.image"  class="img-fluid"/>
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
                                <label><b>Danh Mục Tin Tức</b><span class="text-danger">*</span></label>
                                <select class="form-control" v-model="formArticle.category_id">
                                    <option v-for="(item,index) in categories" :value="item.id">{{ item.title }}</option>
                                </select>
                                
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Tiêu đề</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Vui lòng nhập tiêu đề" v-model="formArticle.title">
                                <span class="form-text text-muted">Tối đa 500 ký tự</span>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Mô Tả</b></label>
                                <textarea class="form-control" v-model="formArticle.description" rows="5"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group form-group-last">
                                <label><b>Nội Dung</b></label>
                                <textarea class="form-control" v-model="formArticle.content" rows="5" id="content"></textarea>
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
                selectArticle:{'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''},
                formArticle:{'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''}
            }
        },
        mounted() {
            this.initDataTable();
            this.settingDropzone();
            this.getCategory();
            this.setEditor();
        },
        methods: {
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
            getCategory:function(){
                var _self=this;
                axios.post('/admin/category/all')
                .then(function (response) 
                {
                    if(response.data.success)
                    {
                        _self.categories=response.data.data
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
                        _self.formArticle.image=response.data;
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
                     _self.formArticle.image='';
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
                                url: '/admin/article/data',
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
                            field: 'category_id',
                            title: 'Danh Mục',
                            template: function(row) {
                                return row.categories.title
                            },
                        },
                        {
                            field: 'description',
                            title: 'Tóm Tắt',
                        },
                        {
                            field: 'is_hot',
                            title: 'Nổi Bật',
                            template: function(row) {
                                if(row.is_hot)
                                {
                                    return '<a href="#" data-id="'+row.id+'" class="hot-action text-danger"><i class="fas fa-fire"></i></a>';
                                }
                                else{
                                    return '<a href="#" data-id="'+row.id+'" class="hot-action text-secondary"><i class="fas fa-fire"></i></a>';
                                }
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
                        _self.viewArticle(id);
                    });

                    $('.edit-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openEditForm(id);
                    });

                    $('.hot-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.changeHot(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa tin tức?',
                          text: "Bạn có chắc rằng muốn xóa tin tức này, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteArticle(id);
                          }
                        })
                    });
                    
                })
                $('#kt_form_category_id').on('change', function() {
                    _self.datatable.search($(this).val().toLowerCase(), 'category_id');
                });

            },
            openNewForm:function(){
                 this.formArticle={'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''};
                 this.myDropzoneForm.removeAllFiles();
                 CKEDITOR.instances.content.setData('');
                 $('#kt_modal_article_form').modal('show');
            },
            openEditForm:function(id){
                var _self=this;
                 this.formArticle={'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''};
                 this.myDropzoneForm.removeAllFiles();
                 axios.post('/admin/article/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formArticle.id=response.data.data.id;
                            _self.formArticle.title=response.data.data.title;
                            _self.formArticle.description=response.data.data.description;
                            _self.formArticle.category_id=response.data.data.category_id;
                            _self.formArticle.image=response.data.data.image;
                            _self.formArticle.content=response.data.data.content;
                            _self.formArticle.status=response.data.data.status;
                            CKEDITOR.instances.content.setData(response.data.data.content);
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_article_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_article_form').modal('show'); 
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
            deleteArticle:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectArticle={'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''};
                var _self=this;
                axios.post('/admin/article/delete', {'id':id})
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
                        $('#kt_modal_article_view').modal('hide'); 
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
            viewArticle:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectArticle={'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''};
                var _self=this;

                axios.post('/admin/article/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectArticle.id=response.data.data.id;
                            _self.selectArticle.title=response.data.data.title;
                            _self.selectArticle.category_id=response.data.data.category_id;
                            _self.selectArticle.description=response.data.data.description;
                            _self.selectArticle.image=response.data.data.image;
                            _self.selectArticle.content=response.data.data.content;
                            _self.selectArticle.status=response.data.data.status;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_article_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_article_view').modal('hide'); 
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
                if(this.formArticle.id == null || this.formArticle.id == '')
                {
                    url='/admin/article/create';
                }
                else{
                    url='/admin/article/update';
                }
                this.formArticle.content=CKEDITOR.instances.content.getData();
                axios.post(url, this.formArticle) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            if(_self.formArticle.id == null || _self.formArticle.id == '')
                            {
                                _self.showSuccess=true;
                                _self.alertMessage='Thêm tin tức mới thành công'
                            }
                            else{
                                _self.showSuccess=true;
                                _self.alertMessage='Cập nhật tin tức mới thành công'
                            }
                            _self.datatable.reload();
                            _self.myDropzoneForm.removeAllFiles();
                            CKEDITOR.instances.content.setData('');
                        }
                        _self.formArticle={'id':'','title':'','category_id':'','description':'','image':'','content':'','is_hot':'','status':''};
                        _self.onSubmit=false;
                       $('#kt_modal_article_form').modal('hide'); 
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_article_form').modal('hide'); 
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
            changeHot:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';

                var _self=this;

                axios.post('/admin/article/hot', {'id':id})
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
                        $('#kt_modal_article_view').modal('hide'); 
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
