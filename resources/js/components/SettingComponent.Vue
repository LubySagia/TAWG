<template>
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <!--Begin::user-->
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                    Căì Đặt
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body ">
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

        <div class="form-group row">
            <label class="col-lg-3 text-lg-right col-form-label"><b>Điểm khi được điểm danh:</b></label>
            <div class="col-lg-6">
            <input class="form-control" type="number" step="1" v-model="formSettingUpdate.check_in_point" />
            </div>
        </div>
       
        
        </div>
        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <div class="row">
                    <div class="col-lg-3 col-xl-3">
                    </div>
                    <div class="col-lg-9 col-xl-9">
                        <button @click="updateSetting()" class="btn btn-success">Lưu</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--End::user -->
</template>

<script>
    export default {
        data: function()
        {
            return {
                formSettingUpdate : {'check_in_point':''},
                showErrors:false,
                showSuccess:false,
                onSubmit:false,
                alertMessage:'',
                csrfToken: window.TAWG.csrfToken,
            }
        },
        mounted() {
            let _self = this;
            this.getSetting();
           
        },
        methods: {
             
            getSetting(){
                let _self = this;
                axios.post('/admin/setting/view')
                  .then(function (response) {
    
                    _self.formSettingUpdate.check_in_point = response.data.data.check_in_point;
                    console.log(_self.formSettingUpdate);
                  })
            },

            updateSetting(){
                let _self = this;
                this.onSubmit=true;
                this.showSuccess=false;
                this.showErrors=false;
                this.alertMessage='';
                   axios.post('/admin/setting/update',this.formSettingUpdate)
                  .then(function (response) {
                        if(response.data.success)
                        {
                            _self.getSetting()
                            _self.onSubmit=false;
                            _self.showSuccess=true;
                            _self.alertMessage='Cập nhật thành công';
                        }
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
            }
        }
    }
</script>
