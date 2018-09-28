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
                                let price = highPrice

                                $.expr[':'].contains = function(a, i, m) {
                                    return jQuery(a).text().toUpperCase()
                                        .indexOf(m[3].toUpperCase()) >= 0;
                                };

                                let infoColorAndSizeProduct = $('#j-product-info-sku')

                                let liColors = $(infoColorAndSizeProduct).find('dt:contains(color):eq(0)').next('dd').find('li')
                                let liSizes = $(infoColorAndSizeProduct).find('dt:contains(size):eq(0)').next('dd').find('li')

                                let colors = [];

                                let sizes = [];


                                for(let i = 0;i< liColors.length;i++)
                                {
                                    colors.push($(liColors[i]).children('a:eq(0)').attr('title'))
                                }

                                for(let i = 0;i< liSizes.length;i++)
                                {
                                    sizes.push($(liSizes[i]).children('a:eq(0)').children('span:eq(0)').html())
                                }


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
                                            images: images,
                                            sizes: sizes,
                                            colors: colors,
                                            category: category,
                                            prices: price
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
