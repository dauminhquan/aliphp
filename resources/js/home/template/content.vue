<template>
    <div id="content-wrapper">
        <data-table title="Danh sách sản phẩm chuẩn bị xuất Excel"
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

                            <p> <i class="icon-warning"></i> Bạn đang xóa nhiều sinh viên. Sau khi xóa, mọi dữ liệu liên quan sẽ bị xóa. Bạn nên cân nhắc điều này ! </p>
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
            <div id="wait-add-product" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-info">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h6 class="modal-title"><i class="icon-rocket"></i> Waiting</h6>
                        </div>

                        <div class="modal-body">

                            <p> <i class="icon-rocket"></i> <span > {{textAddProduct}} </span> </p>
                            <div style="border: snow" class="panel panel-body border-top-danger text-center">
                                <div class="pace-demo" v-if="waiting == true">
                                    <div class="theme_xbox_xs"><div class="pace_progress" data-progress-text="60%" data-progress="60"></div><div class="pace_activity"></div></div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="info-product" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin chi tiết sản phẩm</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="updateItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Tên template</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" required v-model="info.name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các cột</label>
                                        <div class="col-lg-10">
                                            <div class="form-group" v-for="column in info.columns" @key="column.id">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <select class="form-control" required :value="column.id">
                                                            <option v-for="c in cls" :key="c.id" :value="c.id">
                                                                {{c.name}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Lưu thông tin cột</button>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
            <div id="modal-products" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <modal-products
                                    v-if="showProducts == true"
                                    :list-id-selected="listIds"
                                    @addProductsToExcel="addProductsToExcel($event)"
                            ></modal-products>
                        </div>
                    </div>
                </div>
            </div>
        </data-table>
    </div>
</template>
<script>
    import table from './../../components/datatable/table'
    import modalProducts from './products/products'
    import axios from 'axios'

    export default {
        props: ['templateId'],
        computed:{
            setAll(){
                return this.data.length == this.itemSelected.length
            },
            listIds(){
                let ids = []
                let vm = this
                if(this.data.length > 0)
                {
                    ids = vm.data.map(item => {
                        return item[vm.primaryKey]
                    })
                }
                return ids
            }
        },
        components: {
            'data-table' : table,
            'modal-products': modalProducts
        },
        data(){
            return {
                showProducts: true,
                textAddProduct: '',
                columns : [
                ],
                buttonConfig: [
                    {
                        text: 'Thêm mới',
                        className: 'btn bg-primary',
                        action: function(e, dt, node, config) {

                            $('#modal-products').modal('show')
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
                create: {
                    name: '',
                    columns: []
                },
                cls: [],
                row_length : 1
            }
        },
        mounted(){
            this.getData()
        },
        methods: {

            setRows(e){
                this.create.columns.push(event.target.value)
            },
            getData(perPage=500,page=1){
                var vm = this
                axios.get(`/api/template-products/${vm.templateId}`).then(data => {
                    vm.data = data.data.products
                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key:'name',
                            text: 'Tên sản phẩm'
                        },
                        {
                            key: 'branch_aliexpress',
                            text: 'Nhánh trên Aliexpress'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
                    alert('Có lỗi xảy ra ! Vui lòng báo lại với dev')
                })
            },

            updateItem(){
                let vm = this
                vm.waiting =true
                axios.put(`/api/templates/${vm.info.id}`,vm.info).then(data=>{
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
                if(vm.addMultiRow != null)
                {
                    vm.create.columns = vm.addMultiRow.split(';')
                }
                let list_err = []
                vm.create.columns.forEach(item =>{
                    if(!vm.cls.some(function (i) {
                        return i.name == item
                    }))
                    {
                        list_err.push(item)
                    }
                })
                if(list_err.length> 0)
                {
                    alert('Danh sách cột bị sai: '+list_err.toString())
                    return false;
                }
                let cs = []
                vm.create.columns.forEach(item =>{
                    let tt = vm.cls.find(function (i) {
                        return i.name == item
                    })
                    cs.push(tt.id)
                })
                vm.create.columns = cs
                axios.post(`/api/templates`,
                    {
                        template_name: vm.create.name,
                        columns: vm.create.columns
                    }
                    ).then(data=>{
                    vm.getData()
                    vm.waiting = false
                    alert('Thành công')
                    $('#create-column').modal('hide')

                }).catch(err=> {
                    alert('Thất bại! Vui lòng báo với developer')
                    vm.waiting =false
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
                    axios.delete('/api/template-products',{
                        params: {
                            product_id: vm.primaryKeyDelete,
                            template_id: vm.templateId
                        }
                    }).then(data => {
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
                        vm.showProducts = false
                        setTimeout(function () {
                            vm.showProducts = true
                        },4000)
                    }).catch(err => {
                        console.log(err)
                        alert('Có lỗi từ server. Vui lòng báo lại với developer')
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                        vm.showProducts = false
                        setTimeout(function () {
                            vm.showProducts = true
                        },4000)
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
            addProductsToExcel(value){
                let vm = this
                vm.waiting = true
                $('#modal-products').modal('hide')
                vm.textAddProduct = 'Đang thêm sản phẩm...'
                $('#wait-add-product').modal('show')
                axios.post('/api/template-products',{
                    products_id: value,
                    template_id: vm.templateId
                }).then( data => {
                    this.getData()
                    vm.waiting = false
                    vm.textAddProduct = 'Thành công :D'
                    setTimeout(function () {
                        $('#wait-add-product').modal('hide')
                        vm.textAddProduct = ''
                    },1500)
                    vm.showProducts = false
                    setTimeout(function () {
                        vm.showProducts = true
                    },4000)
                }).catch(err => {
                    console.log(err)
                    vm.waiting = false
                    vm.textAddProduct = 'Thất bại :('
                    alert('Có lỗi, vui lòng báo với dev')
                })
            },
            deleteListItem() {
                let vm = this
                vm.deleting = true
                axios.delete('/api/delete-template-products',{
                    params:{
                        id_list: vm.itemSelected,
                        template_id: vm.templateId
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
                    vm.showProducts = false
                    setTimeout(function () {
                        vm.showProducts = true
                    },4000)
                }).catch(err => {
                    $('#modal-danger-delete-list').modal('hide')
                    vm.deleting = false
                    console.log(err)
                    alert('Có lỗi, vui lòng báo với dev')
                    vm.showProducts = false
                    setTimeout(function () {
                        vm.showProducts = true
                    },4000)
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
                this.getTemplateInfo(id)
                $('#info-template').modal('show')
            },
            getTemplateInfo(id){
                let vm = this
                axios.get(`/api/templates/${id}`).then(data => {


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
