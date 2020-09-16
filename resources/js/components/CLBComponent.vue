<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-clbs"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách CLB
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
                           <div class="alert alert-danger" role="alert" v-if="showErrors==true">
                                <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                <div class="alert-text">{{alertMessage}}</div>
                                <div class="alert-close">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true"><i class="la la-close"></i></span>
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-success" role="alert" v-if="showSuccess==true">
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
            <div class="kt-datatable" id="dt-clbs"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal View -->
        <div class="modal fade" id="kt_modal_clb_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết" aria-hidden="true">
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
                               <td  width="30%"><b>Ảnh CLB</b></td>
                               <td><img :src="selectClub.image" width="120px;"></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tên</b></td>
                               <td>{{selectClub.name}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Tóm Tắt</b></td>
                               <td>{{selectClub.description}}</td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Nội Dung </b></td>
                               <td><div v-html="selectClub.content"></div></td>
                           </tr>
                           <tr>
                               <td width="30%"><b>Nhóm Trưởng CLB</b></td>
                               <td>
                                   <button v-on:click="openAddLeader(selectClub.id)" class="btn btn-primary">Thêm Nhóm Trưởng</button>
                                   <br/>
                                  <table class="table" v-if="selectClub.leaders!=null">
                                    <tr v-for="(item,index) in selectClub.leaders">
                                         <td >
                                          {{item.user.last_name}} {{item.user.first_name}} ({{item.user.email}})
                                      </td>
                                      <td>
                                           <button v-on:click="deleteLeader(selectClub.id,item.user.id)" class="btn btn-danger btn-icon"><i class="fas fa-times"></i></button>
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
        <!-- begin: Modal Form -->
        <div class="modal  fade" id="kt_modal_clb_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">CLB</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formClub" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitForm">
                    <div class="modal-body">
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <div class="col-12">
                                   <div class="alert alert-danger" role="alert" v-if="showErrors==true">
                                        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                                        <div class="alert-text">{{alertMessage}}</div>
                                        <div class="alert-close">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="alert alert-success" role="alert" v-if="showSuccess==true">
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
                                <label><b>Ảnh CLB</b></label>
                                <div class="row">
                                    <div class="col-4">
                                        <img :src="formClub.image"  class="img-fluid"/>
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
                                <input type="text" class="form-control" aria-describedby="emailHelp" placeholder="Vui lòng nhập tiêu đề" v-model="formClub.name">
                                <span class="form-text text-muted">Tối đa 500 ký tự</span>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Mô Tả Ngắn</b></label>
                                <textarea class="form-control" v-model="formClub.description" rows="5"></textarea>
                                <hr/>
                            </div>
                            <div class="form-group">
                                <label><b>Mô Tả Chi Tiết</b></label>
                                <textarea class="form-control" v-model="formClub.content" rows="5" id="content"></textarea>
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
        <!-- begin: Modal Form -->
        <div class="modal  fade" id="kt_modal_leader_club__form" tabindex="-1" role="dialog" aria-labelledby="Thêm nhóm trưởng" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Nhóm Trưởng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                  
                    <div class="modal-body">
                        <form name="formLeaderClub" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitAddForm">
                        <div class="kt-portlet__body">
                            <div class="form-group">
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
                            <div class="form-group">
                                <label><b>Email Nhóm Trưởng</b><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Vui lòng nhập email nhóm trưởng" v-model="formAddLeader.email">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Thêm nhóm trưởng</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    </div>
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
                selectClub:{'id':'','name':'','description':'','image':'','content':'','status':'','leaders':''},
                formClub:{'id':'','name':'','description':'','image':'','content':'','status':''},
                formAddLeader:{'email':'','club_id':''}
            }
        },
        mounted() {
            this.initDataTable();
            this.settingDropzone();
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
                        _self.formClub.image=response.data;
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
                     _self.formClub.image='';
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
                                url: '/admin/club/data',
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
                            field: 'description',
                            title: 'Tóm Tắt',
                        },
                        {
                            field: 'members',
                            title: 'Thành Viên',
                            template: function(row) {
                                return '<p>Tổng số thành viên: <b>'+row.members.length+'</b></p><a href="/admin/club/club_member/'+row.id+'">Xem danh sách thành viên</a>';
                            }
                        },
                        {
                            field: 'suggestions',
                            title: 'Góp Ý',
                            template: function(row) {
                                return '<p>Tổng số góp ý: <b>'+row.suggestions.length+'</b></p><a href="/admin/club/club_suggestion/'+row.id+'">Xem danh sách góp ý</a>';
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
                        _self.viewClub(id);
                    });

                    $('.edit-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openEditForm(id);
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
                            _self.deleteClub(id);
                          }
                        })
                    });
                    
                })
                $('#kt_form_category_id').on('change', function() {
                    _self.datatable.search($(this).val().toLowerCase(), 'category_id');
                });

            },
            openNewForm:function(){
                 this.formClub={'id':'','name':'','description':'','image':'','content':'','status':''};
                 this.myDropzoneForm.removeAllFiles();
                 CKEDITOR.instances.content.setData('');
                 $('#kt_modal_clb_form').modal('show');
            },
            openEditForm:function(id){
                var _self=this;
                 this.formClub={'id':'','name':'','description':'','image':'','content':'','status':''};
                 this.myDropzoneForm.removeAllFiles();
                 axios.post('/admin/club/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formClub.id=response.data.data.id;
                            _self.formClub.name=response.data.data.name;
                            _self.formClub.description=response.data.data.description;
                            _self.formClub.image=response.data.data.image;
                            _self.formClub.content=response.data.data.content;
                            _self.formClub.status=response.data.data.status;
                            CKEDITOR.instances.content.setData(response.data.data.content);
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_clb_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_clb_form').modal('show'); 
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
            deleteClub:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectClub={'id':'','name':'','description':'','image':'','content':'','status':'','leaders':''};
                var _self=this;
                axios.post('/admin/club/delete', {'id':id})
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
                        $('#kt_modal_clb_view').modal('hide'); 
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
            viewClub:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectClub={'id':'','name':'','description':'','image':'','content':'','status':'','leaders':''};
                var _self=this;

                axios.post('/admin/club/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectClub.id=response.data.data.id;
                            _self.selectClub.name=response.data.data.name;
                            _self.selectClub.description=response.data.data.description;
                            _self.selectClub.image=response.data.data.image;
                            _self.selectClub.content=response.data.data.content;
                            _self.selectClub.status=response.data.data.status;
                            _self.selectClub.leaders=response.data.data.leaders;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_clb_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_clb_view').modal('hide'); 
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
                if(this.formClub.id == null || this.formClub.id == '')
                {
                    url='/admin/club/create';
                }
                else{
                    url='/admin/club/update';
                }
                this.formClub.content=CKEDITOR.instances.content.getData();
                axios.post(url, this.formClub) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            if(_self.formClub.id == null || _self.formClub.id == '')
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
                        _self.formClub={'id':'','name':'','description':'','image':'','content':'','status':''};
                        _self.onSubmit=false;
                       $('#kt_modal_clb_form').modal('hide'); 
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_clb_form').modal('hide'); 
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
            openAddLeader:function(club_id){
                this.formAddLeader.club_id=club_id;
                $('#kt_modal_leader_club__form').modal('show');
            },
            submitAddForm:function(){
                var _self=this;
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                axios.post('/admin/club/add_leader', this.formAddLeader) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.showSuccess=true;
                            _self.showErrors=false;
                            _self.alertMessage='Thêm nhóm trưởng thành công';
                            _self.selectClub.id=response.data.data.id;
                            _self.selectClub.name=response.data.data.name;
                            
                            _self.selectClub.description=response.data.data.description;
                            _self.selectClub.image=response.data.data.image;
                            _self.selectClub.content=response.data.data.content;
                            _self.selectClub.status=response.data.data.status;
                            _self.selectClub.leaders=response.data.data.leaders;
                             _self.datatable.reload();
                        }
                        _self.onSubmit=false;
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
            deleteLeader:function(club_id,user_id){
                var _self=this;
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                axios.post('/admin/club/delete_leader', {club_id:club_id,user_id:user_id}) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.showSuccess=true;
                            _self.showErrors=false;
                            _self.alertMessage='Xóa nhóm trưởng thành công';
                            _self.selectClub.id=response.data.data.id;
                            _self.selectClub.name=response.data.data.name;
                            _self.selectClub.description=response.data.data.description;
                            _self.selectClub.image=response.data.data.image;
                            _self.selectClub.content=response.data.data.content;
                            _self.selectClub.status=response.data.data.status;
                            _self.selectClub.leaders=response.data.data.leaders;
                             _self.datatable.reload();
                        }
                        _self.onSubmit=false;
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
