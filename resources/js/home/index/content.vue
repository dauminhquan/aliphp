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
            <div id="info-product" class="modal fade">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h5 class="modal-title">Thông tin chi tiết sản phẩm</h5>
                        </div>
                        <form class="form-horizontal" action="#">
                            <div class="modal-body">
                                <fieldset class="content-group">
                                    <legend class="text-bold ">Các thông tin</legend>

                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold" >Tên sản phẩm</label>
                                        <div class="col-lg-10">
                                            <textarea type="text" class="form-control" v-model="info.item_name"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Giá sản phẩm</label>
                                        <div class="col-lg-10">
                                            <input type="text" v-model="info.standard_price" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Ngách trên Aliexpress</label>
                                        <div class="col-lg-10">
                                            <input type="text" v-model="info.branch_aliexpress" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group" v-if="info.colors != undefined">
                                        <label class="control-label col-lg-2 text-bold">Danh sách màu</label>
                                        <div class="col-lg-10">
                                            <input type="text" v-model="info.colors" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Danh sách hình ảnh</label>
                                        <div class="col-lg-10">
                                           <div class="row">
                                               <div class="col-lg-12 col-md-6">
                                                   <div class="thumbnail no-padding">
                                                       <div class="thumb image-main">
                                                           <img :src="info.main_image_url">
                                                           <div class="caption-overflow">
                                                                <span>
                                                                    <a href="#" class="btn bg-success-400 btn-icon btn-xs" data-popup="lightbox"><i class="icon-plus2"></i></a>
                                                                    <a href="#" class="btn bg-success-400 btn-icon btn-xs"><i class="icon-link"></i></a>
                                                                </span>
                                                           </div>
                                                       </div>

                                                       <div class="caption text-center">
                                                           <h6 class="text-semibold no-margin text-bold">Hình ảnh chính</h6>
                                                       </div>
                                                   </div>
                                               </div>
                                           </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6" v-for="image in info.other_images_url" :key="image">
                                                    <div class="thumbnail no-padding">
                                                        <div class="thumb">
                                                            <img :src="image" alt="">
                                                            <div class="caption-overflow">
                                                                <span>
                                                                    <a href="#" target="_blank" class="btn bg-success-400 btn-icon btn-xs" data-popup="lightbox"><i class="icon-plus2"></i></a>
                                                                    <a href="#" class="btn bg-success-400 btn-icon btn-xs"><i class="icon-link"></i></a>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="caption text-center">
                                                            <h6 class="text-semibold no-margin">Hình ảnh khác</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-lg-2 text-bold">Mô tả sản phẩm</label>
                                        <button class="btn btn-success" type="button" @click="showCodeDescription = true;setHtmlToCkec(info.product_description)"><i class="icon-cog2"></i> Show code</button>
                                        <button class="btn btn-info" type="button" @click="showCodeDescription = false"><i class="icon-section"></i> Show HTML</button>
                                    </div>
                                    <div class="form-group">
                                        <div class="description-content">
                                            <div class="row">
                                                <div class="col-lg-12 product-description" v-show="showCodeDescription == false" v-html="info.product_description">
                                                </div>
                                                <div class="col-lg-12" v-show="showCodeDescription == true">
                                                    <textarea id="content-description-product" v-model="info.product_description"></textarea>
                                                </div>
                                            </div></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </fieldset>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Lưu thông tin sản phẩm</button>
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
            ComputedImageHover(){
                if(this.imageHover != null)
                {
                    return this.imageHover.replace('_50x50.jpg','')
                }
               return 'data:image/png;base64,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'
            }
        },
        components: {
            'data-table' : table
        },
        data(){
            return {
                columns : [
                ],
                buttonConfig: [
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
                        html:'<a href="javascript:void(0);"><i class="icon-trash"></i> Xóa sản phẩm</a>'
                    }
                ],
                imageHover: 'data:image/png;base64,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',
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
                imageColorSelected: null
            }
        },
        mounted(){
            this.getData()
            CKEDITOR.replace('content-description-product');
        },
        methods: {
            selectedImageColor(id)
            {
                if(id == this.imageColorSelected)
                {
                    return 'image-color color-select'
                }
                return 'image-color'
            },
            opentableDownloadExcel(){
                let ex = this.excelDownload
              window.open(`/export-excel?feed_product_type=${ex.feed_product_type}&item_sku=${ex.item_sku}&brand_name=${ex.brand_name}&item_type=${ex.item_type}&quantity=${ex.quantity}&merchant_shipping_group_name=${ex.merchant_shipping_group_name}&part_number=${ex.part_number}`,'_blank');
            },
            setHtmlToCkec(d){
                CKEDITOR.instances['content-description-product'].setData(d)
            },
            getData(perPage=500,page=1){
                var vm = this
                axios.get('/api/products'+'?size='+perPage+'&page='+page).then(data => {
                    vm.data = data.data.data
                    vm.perPage = data.data.per_page
                    vm.totalPage = data.data.total
                    vm.data = vm.data.map(item => {
                        item.category_name = ''
                        if(item.category != null)
                        {
                            item.category_name = item.category.name
                        }


                        item.colors_html = `<p style="background-color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;" title="${item.colors}">${item.colors}</p>`
                        item.sizes_html = `<p style="background-color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;" title="${item.colors}">${item.sizes}</p>`
                        item.html_name = `<p style="background-color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;" title="${item.item_name}">${item.item_name}</p>`
                        item.textUrl = '<a href="'+item.url_aliexpress+'" target="_blank">#'+item.aliexpress_product_id+'</a>'
                        item.branch_aliexpress_html = `<p style="background-color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;" title="${item.branch_aliexpress}">${item.branch_aliexpress}</p>`

                        item.other_images_url = item.other_images != null ? item.other_images.split(';') : []
                        item.other_images_url.filter(item => {
                            return item != '' && item != null && item!= undefined
                        })
                        item.img_url = `<img src="${item.main_image_url}" style="max-width: 200px">`
                        return item
                    })

                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key:'html_name',
                            text: 'Tên sản phẩm'
                        },
                        {
                            key:'colors_html',
                            text: 'Các màu'
                        },
                        {
                            key:'sizes_html',
                            text: 'Các kích cỡ'
                        },
                        {
                          key: 'img_url',
                          text: 'Giá dự kiến'
                        },
                        {
                          key: 'textUrl',
                          text: 'Url'
                        },
                        {
                          key: 'query_keyword',
                          text: 'Từ khóa tìm kiếm'
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
            deleteItem(){
                let vm = this
                vm.deleting = true

                if(vm.primaryKeyDelete != -1)
                {
                    let indexOf = -1
                    /*axios.delete(vm.config.API_ADMIN_STUDENTS_RESOURCE+'/'+vm.primaryKeyDelete).then(data => {
                        vm.data.forEach((item,index) => {

                            if(item[vm.primaryKey] == vm.primaryKeyDelete)
                            {
                                indexOf = index
                            }
                        })
                        if(indexOf != -1)
                        {
                            vm.data.splice(indexOf,1)
                            vm.config.notifySuccess()
                        }
                        else
                        {
                            vm.config.notifyWarning()
                        }
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                        $('div.checker>span').removeClass('checked')
                    }).catch(err => {
                        console.log(err)
                        vm.config.notifyError()
                        vm.deleting = false
                        $('#modal_danger').modal('hide')
                    })*/
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
                this.getProductInfo(id)
                this.imageHover = null
                this.showCodeDescription = null
                $('#info-product').modal('show')
            },
            getProductInfo(id){
                let vm = this
                vm.info = vm.data.find(item => {
                    return item.id == id
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
