<template>
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-suggestions"></i>
                    
                </span>
                <h3 class="kt-portlet__head-title">
                    Danh Sách Góp ý CLB
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
                    <!-- <div class="col-xl-8 order-2 order-xl-1">
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
                    </div> -->
                </div>
            </div>

            <!--end: Search Form -->

            
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit">

            <!--begin: Datatable -->
            <div class="kt-datatable" id="dt-suggestions"></div>

            <!--end: Datatable -->
        </div>
            <!-- Modal -->
        <div class="modal fade" id="kt_modal_suggestion_view" tabindex="-1" role="dialog" aria-labelledby="Thông tin chi tiết SuggestionClub" aria-hidden="true">
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
                                <td>
                                    {{selectSuggestionClub.created_at}}
                                </td>
                            </tr>
                           <tr>
                               <td v-html="selectSuggestionClub.content"> </td>
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
        props: ['club_id'],
        data() {
            return {
                csrfToken: window.TAWG.csrfToken,
                showErrors:false,
                showSuccess:false,
                onSubmit:false,
                alertMessage:'',
                datatable:'',
                selectSuggestionClub:{'id':'', 'suggestion_id':'','suggestion':'', 'club_id':'','club':'', 'content':'','created_at':''}
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
                                url: '/admin/club/suggestion_list/'+_self.club_id,
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
                            selector: false,
                            sortable: false,
                            textAlign: 'center',
                        },
                        {
                            field: 'suggestion',
                            title: 'Thành Viên',
                            template: function(row) {
                               return row.user.last_name+ ' ' +row.user.first_name+'('+row.user.email+')';
                            }
                        },
                        {
                            field: 'content',
                            title: 'Nội Dung',
                            sortable: false,
                        },
                        {
                            field: 'created_at',
                            title: 'Ngày Góp ý'
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
                        _self.viewSuggestionClub(id);
                    });

                    $('.delete-action').click(function(){
                        var id = $(this).attr('data-id');
                        Swal.fire({
                          title: 'Xóa góp ý?',
                          text: "Bạn có chắc rằng muốn xóa góp ý này khỏi CLB, dữ liệu sẽ không thể khôi phục lại sau khi đã xóa.",
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Xác Nhận'
                        }).then((result) => {
                          if (result.value) {
                            _self.deleteSuggestionClub(id);
                          }
                        })
                    });
                    
                })
                
            },
            deleteSuggestionClub:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectSuggestionClub={'id':'', 'suggestion_id':'','suggestion':'', 'club_id':'','club':'', 'content':''};
                var _self=this;
                axios.post('/admin/club/delete_suggestion', {'suggestion_id':id,'club_id':this.club_id})
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
            viewSuggestionClub:function(id){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                this.selectSuggestionClub={'id':'', 'suggestion_id':'','suggestion':'', 'club_id':'','club':'', 'content':''};
                var _self=this;
                axios.post('/admin/club/view_suggestion', {'id':id})
                    .then(function (response) 
                    {
                        if(response.data.success)
                        {
                            _self.selectSuggestionClub.id=response.data.data.id;
                            _self.selectSuggestionClub.suggestion_id=response.data.data.suggestion_id;
                            _self.selectSuggestionClub.club_id=response.data.data.club_id;
                            _self.selectSuggestionClub.content=response.data.data.content;
                            _self.selectSuggestionClub.created_at=response.data.data.created_at;
                            
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
            }
        }
        
    }
</script>
