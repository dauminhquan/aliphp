<template>
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">{{title}}</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li>
                        Số item mỗi trang:
                    </li>
                    <li>
                        <select class="right form-control length-select" v-model="perPage" >
                            <option v-for="length in lengths" :value="length">{{length}}</option>
                            <option value="-1">Tất cả</option>
                        </select>
                    </li>
                    <li>
                        Chọn trang:
                    </li>
                    <li>
                        <select class="right form-control length-select" v-model="pageSelect" >
                            <option v-for="page in totalPage" :value="page">{{page}}</option>
                        </select>
                    </li>
                </ul>
            </div>

        </div>
        <table id="data-table" class="table datatable-basic">
            <thead>
            <tr>
                <th>
                    <div class="btn-group navbar-btn" style="margin-left: unset !important;">
                        <button type="button" class="btn btn-default btn-icon btn-checkbox-all" @click="selectAll">
                            <div class="checker"><span :class="checked"><input type="checkbox"class="styled"></span></div>
                        </button>
                        <button type="button" class="btn btn-default btn-icon dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" v-if="!allChecked" @click="selectAll">Chọn tất cả</a></li>
                            <li><a href="javascript:void(0);" v-if="allChecked" @click="unSelectAll">Bỏ chọn tất cả</a></li>
                            <li><a href="javascript:void(0);" @click="deleteSelected">Xóa mục đã chọn</a></li>
                        </ul>
                    </div>
                </th>
                <th v-for="column in columns" :key="column.key" v-html="column.text"></th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in data">
                <td>
                    <checkbox-item
                            @setClicked="checkedItem(item[primaryKey])"
                            :allChecked="allChecked"
                            :resetCheck="resetCheck"
                    ></checkbox-item>

                </td>
                <td v-for="column in columns" :key="column.key" v-html="item[column.key]"></td>
                <td class="text-center">
                    <ul class="icons-list">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li  v-for="li in menu" :key="li.action" v-html="li.html" @click="action(item[primaryKey],li.action)"></li>
                            </ul>
                        </li>
                    </ul>
                </td>
            </tr>
            </tbody>
        </table>
        <slot></slot>
    </div>

</template>
<script>
    import checkboxItem from './checkboxItem'
    var $ = require('jquery')
    import 'datatables'
    import 'datatables.net-buttons'
    import 'select2'
    require( 'datatables.net-buttons/js/buttons.colVis.min');
    require('datatables.net-buttons/js/dataTables.buttons.min')
    export default {
        components:{
          'checkbox-item':checkboxItem
        },
        props: [
            'title',
            'data',
            'columns',
            'showCheck',
            'targets',
            'buttonConfig',
            'primaryKey',
            'menu',
            'resetCheck',
            'pages',
            'lengths'
        ],
        data(){
            return {
                table: null,
                idSelected: [],
                checked:'',
                allChecked: false,
                perPage: 500,
                pageSelect:1,
            }
        },
        beforeUpdate(){
            this.table.fnDestroy()
        },
        updated(){
            this.$nextTick(function () {

                this.Init()
            })
        },
        mounted(){
            var vm = this

            this.Init()
        },
        watch:{
            resetCheck(){
                this.checked = ''
            },
            perPage(value){
                this.$emit('changePerPage',value)
            },
            pageSelect(value){
                this.$emit('changePageSelect',value)
            }
        },
        methods: {
            Init()
            {
                let vm = this
                $.extend( $.fn.dataTable.defaults, {
                    autoWidth: false,
                    dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                    language: {
                        search: '<span>Tìm kiếm:</span> _INPUT_',
                        lengthMenu: '<span>Hiển thị:</span> _MENU_',
                        paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
                    }
                });
                let length = vm.columns.length
                var configted = false;
                vm.buttonConfig.forEach(item => {
                    if(item.extend == 'colvis')   {
                        configted = true
                    }
                })
                if(configted == false)
                {
                    vm.buttonConfig.push({
                        extend: 'colvis',
                        text: '<i class="icon-three-bars"></i> <span class="caret"></span>',
                        className: 'btn bg-blue btn-icon'
                    })
                }

                let targets = [0,length+1]
                targets = targets.concat(vm.targets)
                vm.table = $('#data-table').dataTable({
                    columnDefs: [ { orderable: false, targets: targets }],
                    buttons: {
                        buttons: vm.buttonConfig
                    }
                });

                $('.dataTables_filter input[type=search]').attr('placeholder','Nhập từ khóa');



                $('.dataTables_length select').select2({
                    minimumResultsForSearch: Infinity,
                    width: 'auto'
                });

                $('button.dt-button.buttons-collection.buttons-colvis.btn.bg-blue.btn-icon[tabindex="0"][aria-controls="data-table"]').click(function () {
                    if($(this).attr('removed') == undefined)
                    {
                        $('button.dt-button.buttons-columnVisibility.active[tabindex="0"]')[0].remove()
                        $(this).attr('removed',1)
                    }
                })

            },
            selectAll(){
                this.doChecked()
                if(this.allChecked == true)
                {
                    this.$emit('unSelectAll')
                }
                else{
                    this.$emit('selectAll')
                }
                this.allChecked = !this.allChecked

            },
            unSelectAll(){
                this.doChecked()
                this.allChecked = false
                this.$emit('unSelectAll')
            },
            checkedItem(key)
            {
              this.$emit('clickedKeyItem',key)
            },
            doChecked(){
                if(this.checked == '')
                {
                    this.checked = 'checked'
                }
                else{
                    this.checked = ''
                }
            },

            deleteSelected(){
                this.$emit('deleteSelected')

            },
            deletedSelect(){
                this.unSelectAll()
            },
            action(key,action)
            {

                this.$emit('action',[key,action])
            }
        },
        computed:{
            totalPage(){
                if(typeof this.pages != "number")
                {
                    this.pages = 1
                }
                let c = parseInt(this.pages / this.perPage)

                let p = this.pages%this.perPage
                if(p > 0)
                {
                    c++
                }

                let pages = []
                for(let i = 0;i < c;i++)
                {
                    pages.push(i+1);
                }
                return pages
            }
        }
    }
</script>

<style>
    .dt-button-collection button{
        width: 100% !important;
    }
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        vertical-align: middle;
        max-width: 165px;
    }
</style>
