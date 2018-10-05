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
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các thông tin</label>
                                        <div class="col-lg-10">
                                            <div class="form-group" v-for="column in templateColumns" @key="column.id">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <input type="text" class="form-control" readonly :value="column.name">
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" v-model="info[column.name]">
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
                                <button type="submit" class="btn btn-primary">Update thông tin</button>
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
            <div id="modal-export-excel" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin chi tiết sản phẩm</h5>
                        </div>
                        <form class="form-horizontal" @submit.prevent="exportExcel">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Danh sách các cột thông tin chung</label>
                                        <div class="col-lg-10">
                                            <div class="form-group" v-for="(cl,index) in commonData" :key="index">
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <select2 class="form-control" :value="cl.key" :options="templateColumns" @input="cl.key = $event">
                                                            <!--<option ></option>-->
                                                            <!--<option value="" v-for="column in templateColumns" @key="column.id"  :value="column.id" >{{column.name}}</option>-->
                                                        </select2>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" placeholder="Nhập giá trị" v-model="cl.value">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Thêm cột</label>
                                        <button type="button" class="btn btn-success" @click="commonData.push({key: '',value:''})" ><i class="icon-add-to-list"></i> Thêm một cột</button>
                                        <button type="button" class="btn btn-danger" @click="commonData.pop()" ><i class="icon-trash"></i> Xóa cột cuối</button>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary"><i class="icon-file-download"></i> Xuất Excel</button>
                                <button type="button" class="btn btn-info" @click="saveCommon"><i class="glyphicon glyphicon-save-file"></i> Lưu cột chung</button>
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
    import modalProducts from './products/products'
    import axios from 'axios'
    import select2 from './components/select2/select2'
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
            'modal-products': modalProducts,
            'select2': select2
        },
        data(){
            return {
                commonData: [
                ],
                columnCommon: 0,
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
                    },
                    {
                        text: 'Xuất Excel',
                        className: 'btn bg-info',
                        action: function(e, dt, node, config) {

                            $('#modal-export-excel').modal('show')
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
                        action :'change-status',
                        html:'<a href="javascript:void(0);"><i class="icon-info3"></i> Đổi trạng thái</a>'
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
                templateColumns: [],
            }
        },
        mounted(){
            this.getData()
            this.getColumns()
            this.getCommonData()
        },
        methods: {
            getCommonData(){
                let vm =this
                axios.get(`/api/get-common-columns/${vm.templateId}`).then(data => {
                    let d = data.data
                    d.forEach(i => {
                        i.key = i.column_id
                        delete i.column_id
                        delete i.template_id
                    })
                    vm.commonData = d
                }).catch(err => {
                    console.log(err)
                    alert('co loi. Vui long bao lai voi dev')
                })
            },
            getColumns(){
                axios.get('/api/templates/'+this.templateId).then(data => {
                    this.templateColumns = data.data.columns
                }).catch(err => {
                    console.log(err)
                    alert('Đã có lỗ. Vui lòng báo với DEV')
                })
            },
            setRows(e){
                this.create.columns.push(event.target.value)
            },
            getData(perPage=500,page=1){
                var vm = this
                axios.get(`/api/template-products/${vm.templateId}`).then(data => {
                    vm.data = data.data.products
                    vm.data.forEach(item => {
                        item.main_image_url_img = `<img style="max-width: 200px" src="${item.main_image_url}">`
                    })
                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key:'item_name',
                            text: 'Tên sản phẩm'
                        },
                        {
                          key: 'main_image_url_img',
                          text: 'Ảnh đại diện'
                        },
                        {
                            key:'exported',
                            text: 'Tình trạng xuất Excel'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
                    alert('Có lỗi xảy ra ! Vui lòng báo lại với dev')
                })
            },
            exportExcel(){
                let url = '/export-excel/'+this.templateId+'?'
                let vm = this
                    this.commonData.forEach( i => {
                        if(i.key!= '' && i.value != '')
                        {
                            let keyO = vm.templateColumns.find(item => {
                                return item.id == i.key
                            })
                            url+=keyO.name+'='+i.value+'&'

                        }
                    })
                    window.open(url,'_blank')
            },
            saveCommon(){
                let vm = this
                axios.post(`/api/save-common-columns/${vm.templateId}`,{
                    common: vm.commonData
                }).then(data => {
                    console.log(data)
                    alert('Thanh cong')
                }).catch(err => {
                    console.log(err)
                    alert('Co loi xay ra, vui long bao lai voi Dev')
                })
            },
            updateItem(){
                let vm = this
                vm.waiting =true
                axios.put(`/api/template-products/${vm.templateId}/${vm.info.id}`,vm.info).then(data=>{
                    vm.getData()
                    alert('Thành công')
                    vm.waiting = false
                    $('#info-product').modal('hide')
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
                if(event[1] == 'change-status')
                {

                    axios.put(`/api/change-status-product/${this.templateId}/${event[0]}`).then(data => {
                        vm.data.forEach(i => {
                            if (i[vm.primaryKey] == event[0]) {
                                if (i.exported == 'Đã xuất Excel')
                                {
                                    i.exported = 'Chưa xuất Excel'
                                }
                                else{
                                    i.exported = 'Đã xuất Excel'
                                }
                            }
                        })
                    }).catch(err => {
                        console.log(err)
                        alert('Có lỗi xảy ra, vui lòng báo với DEV')
                    })
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
                this.getProductInfo(id)
                $('#info-product').modal('show')
            },
            getProductInfo(id){
                let vm = this
                axios.get(`/api/template-products/${vm.templateId}/${id}`).then(data => {


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
