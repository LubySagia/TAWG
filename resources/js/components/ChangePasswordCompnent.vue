<template>
    <div>
     <form class="kt-form kt-form--label-right" method="POST" enctype="multipart/form-data" v-on:submit.prevent="changePassword">
            <div class="kt-portlet__body">
                <div class="kt-section kt-section--first">
                    <div class="kt-section__body">
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
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Mật Khẩu Hiện Tại<span class="text-danger">*</span></label>
                            <div class="col-lg-9 col-xl-6">
                                <input type="password" v-model="formChangePassword.oldpassword" class="form-control" value="" placeholder="Vui lòng nhập mật khẩu hiện tại">
                               
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Mật Khẩu Mới<span class="text-danger">*</span></label>
                            <div class="col-lg-9 col-xl-6">
                                <input type="password" v-model="formChangePassword.password" class="form-control" value="" placeholder="Vui lòng nhập mật khẩu mới">
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            <label class="col-xl-3 col-lg-3 col-form-label">Nhập Lại Mật Khẩu Mới<span class="text-danger">*</span></label>
                            <div class="col-lg-9 col-xl-6">
                                <input type="password" v-model="formChangePassword.password_confirmation" class="form-control" value="" placeholder="Vui lòng nhập lại mật khẩu hiện tại">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <div class="row">
                        <div class="col-lg-3 col-xl-3">
                        </div>
                        <div class="col-lg-9 col-xl-9">
                            <button type="submit" v-bind:disabled="onSubmit"  class="btn btn-brand btn-bold">Đổi Mật Khẩu</button>&nbsp;
                            <button type="reset" class="btn btn-secondary">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
         data() {
            return {
                csrfToken: window.TAWG.csrfToken,
                formErrors:{},
                showErrors:false,
                showSuccess:false,
                onSubmit:false,
                alertMessage:'',
                formChangePassword : {'password':'','oldpassword':'','password_confirmation':''}
            }
        },
        mounted() {
            
        },
        methods: {
            /**
             * [changePassword submit value to change password]
             * 
             */
            changePassword:function(){
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                var _self=this;
                axios.post('/admin/change-password', this.formChangePassword)
                .then(function (response) 
                {
                    _self.formChangePassword={'password':'','oldpassword':'','password_confirmation':''};
                    _self.onSubmit=false;
                    _self.showSuccess=true;
                    $(".alert").focus();
                     _self.alertMessage='Đổi mật khẩu thành công';
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
