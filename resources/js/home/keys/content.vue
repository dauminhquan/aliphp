<template>
    <div id="content-wrapper">
        <data-table title="Danh sách mã sản phẩm"
                    :columns="columns"
                    :data="data"
                    :targets="[]"
                    :buttonConfig="buttonConfig"
                    :resetCheck="resetCheck"
                    :menu="menu"
                    :primaryKey="primaryKey"
                    :pages="totalPage"
                    :lengths="lengths"
                    :setAll="setAll"
                    @selectAll="selectAll"
                    @unSelectAll="unSelectAll"
                    @deleteSelected="deleteSelected"
                    @action="action($event)"
                    @clickedKeyItem="clickedKeyItem"
                    @changePerPage="changePerPage"
                    @changePageSelect="changePageSelect"

        >
            <div id="modal_danger" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title"><i class="icon-warning"></i> Cảnh báo</h6>
                        </div>

                        <div class="modal-body">
                            <p> <i class="icon-warning"></i> Sau khi xóa, mọi dữ liệu liên quan sẽ bị xóa. Bạn nên cân nhắc điều này ! </p>
                            <div style="border: snow" class="panel panel-body border-top-danger text-center">
                                <div class="pace-demo" v-if="deleting == true">
                                    <div class="theme_xbox_xs"><div class="pace_progress" data-progress-text="60%" data-progress="60"></div><div class="pace_activity"></div></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" @click="deleteItem">Xác định xóa</button>

                        </div>
                    </div>
                </div>
            </div>
            <div id="modal-danger-delete-list" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title"><i class="icon-warning"></i> Cảnh báo</h6>
                        </div>

                        <div class="modal-body">

                            <p> <i class="icon-warning"></i> Bạn đang xóa nhiều item. Sau khi xóa, mọi dữ liệu liên quan sẽ bị xóa. Bạn nên cân nhắc điều này ! </p>
                            <div style="border: snow" class="panel panel-body border-top-danger text-center">
                                <div class="pace-demo" v-if="deleting == true">
                                    <div class="theme_xbox_xs"><div class="pace_progress" data-progress-text="60%" data-progress="60"></div><div class="pace_activity"></div></div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" @click="deleteListItem">Xác định xóa</button>

                        </div>
                    </div>
                </div>
            </div>
            <div id="add-key" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thêm key</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="createItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Điền key</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" v-model="create.key" required>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <p v-if="waiting == true">Đang tạo...</p>
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Thêm key</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <div id="create-many-key" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thêm nhiều key</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="createListItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Điền key</label>
                                        <div class="col-lg-10">
                                            <textarea type="text" rows="5" class="form-control" v-model="list_keys" required></textarea>
                                            <p> Mẫu: <span class="text-bold">key1;key2;key3;...</span></p>
                                        </div>

                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <p v-if="waiting == true">Đang tạo...</p>
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Thêm key</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </data-table>
    </div>
