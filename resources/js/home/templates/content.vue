<template>
    <div id="content-wrapper">
        <data-table title="Danh sách template"
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
            <div>
                <label class="control-label text-bold" style="position: fixed;z-index: 88888;right: 7%;top: 10%;" v-if="waitingChangeClumn == true" >Đang lưu. Vui lòng đợi...</label>
            </div>
            <div id="info-template" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin chi tiết template</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="updateItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Tên template</label>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" required v-model="info.name">
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các cột</label>
                                        <div class="col-lg-10">
                                            <div class="form-group" v-for="column in info.columns" @key="column">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <select class="form-control" required :value="column" @change="changeInfo(column,$event)">
                                                            <option v-for="c in cls" :key="c.id" :value="c.id">
                                                                {{c.name}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button type="button" class="btn btn-danger" @click="removeColumn(column)"><i class="icon-trash"></i> Xóa</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các cột</label>
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <select class="form-control"  v-model="valueAddToInfo">
                                                            <option v-for="c in cls" :key="c.id" :value="c.id">
                                                                {{c.name}}
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Thêm cột</label>
                                        <div class="col-lg-10">
                                            <button type="button" class="btn btn-success"  @click="addColumnToInfo"><i class="icon-add"></i> Thêm 1 cột</button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <p v-if="waiting == true">Đang lưu...</p>
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Lưu thông tin cột</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="setting-columns" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Sửa cột</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="updateItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các cột</label>

                                        <div class="col-lg-10">
                                            <div class="form-group" v-for="column in info.columns" @key="column">
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" :value="getNameColumn(column)" readonly>
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <select name="" class="form-control" :value="getProductColumnName(column)" @change="changeColumn(column,$event)">
                                                            <option></option>
                                                            <option v-for="cl in productColumns" :key="cl" :value="cl">{{trans.find(cl)}}</option>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="create-column" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thêm template</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="createItem">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Tên template</label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" v-model="create.name" required>
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="addMultiColumn == false" v-for="index in row_length" :key="index">
                                        <label class="control-label col-lg-2 text-bold" >Chọn cột</label>
                                        <div class="col-lg-10">
                                            <select class="form-control" required @change="setRows()">
                                                <option></option>
                                                <option v-for="c in cls" :key="c.id" :value="c.id">
                                                    {{c.name}}
                                                </option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group" v-if="addMultiColumn == true">
                                        <label class="control-label col-lg-2 text-bold" >Nhập text</label>
                                        <div class="col-lg-10">
                                            <textarea rows="5" class="form-control" v-model="addMultiRow"></textarea>
                                            <p>Form mẫu: <span>cot1;cot2;cot3;...</span>.</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Thêm cột</label>
                                        <div class="col-lg-10">
                                            <button type="button" class="btn btn-success" v-if="addMultiColumn == false" @click="addMultiColumn= !addMultiColumn;addMultiRow=null"><i class="icon-add"></i> Thêm một lúc nhiều cột</button>
                                            <button type="button" class="btn btn-success" v-if="addMultiColumn == true" @click="addMultiColumn= !addMultiColumn;addMultiRow=null"><i class="icon-add"></i> Thêm từng cột</button>
                                            <button type="button" class="btn btn-success" v-if="row_length < cls.length - 1 && addMultiColumn == false" @click="row_length++"><i class="icon-add"></i> Thêm 1 cột</button>
                                            <button type="button" class="btn btn-danger" v-if="row_length > 0 && addMultiColumn == false " @click="row_length--"><i class="icon-trash"></i> Xóa cột</button>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <p v-if="waiting == true">Đang tạo...</p>
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Thêm template</button>
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
                waitingChangeClumn: false,
                trans: trans,
                addColumn: 0,
                addMultiColumn: false,
                addMultiRow: null,
                columns : [
                ],
                buttonConfig: [
                    {
                        text: 'Thêm mới',
                        className: 'btn bg-primary',
                        action: function(e, dt, node, config) {

                            $('#create-column').modal('show')
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
                        action :'template-products',
                        html:'<a href="javascript:void(0);"><i class="icon-file-excel"></i> Xuất Excel</a>'
                    },
                    {
                        action :'setting-columns',
                        html:'<a href="javascript:void(0);"><i class="icon-cog2"></i> Cài đặt cột</a>'
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
                row_length : 1,
                valueAddToInfo: null,
                productColumns: [],
                allColumns: []
            }
        },
        mounted(){
            this.getData()
            this.getColumns()
            this.getProductColumns()
            this.getAllColumns()
        },
        methods: {
            changeColumn(id,e)
            {
                this.allColumns.forEach(item => {
                    if(item.id == id)
                    {
                        item.product_column = e.target.value
                    }
                })
                this.waitingChangeClumn = true
                let vm = this
                axios.put(`/api/columns/${id}`,{
                    product_column: e.target.value
                }).then(data => {
                    setTimeout(function () {
                        vm.waitingChangeClumn = false
                    },1500)


                    console.log('success')
                }).catch(err => {
                    setTimeout(function () {
                        vm.waitingChangeClumn = false
                    },1500)
                    alert('Thất bại. Hãy load lại trang và báo với dev')
                    console.log(err)
                })
            },
            getProductColumnName(id){
                let vm = this

                if(vm.allColumns.length > 0)
                {
                    let c = vm.allColumns.find(item => {
                        return item.id == id
                    })
                    return c.product_column
                }
                return null
            },
            getNameColumn(id){
                let vm = this

                if(vm.allColumns.length > 0)
                {
                    let c = vm.allColumns.find(item => {
                        return item.id == id
                    })
                    return c.name
                }
                return null
            },
            getAllColumns(){
                axios.get('/api/columns?size=-1').then(data => {
                    console.log(data.data)
                    this.allColumns = data.data.data
                }).catch(err =>{
                    console.log(err)
                })
            },
            getProductColumns(){
                axios.get('/api/product-columns').then(data => {
                    this.productColumns = data.data
                })  .catch(err => {
                    console.log(err)
                    alert('Có lỗi, vui lòng báo với DEV')
                })
            },
            changeInfo(id,event){
                let vm = this
                let n = 0
                vm.info.columns.forEach(i => {
                    if(i == event.target.value)
                    {
                        n++
                    }
                })

                if(n >=1)
                {
                    alert('Cột đã tồn tại rồi')
                }

                for(let i = 0; i < vm.info.columns.length;i++)
                {
                    if(vm.info.columns[i] == id)
                    {
                        vm.info.columns[i] = event.target.value
                    }
                }
            },
            addColumnToInfo(){

                let vm = this
                let n = 0
                vm.info.columns.forEach(i => {
                    if(i == vm.valueAddToInfo)
                    {
                        n++
                    }
                })

                if(n >=1)
                {
                    alert('Cột đã tồn tại rồi')
                }
                else{
                    this.info.columns.push(vm.valueAddToInfo)
                }

            },
            removeColumnE(e){
                let vm = this
                vm.info.columns = vm.info.columns.filter(item => {
                    return item != e.target.value
                })
            },
            removeColumn(id)
            {
                let vm = this
                vm.info.columns = vm.info.columns.filter(item => {
                    return item != id
                })
            },
            setRows(event){
                this.create.columns.push(event.target.value)
            },
            getData(perPage=500,page=1){
                var vm = this
                axios.get('/api/templates'+'?size='+perPage+'&page='+page).then(data => {
                    vm.data = data.data.data
                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key:'name',
                            text: 'Tên template'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
                    alert('Có lỗi xảy ra ! Vui lòng báo lại với dev')
                })
            },
            getColumns(){
                var vm = this
                axios.get('/api/columns'+'?size=-1').then(data => {
                    vm.cls = data.data.data
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
                    $('#info-template').modal('hide')

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
                if(event[1] == 'template-products')
                {
                    window.open(`/template/${event[0]}`,'_blank')
                }
                if(event[1] == 'setting-columns')
                {
                    this.getTemplateInfo(event[0])
                    $('#setting-columns').modal('show')
                }
            },
            deleteItem(){
                let vm = this
                vm.deleting = true

                if(vm.primaryKeyDelete != -1)
                {
                    let indexOf = -1
                    axios.delete('/api/templates'+'/'+vm.primaryKeyDelete).then(data => {
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
                /*axios.delete('/api/delete-columns',{
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

                    vm.config.notifySuccess()
                    vm.itemSelected = []
                    vm.resetCheck = !vm.resetCheck

                }).catch(err => {
                    $('#modal-danger-delete-list').modal('hide')
                    vm.deleting = false
                    console.log(err)
                    vm.config.notifyError()
                })*/
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
                    vm.info.columns = vm.info.columns.map(i => {
                        return i.id
                    })
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
