<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-users"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Thành Viên
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
                            <!-- <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Status:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_status">
                                            <option value="">All</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Delivered</option>
                                            <option value="3">Canceled</option>
                                            <option value="4">Success</option>
                                            <option value="5">Info</option>
                                            <option value="6">Danger</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-form__group kt-form__group--inline">
                                    <div class="kt-form__label">
                                        <label>Type:</label>
                                    </div>
                                    <div class="kt-form__control">
                                        <select class="form-control bootstrap-select" id="kt_form_type">
                                            <option value="">All</option>
                                            <option value="1">Online</option>
                                            <option value="2">Retail</option>
                                            <option value="3">Direct</option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-users"></div>

            <!--end: Datatable -->
        </div>
        <!-- begin: Modal Form -->
        <div class="modal  fade" id="kt_modal_point_form" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết " aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">+/- Điểm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form name="formPoint" role="form"  class="kt-form" method="POST" enctype="multipart/form-data" v-on:submit.prevent="submitPointForm">
                    <div class="modal-body">
                        <div class="kt-portlet__body">
                             <div class="form-group">
                                <label><b>Cộng trừ Điểm </b><span class="text-danger">*</span></label>
                                <input type="number" class="form-control"  v-model="formPoint.point">
                                <span class="text-danger">Nhập số âm nếu muốn trừ điểm</span>
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
            <!-- Modal -->
        <div class="modal fade" id="kt_modal_user_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết User" aria-hidden="true">
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
                               <td><img :src="selectUser.avatar" width="120px;"></td>
                           </tr>
                           <tr>
                               <td><b>Họ</b></td>
                               <td>{{selectUser.last_name}}</td>
                           </tr>
                           <tr>
                               <td><b>Tên</b></td>
                               <td>{{selectUser.first_name}}</td>
                           </tr>
                           <tr>
                               <td><b>Email</b></td>
                               <td>{{selectUser.email}}</td>
                           </tr>
                           <tr>
                               <td><b>Số ĐT</b></td>
                               <td>{{selectUser.phone}}</td>
                           </tr>
                           <tr>
                               <td><b>Giới Tính</b></td>
                               <td>{{selectUser.gender}}</td>
                           </tr>
                           <tr>
                               <td><b>Ngày Sinh</b></td>
                               <td>{{selectUser.birthday}}</td>
                           </tr>
                           <tr>
                               <td><b>Địa Chỉ</b></td>
                               <td>{{selectUser.address}}</td>
                           </tr>
                           <tr>
                               <td><b>Công Việc</b></td>
                               <td>{{selectUser.job}}</td>
                           </tr>
                            <tr>
                               <td><b>Trình Độ</b></td>
                               <td>{{selectUser.level}}</td>
                           </tr>
                           <tr>
                               <td><b>Sở Thích</b></td>
                               <td>{{selectUser.hobby}}</td>
                           </tr>
                           <tr>
                               <td><b>Điểm</b></td>
                               <td>{{selectUser.point}}</td>
                           </tr>
                       </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
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
                formPoint:{'id':'','point':''},
                selectUser:{'id':'','first_name':'','last_name':'','email':'','avatar':'','phone':'','gender':'','birthday':'','address':'','job':'','level':'','hobby':'','point':''}
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
                                url: '/admin/user/data',
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
                            field: 'last_name',
                            title: 'Họ',
                        },
                        {
                            field: 'first_name',
                            title: 'Tên',
                        },
                        {
                            field: 'email',
                            title: 'Email',
                            sortable: false,
                        },
                        {
                            field: 'phone',
                            title: 'Số ĐT',
                            sortable: false,
                        },
                        {
                            field: 'point',
                            title: 'Điểm',                            
                            template: function(row) {
                                return row.point + '<br/><button type="button" class="btn btn-primary btn-sm btn-icon point-action" data-id="'+row.id+'">+/- Điểm</button>';
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
                                var block='';
                                var block_class='';
                                if(row.is_block)
                                {
                                    block='fa-lock';
                                    block_class='btn-dark';
                                }
                                else{
                                    block='fa-lock-open';
                                    block_class='btn-warning text-white';
                                }
                                return '\
                                \<button type="button" class="btn btn-primary btn-sm btn-icon view-action" data-id="'+row.id+'"><i class="fa fa-eye"></i></button>\
                                \<button type="button" class="btn '+block_class+' btn-sm btn-icon block-action" data-id="'+row.id+'"><i class="fa '+block+'"></i></button>\
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
                        _self.viewUser(id);
                    });

                    $('.block-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.blockUser(id);
                    });

                    $('.point-action').click(function(){
                         var id = $(this).attr('data-id');
                        _self.openPointUser(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa thành viên?',
                          text: "Bạn có chắc rằng muốn xóa thành viên này, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteUser(id);
                          }
                        })
                    });
                    
                })
                
            },
            deleteUser:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectUser={'id':'','first_name':'','last_name':'','email':'','avatar':'','phone':'','gender':'','birthday':'','address':'','job':'','level':'','hobby':'','point':''};
                var _self=this;
                axios.post('/admin/user/delete', {'id':id})
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
                        $('#kt_modal_user_view').modal('hide'); 
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
            viewUser:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectUser={'id':'','first_name':'','last_name':'','email':'','avatar':'','phone':'','gender':'','birthday':'','address':'','job':'','level':'','hobby':'','point':''};
                var _self=this;
                axios.post('/admin/user/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectUser.id=response.data.data.id;
                            _self.selectUser.first_name=response.data.data.first_name;
                            _self.selectUser.last_name=response.data.data.last_name;
                            _self.selectUser.last_name=response.data.data.last_name;
                            _self.selectUser.email=response.data.data.email;
                            _self.selectUser.avatar=response.data.data.avatar;
                            _self.selectUser.phone=response.data.data.phone;
                            _self.selectUser.gender=response.data.data.gender;
                            _self.selectUser.birthday=response.data.data.birthday;
                            _self.selectUser.address=response.data.data.address;
                            _self.selectUser.job=response.data.data.job;
                            _self.selectUser.level=response.data.data.level;
                            _self.selectUser.hobby=response.data.data.hobby;
                            _self.selectUser.point=response.data.data.point;
                        }
                        

                        _self.onSubmit=false;
                        $('#kt_modal_user_view').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_user_view').modal('hide'); 
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
            blockUser:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectUser={'id':'','first_name':'','last_name':'','email':'','avatar':'','phone':'','gender':'','birthday':'','address':'','job':'','level':'','hobby':'','point':''};
                var _self=this;
                axios.post('/admin/user/block', {'id':id})
                    .then(function (response) 
                    {
                        _self.onSubmit=false;
                        _self.showSuccess=true;
                        _self.alertMessage='Khóa tài khoản thành công';
                        $(".alert").focus();
                        _self.datatable.reload();
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_user_view').modal('hide'); 
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
            openPointUser:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.formPoint={'id':'','point':''};
                var _self=this;
                axios.post('/admin/user/view', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.formPoint.id=response.data.data.id;
                        }
                        
                        _self.onSubmit=false;
                        $('#kt_modal_point_form').modal('show');
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_point_form').modal('hide'); 
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
            submitPointForm(){
                var _self=this;
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
 
                axios.post('/admin/user/point', this.formPoint) .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.alertMessage='Cập nhật điểm thành công'
                            
                            _self.datatable.reload();

                        }
                        _self.formPoint={'id':'','point':''};
                        _self.onSubmit=false;
                       $('#kt_modal_point_form').modal('hide'); 
                    }).catch(function (errors) {
                        _self.onSubmit=false;
                        _self.showErrors=true;
                        $(".alert").focus();   
                        $('#kt_modal_point_form').modal('hide'); 
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