</template>
<script>
    import table from './../../components/datatable/table'
    import axios from 'axios'
    import trans from './../config'
    import select2 from './../../components/select2/select2'
    export default {
        computed:{
            setAll(){
                return this.data.length == this.itemSelected.length
            },
        },
        components: {
            'data-table' : table,
            'select2' : select2
        },
        data(){
            return {
                columns : [
                ],
                buttonConfig: [
                    {
                        text: 'Thêm mới',
                        className: 'btn bg-primary',
                        action: function(e, dt, node, config) {

                            $('#add-key').modal('show')
                        }
                    },
                    {
                        text: 'Thêm nhiều key 1 lúc',
                        className: 'btn bg-danger-400',
                        action: function(e, dt, node, config) {

                            $('#create-many-key').modal('show')
                        }
                    }
                ],
                deleting: false,
                data :[],
                menu: [
                    {
                        action :'view',
                        html:'<a href="javascript:void(0);"><i class="icon-info3"></i> Thông tin chi tiết</a>'
                    },
                    {
                        action :'delete',
                        html:'<a href="javascript:void(0);"><i class="icon-trash"></i> Xóa</a>'
                    }
                ],
                primaryKey: 'id',
                lengths: [50,100,200,500,1000,2000,5000],
                itemSelected: [],
                primaryKeyDelete: -1,
                deletedSelectItem: false,
                resetCheck:false,
                uploading: false,
                pages: [],
                page:1,
                totalPage:0,
                perPage:500,
                info: {},
                waiting: false,
                create: {},
                product_columns: [],
                list_keys: '',
                trans: trans
            }
        },
        mounted(){
            $('select').select2()
            this.getData()
        },
        methods: {
            getData(perPage=500,page=1){
                var vm = this
                axios.get('/api/keys'+'?size='+perPage+'&page='+page).then(data => {
                    vm.data = data.data.data
                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key:'key',
                            text: 'Mã'
                        },
                        {
                            key:'created_at',
                            text: 'Ngày tạo'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
                    alert('Có lỗi! Vui lòng báo với dev')
                })
            },
            updateItem(){
                let vm = this
                vm.waiting =true
                axios.put(`/api/keys/${vm.info.id}`,vm.info).then(data=>{
                    vm.getData()
                    alert('Thành công')
                    vm.waiting =false
                    $('#info-column').modal('hide')

                }).catch(err=> {
                    vm.waiting =false
                    alert('Thất bại! Vui lòng báo với developer')

                })
            },
            createItem(){
                let vm = this
                vm.waiting =true
                axios.post(`/api/keys`,vm.create).then(data=>{
                    vm.getData()
                    vm.waiting =false
                    alert('Thành công')
                    $('#create-column').modal('hide')

                }).catch(err=> {
                    alert('Thất bại! Vui lòng báo với developer')
                    vm.waiting =false
                })
            },
            createListItem(){
                let vm = this
                vm.waiting =true
                let list = vm.list_keys.split(';')
                let list_create = []
                list.forEach(item => {
                    let t = item.split(':')
                    list_create.push({
                        key: item
                    })
                })
                axios.post(`/api/create-many-keys`,{
                    keys: list_create
                }).then(data=>{
                    vm.getData()
                    vm.waiting =false
                    $('#create-many-column').modal('hide')
                    if(data.data.list_err.length > 0)
                    {
                        let keyError = []
                        data.data.list_err.forEach(i => {
                            keyError.push(i.key)
                        })
                        alert('Thành công! Danh sách mã đã tồn tại rồi: '+keyError.toString())
                    }
                    else {
                        alert('Thành công')
                    }

                }).catch(err=> {
                    vm.waiting =false
                    alert('Thất bại! Vui lòng báo với dev')
                })


            },
            selectAll(){
                let vm = this
                vm.itemSelected = []
                vm.data.forEach(item => {
                    vm.itemSelected.push(item[vm.primaryKey])
                })
            },
            unSelectAll(){
                this.itemSelected = []
            },
            action(event){
                let vm = this
                if(event[1] == 'delete')
                {
                    vm.primaryKeyDelete = event[0]
                    $('#modal_danger').modal('show')
                }
                if(event[1] == 'view')
                {
                    vm.showItem(event[0])
                }
            },
            deleteItem(){
                let vm = this
                vm.deleting = true

                if(vm.primaryKeyDelete != -1)
                {
                    let indexOf = -1
                    axios.delete('/api/keys'+'/'+vm.primaryKeyDelete).then(data => {
                        vm.data.forEach((item,index) => {

                            if(item[vm.primaryKey] == vm.primaryKeyDelete)
                            {
                                indexOf = index
                            }
                        })
                        if(indexOf != -1)
                        {
                            vm.data.splice(indexOf,1)
                            alert('Thành công')
                        }
                        else
                        {
                            alert('Có lỗi. Hãy load lại trang')
                        }
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                        $('div.checker>span').removeClass('checked')
                    }).catch(err => {
                        console.log(err)
                        alert('Có lỗi từ server. Vui lòng báo lại với developer')
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                    })
                }
                else{
                    vm.deleting = false
                    $('#modal_danger').modal('hide')
                }
            },
            clickedKeyItem(item) {
                let vm =this

                let index = vm.itemSelected.indexOf(item)

                if (index > -1) {
                    vm.itemSelected.splice(index, 1);
                }
                else{
                    vm.itemSelected.push(item)
                }
            },
            deleteSelected(){
                $('#modal-danger-delete-list').modal('show')
            },
            deleteListItem() {
                let vm = this
                vm.deleting = true
                axios.delete('/api/destroy-keys',{
                    params:{
                        id_list: vm.itemSelected
                    }
                }).then(data => {
                    $('#modal-danger-delete-list').modal('hide')
                    vm.deleting = false
                    let list_id = vm.itemSelected
                    let newData = []
                        vm.data.forEach((dt) => {
                            if(!vm.existsItem(dt[vm.primaryKey],list_id))
                            {
                                newData.push(dt)
                            }
                        })

                    vm.data = newData

                    vm.itemSelected = []
                    vm.resetCheck = !vm.resetCheck

                }).catch(err => {
                    $('#modal-danger-delete-list').modal('hide')
                    vm.deleting = false
                    alert('có lỗi xảy ra. Báo lại với dev')
                    console.log(err)
                })
            },
            existsItem(item,Arry){
                let result = false
                Arry.forEach((i) => {
                    if(item == i)
                    {
                        result = true
                    }
                })
                return result
            },
            showItem(id) {
                this.getColumnInfo(id)
                $('#info-column').modal('show')
            },
            getColumnInfo(id){
                let vm = this
                axios.get(`/api/keys/${id}`).then(data => {


                    vm.info = data.data
                    }).catch(err => {
                    console.log(err)
                    alert('Có lỗi! Vui lòng báo cho developer')
                })
            },
            changePerPage(perPage){
                this.getData(perPage)
            },
            changePageSelect(page){
                this.getData(this.perPage,page)
            }
        }
    }
</script>
<style>
    .image-main{
        width: 50%;
        margin: auto;
    }
    .description-content{
        margin: 50px 50px;
    }
    .description-content img{
        width: 100%;
    }
    .color-select{
        border: 1px solid rgba(239,22,63,0.59);
        padding: 2px;
    }
    .image-color:not(.color-select):hover {
        border: 1px solid rgba(149,146,145,0.59);
        padding: 2px;
        width: 49px;
    }
</style>
