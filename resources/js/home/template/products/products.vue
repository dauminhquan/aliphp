<template>
    <div>
        <data-table title="Danh sách sản phẩm"
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
                    @addProductsToExcel="addProductsToExcel"
                    @action="action($event)"
                    @clickedKeyItem="clickedKeyItem"
                    @changePerPage="changePerPage"
                    @changePageSelect="changePageSelect"
        >
        </data-table>
    </div>
</template>
<script>
    import table from './../components/datatable/table'
    import axios from 'axios'

    export default {
        props: ['list-id-selected'],
        computed:{
            setAll(){
                return this.data.length == this.itemSelected.length
            },
        },
        components: {
            'data-table' : table
        },

        watch:{
            listIdSelected(value)
            {
                let vm = this
                if(value.length > 0)
                {
                    value.forEach(item => {
                        vm.data = vm.data.filter(d => {
                            return d[vm.primaryKey] != item
                        })
                    })

                }

            }
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

            }
        },
        mounted(){
            this.getData()
        },
        methods: {
            getData(perPage=500,page=1){
                var vm = this
                axios.get('/api/products'+'?size='+perPage+'&page='+page).then(data => {
                    vm.data = data.data.data

                    if(vm.listIdSelected.length > 0)
                    {
                        vm.listIdSelected.forEach(item => {
                            vm.data = vm.data.filter(d => {
                                return d[vm.primaryKey] != item
                            })
                        })

                    }
                    vm.data.forEach(i => {
                        i.main_image = `<img src="${i.main_image_url}" style="max-width: 200px">`
                    })
                    vm.columns = [
                        {
                            key: 'id',
                            text: 'ID'
                        },
                        {
                            key: 'item_name',
                            text: 'Tên sản phẩm'
                        },
                        {
                            key: 'main_image',
                            text: 'Ảnh chính'
                        },
                        {
                            key:'query_keyword',
                            text: 'Từ khóa tìm kiếm'
                        }

                    ]
                }).catch(err => {
                    console.log(err)
                    alert('Có lỗi xảy ra ! Vui lòng báo lại với dev')
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
            addProductsToExcel(){
                let selected = this.itemSelected
                this.$emit('addProductsToExcel',selected)
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
