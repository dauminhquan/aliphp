<template>
    <div id="content-wrapper">
        <data-table title="Các sản phẩm tìm được"
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
            <div id="info-column" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin cột</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="updateItem">
                            <div class="modal-body">
                                <fieldset class="content-group">

                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Tên cột</label>
                                        <div class="col-lg-10">
                                            <input type="text" required class="form-control" v-model="info.name">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Lưu cột</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <div id="modal-create-column" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin cột</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="createItem">
                            <div class="modal-body">
                                <fieldset class="content-group">

                                    <div class="form-group">

                                        <div class="col-lg-12">
                                            <label class="control-label text-bold" >Tên cột</label>
                                            <input type="text" required class="form-control" placeholder="Điền tên cột" v-model="create.column">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Lưu cột</button>
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

    export default {
        computed:{
            setAll(){
                return this.data.length == this.itemSelected.length
            },
        },
        components: {
            'data-table' : table
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

                            $('#modal-create-column').modal('show')
                        }
                    },
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
                        html:'<a href="javascript:void(0);"><i class="icon-trash"></i> Xóa cột</a>'
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
                showCodeDescription: null,
                info: {},
                excelDownload: {

                },
                imageColorSelected: null,
                infoOld: {},
                create: {
                    nullable : true
                }
            }
        },
        mounted(){
            this.getData()
        },
        methods: {

            getData(){
                var vm = this
                axios.get('/api/resource-product-columns').then(data => {
                    vm.data = data.data

                    vm.columns = [
                        {
                            key: 'id',
                            text: 'Số thứ tự'
                        },
                        {
                            key:'name',
                            text: 'Tên cột'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
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
            updateItem(){
                let vm = this
                axios.put(`/api/resource-product-columns/${vm.infoOld.name}`,{
                    column: vm.info.name
                }).then(data => {
                    console.log(data)
                    alert('Thành công')
                    $('#info-column').modal('hide')
                    vm.getData()
                    vm.infoOld = {}
                    vm.info = {}
                }).catch(err => {
                    console.log(err)
                    alert('Thất bại, vui lòng báo với Dev')
                })
            },
            createItem(){
                let vm = this
                axios.post(`/api/resource-product-columns`,vm.create).then(data => {
                    alert('Thành công')
                    $('#modal-create-column').modal('hide')
                    vm.create = {}
                    vm.getData()
                }).catch(err => {
                    console.log(err)
                    alert('Thất bại, vui lòng báo với Dev')
                })
            },
            deleteItem(){
                let vm = this
                vm.deleting = true

                if(vm.primaryKeyDelete != -1)
                {
                    let cl = null
                    vm.data.forEach(item => {
                        if(item.id == vm.primaryKeyDelete)
                        {
                            cl = item.name
                        }
                    })
                    let indexOf = -1
                    axios.delete('/api/resource-product-columns'+'/'+cl).then(data => {
                        vm.data.forEach((item,index) => {

                            if(item[vm.primaryKey] == vm.primaryKeyDelete)
                            {
                                indexOf = index
                            }
                        })
                        if(indexOf != -1)
                        {
                            vm.data.splice(indexOf,1)

                        }
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                        $('div.checker>span').removeClass('checked')
                    }).catch(err => {
                        console.log(err)
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                        alert('Thất bại. Vui lòng báo lại với DEV')
                    })
                }
                else{
                    vm.deleting = false
                    $('#modal_danger').modal('hide')
                }
            },
            deleteSelected(){
                $('#modal-danger-delete-list').modal('show')
            },
            deleteListItem() {
                let vm = this
                vm.deleting = true
                axios.delete('/api/destroy-products',{
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
                    console.log(err)
                    alert('Có lỗi. Vui lòng báo với dev')

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
                let vm = this
                vm.data.forEach(item => {
                    if(item.id == id)
                    {
                        vm.info = item
                        vm.infoOld.name = item.name
                    }
                })
                $('#info-column').modal('show')
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
