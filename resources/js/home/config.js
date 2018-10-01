const trans = {
    item_name: 'Tên sản phẩm',
    item_sku: 'Sku sản phẩm',
    branch_name: 'Tên ngách (Nhánh)',
    aliexpress_product_id: 'Mã sản phẩm trên Aliexpress',
    branch_aliexpress: 'Ngách sản phẩm trên Aliexpress',
    brand_name: 'Ngách sản phẩm',
    colors: 'Danh sách màu của sản phẩm',
    external_product_id: 'UPC',
    external_product_id_type: 'Loại mã sản phẩm bên ngoài',
    feed_product_type: 'Loại sản phẩm',

}
export default {
    find: (key) => {
        if(trans[key] == undefined)
        {
            // console.log('error: '+key+" not found")
            return key;
        }
        return trans[key]+` (${key})`
    }
}
