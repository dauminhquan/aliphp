<template>
    <div id="modal_default" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Đổi mật khẩu</h5>
                </div>

                <form @submit.prevent="changePassword">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Mật khẩu cũ</label>
                                    <input type="password" placeholder="Nhập mật khẩu cũ" v-model="info.oldPassword" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>Nhập mật khẩu mới</label>
                                    <input type="password" v-model="info.newPassword" placeholder="Nhập mật khẩu mới" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                        <button type="submit"  class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        data(){
            return {
                info: {

                }
            }
        },
        methods:{
            changePassword(){
                axios.post('/api/change-password',this.info).then(data => {
                    alert('Đổi pass thành công')
                    console.log(data)
                }).catch(err => {
                    console.log(err)
                    alert('Mật khẩu cũ sai')
                })
            }
        }
    }
</script>