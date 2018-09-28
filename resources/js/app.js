window.$ = window.jQuery = require('jquery');
import config from './config'
window.$ = $

function joinData(colorDataSkuPropId,colorDataSkuId,colorTitle,sizeDataSkuPropId,sizeDataSkuId,nameSize){
    return {
        attrHasTitle: colorDataSkuPropId+':'+colorDataSkuId+'#'+colorTitle+';'+sizeDataSkuPropId+':'+sizeDataSkuId,
        attrHasNotTitle: colorDataSkuPropId+':'+colorDataSkuId+';'+sizeDataSkuPropId+':'+sizeDataSkuId,
        attrHasNameSize: colorDataSkuPropId+':'+colorDataSkuId+'#'+colorTitle+';'+sizeDataSkuPropId+':'+sizeDataSkuId+'#'+nameSize,
        colorName: colorTitle,
        sizeName: nameSize
    }
}

chrome.storage.local.get('statusTool', function (result) {
    var status = result.statusTool


    function getUrlVars(hashes)
    {
        var vars = [], hash;
        hashes = hashes.slice(hashes.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
    if(config.checkCategoryUrl())
    {
        if(status == 1)
        {
            let products = $('a.picRind')
            for (let i= 0;i<products.length;i++)
            {
                window.open($(products[i]).attr('href'),'_blank')
            }

            setTimeout(function () {
                if(status == 1)
                {
                    window.location = $($('a.page-next.ui-pagination-next')[0]).attr('href')
                }
            },40000)

        }
    }
    if(config.checkProductUrl())
    {
        var category = $('div.ui-breadcrumb:eq(0) a:eq(2)').html()

        if(status == 1)
        {
            var scr = document.createElement("script");
            scr.type="text/javascript";
            let nt = new Date()
            nt = nt.getTime()
            scr.innerHTML = "localStorage.setItem('adminSeq"+nt+"', window.runParams.adminSeq);localStorage.setItem('desProduct"+nt+"',window.runParams.detailDesc);localStorage.setItem('productId"+nt+"',window.runParams.productId);localStorage.setItem('skuProducts"+nt+"',JSON.stringify(skuProducts));localStorage.setItem('skuAttrIds"+nt+"',skuAttrIds)"
            document.body.appendChild(scr)
            let skuProducts = JSON.parse(localStorage.getItem('skuProducts'+nt))
            let adminSeq = localStorage.getItem('adminSeq'+nt)
            let skuAttrIds = localStorage.getItem('skuAttrIds'+nt)
            let lowePrice,highPrice = null
            if ($('span[itemprop="lowPrice"]').length != 0)
            {
                lowePrice = $($('span[itemprop="lowPrice"]')[0]).html()
            }
            let idProduct = localStorage.getItem('productId'+nt) /*$($('a.send-mail-btn')[0]).attr('data-id1')*/

            if ($('span[itemprop="highPrice"]').length != 0)
            {
                highPrice = $($('span[itemprop="highPrice"]')[0]).html()
            }
            if(lowePrice == null || lowePrice==undefined || highPrice == null || highPrice == undefined)
            {
                lowePrice = highPrice = $('span#j-sku-price').html()
            }
            let star = null
            let st = $('.percent-num')[0]
            if(st == null)
            {
                window.close()
            }
            star = st.innerHTML

            if(parseFloat(star) < 4.8)
            {
                window.close()
            }
            else{
                try{
                    $.ajax({
                        url: 'http://localhost:3000',
                        type: 'get',
                        data: {
                            ownerId: adminSeq,
                            productId: idProduct,
                            minPrice: lowePrice,
                            maxPrice: highPrice
                        },
                        success: function (data) {
                            if(data.result == 1) {
                                let price = JSON.parse(localStorage.getItem('skuProducts'+nt))


                                let ulColor = $('#j-sku-list-1')

                                let ulColor_data_sku_prop_id = $(ulColor).attr('data-sku-prop-id')


                                let ulSize = $('#j-sku-list-2')

                                let ulSize_data_sku_prop_id = $(ulSize).attr('data-sku-prop-id')


                                let urlShipFrom = $('#j-sku-list-3')

                                let urlShipFrom_data_sku_prop_id = $(urlShipFrom).attr('data-sku-prop-id')


                                let colorsData = []

                                let sizesData = []

                                let pricesData = []
                                /*lay danh sach anh va ten mau san pham*/

                                if(ulColor!= undefined)
                                {
                                    let aColor = $(ulColor).children('li').children('a')

                                    for (let i = 0; i < aColor.length; i++) {
                                        colorsData.push({
                                            name: $(aColor[i]).attr('title'),
                                            image_url: $(aColor[i]).children('img:eq(0)').attr('src')
                                        })
                                    }

                                    /*lay danh sach size sản phẩm*/
                                    let aSize = $(ulSize).children('li').children('a')

                                    for (let i = 0; i < aSize.length; i++) {
                                        sizesData.push({
                                            name: $(aSize[i]).children('span:eq(0)').html()
                                        })
                                    }

                                    /*lay danh sach giá sản phẩm*/

                                    // lay danh sach attrId

                                    let skuAttrs = []

                                    for(let i = 0;i<aColor.length;i++)
                                    {
                                        for(let j=0;j<aSize.length;j++)
                                        {
                                            skuAttrs.push(joinData(ulColor_data_sku_prop_id,$(aColor[i]).attr('data-sku-id'),$(aColor[i]).attr('title'),ulSize_data_sku_prop_id,$(aSize[j]).attr('data-sku-id'),$(aSize[j]).children('span:eq(0)').html()))
                                        }
                                    }

                                    // còn trường hợp ship from
                                    //
                                    console.log(skuProducts,skuAttrs)
                                    for(let i = 0;i<skuProducts.length;i++)
                                    {
                                        for(let j=0;j<skuAttrs.length;j++)
                                        {
                                            if(skuProducts[i].skuAttr == skuAttrs[j].attrHasTitle || skuProducts[i].skuAttr == skuAttrs[j].attrHasNotTitle

                                                || skuProducts[i].skuAttr == skuAttrs[j].attrHasNameSize

                                            )
                                            {
                                                if(skuProducts[i].skuAttr == skuAttrs[j].attrHasTitle)
                                                {
                                                    pricesData.push({
                                                        colorName: skuAttrs[j].colorName,
                                                        sizeName: skuAttrs[j].sizeName,
                                                        price: skuProducts[i].skuVal.actSkuMultiCurrencyCalPrice
                                                    })
                                                }
                                                if(skuProducts[i].skuAttr == skuAttrs[j].attrHasNameSize)
                                                {
                                                    pricesData.push({
                                                        colorName: skuAttrs[j].colorName,
                                                        sizeName: skuAttrs[j].sizeName,
                                                        price: skuProducts[i].skuVal.actSkuMultiCurrencyCalPrice
                                                    })
                                                }
                                                else{
                                                    pricesData.push({
                                                        colorName: skuAttrs[j].colorName,
                                                        sizeName: skuAttrs[j].sizeName,
                                                        price: skuProducts[i].skuVal.actSkuCalPrice
                                                    })
                                                }
                                            }
                                        }
                                    }


                                }


                                console.log(pricesData)




                                let colorsTextNode = $('li.item-sku-image>a[data-role="sku"]')
                                //
                                let colorsText = []
                                for(let i = 0;i < colorsTextNode.length ;i++)
                                {
                                    colorsText.push({
                                        name: $(colorsTextNode[i]).attr('title'),
                                        url_image : $(colorsTextNode[i]).children('img:eq(0)').attr('src')
                                    })
                                }





                                let sizesNode = $('#j-sku-list-2>li>a')
                                let keySkuId =  $('#j-sku-list-2').attr('data-sku-prop-id')
                                //
                                let sizesAndId = []
                                for(let i = 0;i < sizesNode.length ;i++)
                                {
                                    sizesAndId.push({
                                        name :$(sizesNode[i]).children('span:eq(0)').html(),
                                        id: $(sizesNode[i]).attr('data-sku-id'),
                                        price: null
                                    })
                                }
                                // for(let i = 0 ;i< price.length;i++)
                                // {
                                //     for(let j = 0;j<sizesAndId.length ;j++)
                                //     {
                                //         if(price[i].skuAttr.includes(keySkuId+':'+sizesAndId[j].id))
                                //         {
                                //             sizesAndId[j].price = price[i].skuVal.actSkuCalPrice
                                //             break
                                //         }
                                //     }
                                //
                                // }
                                let imagesProduct = $('#j-image-thumb-list>li>span>img')
                                //
                                let images = []

                                for (let i = 0;i< imagesProduct.length;i++)
                                {
                                    if(i == 0)
                                    {
                                        images.push({
                                            image_url: $(imagesProduct[i]).attr('src'),
                                            type: 1
                                        })
                                    }else{
                                        images.push({
                                            image_url: $(imagesProduct[i]).attr('src'),
                                            type: 2
                                        })
                                    }
                                }

                                let urlDes = localStorage.getItem('desProduct'+nt)
                                let parmas = getUrlVars(urlDes)
                                //
                                urlDes = `https://aeproductsourcesite.alicdn.com/product/description/pc/v2/en_US/desc.htm?productId=${parmas.productId}&key=${parmas.key}&token=${parmas.token}&v=1`
                                let description = null
                                $.ajax({
                                    url: urlDes,
                                    type: 'get',
                                    success: function (data) {
                                        if(data != null && data !='')
                                        {
                                            description = data
                                        }
                                    },
                                    error: function (error) {
                                        console.log(error)
                                    },
                                    async:false
                                })
                                console.log(description)
                                sizesAndId = sizesAndId.filter(item => {
                                    return item.id != undefined
                                })
                                if(pricesData.length == 0 || pricesData == undefined || pricesData == null)
                                {
                                    pricesData = []
                                }
                                $.ajax({
                                    url: 'http://localhost:3000/put/product',
                                    type: 'POST',
                                    data: {
                                        data: JSON.stringify({
                                            product_id: idProduct,
                                            product_name: $('h1.product-name')[0].innerHTML,
                                            item_name:  $('h1.product-name')[0].innerHTML,
                                            standard_price: '',
                                            product_url: window.location.href,
                                            product_star: star,
                                            product_rating_des: data.product_rating_des,
                                            product_rating_shipping: data.product_rating_shipping,
                                            product_rating_seller: data.product_rating_seller,
                                            description: description,
                                            colors: colorsText,
                                            sizes: sizesAndId,
                                            images: images,
                                            category: category,
                                            prices: pricesData
                                        })
                                    },
                                    headers: {
                                        'Accept': 'application/json'
                                    },
                                    success: function (data) {
                                        console.log(data)
                                        localStorage.removeItem('adminSeq'+nt);
                                        localStorage.removeItem('desProduct'+nt)
                                        localStorage.removeItem('productId'+nt);
                                        localStorage.removeItem('skuProducts'+nt);
                                        localStorage.removeItem('skuAttrIds'+nt,skuAttrIds)
                                        window.close()
                                    },
                                    error: function (err) {
                                        localStorage.removeItem('adminSeq'+nt);
                                        localStorage.removeItem('desProduct'+nt)
                                        localStorage.removeItem('productId'+nt);
                                        localStorage.removeItem('skuProducts'+nt);
                                        localStorage.removeItem('skuAttrIds'+nt,skuAttrIds)
                                        window.close()
                                    }
                                })
                            }
                            else{
                                localStorage.removeItem('adminSeq'+nt);
                                localStorage.removeItem('desProduct'+nt)
                                localStorage.removeItem('productId'+nt);
                                localStorage.removeItem('skuProducts'+nt);
                                localStorage.removeItem('skuAttrIds'+nt,skuAttrIds)
                                window.close()
                            }
                        },
                        error: function (err) {
                            console.log(err)
                            localStorage.removeItem('adminSeq'+nt);
                            localStorage.removeItem('desProduct'+nt)
                            localStorage.removeItem('productId'+nt);
                            localStorage.removeItem('skuProducts'+nt);
                            localStorage.removeItem('skuAttrIds'+nt,skuAttrIds)
                            window.close()
                        }
                    })
                }
                catch (e) {
                    localStorage.removeItem('adminSeq'+nt);
                    localStorage.removeItem('desProduct'+nt)
                    localStorage.removeItem('productId'+nt);
                    localStorage.removeItem('skuProducts'+nt);
                    localStorage.removeItem('skuAttrIds'+nt,skuAttrIds)
                    window.close()
                }

            }
        }

    }
})
