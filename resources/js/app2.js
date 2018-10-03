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
            },50000)

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
                if($('span#j-sku-discount-price') != undefined)
                {
                    lowePrice = highPrice = $('span#j-sku-discount-price').html()
                }
               else{
                    lowePrice = highPrice = $('span#j-sku-price').html()
                }
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

                                let price = (parseFloat(highPrice) + parseFloat(data.priceShip)) * 2

                                $.expr[':'].contains = function(a, i, m) {
                                    return jQuery(a).text().toUpperCase()
                                        .indexOf(m[3].toUpperCase()) >= 0;
                                };

                                let infoColorAndSizeProduct = $('#j-product-info-sku')

                                let liColors = $(infoColorAndSizeProduct).find('dt:contains(color):eq(0)').next('dd').find('li')
                                let liSizes = $(infoColorAndSizeProduct).find('dt:contains(size):eq(0)').next('dd').find('li')

                                let colors = '';

                                let sizes = '';


                                for(let i = 0;i< liColors.length;i++)
                                {
                                    if($(liColors[i]).children('a:eq(0)').attr('title') == undefined)
                                    {
                                        colors+=$(liColors[i]).children('a:eq(0)').children('span:eq(0)').html()+';'
                                    }
                                    else{
                                        colors+=$(liColors[i]).children('a:eq(0)').attr('title')+';'
                                    }
                                }

                                for(let i = 0;i< liSizes.length;i++)
                                {
                                    let s = $(liSizes[i]).children('a:eq(0)').children('span:eq(0)').html()
                                    if(s != undefined)
                                    {
                                        sizes+=$(liSizes[i]).children('a:eq(0)').children('span:eq(0)').html()+';'
                                    }

                                }


                                let imagesProduct = $('#j-image-thumb-list>li>span>img')
                                //
                                let other_images = ''
                                let main_image = ''
                                for (let i = 0;i< imagesProduct.length;i++)
                                {
                                    if(i == 0)
                                    {
                                        main_image = $(imagesProduct[i]).attr('src')
                                        main_image = main_image.replace('_50x50.jpg','')
                                    }else{
                                        other_images+=($(imagesProduct[i]).attr('src')).replace('_50x50.jpg','')+';'
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
                                            let dom_nodes = $($.parseHTML(`<div>${data}</div>`));
                                             $(dom_nodes).find(':contains(aliexpress)').parents('p:eq(0)').remove()
                                            $(dom_nodes).find('a').remove()
                                            $(dom_nodes).find('img').remove()
                                            description = $(dom_nodes).text()
                                        }
                                    },
                                    error: function (error) {
                                        window.close()
                                        console.log(error)
                                    },
                                    async:false
                                })
                                let specifics = $('.product-property-list:eq(0)').html()
                                let specificsLis = $('.product-property-list:eq(0)').find('li:not(:contains(Brand Name))')
                                let key_works = ''
                                let generic_keywords = []

                                $.ajax({
                                    type:'get',
                                    url: 'https://www.aliexpress.com/seo/detailCrosslinkAjax.htm?productId='+idProduct,
                                    success: function (data) {
                                        let dom_nodes = $($.parseHTML(`<div>${data}</div>`));
                                        let keyworks_a = $(dom_nodes).find('a')
                                        console.log(keyworks_a)
                                        let max = 5
                                        for(let i = 0;i<keyworks_a.length; i++)
                                        {
                                            if(i< max)
                                            {
                                                if(!generic_keywords.includes(keyworks_a[i].innerText))
                                                {
                                                    generic_keywords.push(keyworks_a[i].innerText)
                                                    key_works+= keyworks_a[i].innerText+';'
                                                }
                                                else{
                                                    max++
                                                }
                                            }
                                        }
                                    },
                                    error: function (error) {

                                        console.log(error)
                                    },
                                    async:false
                                })

                                let dtPost = {
                                    aliexpress_product_id: idProduct,
                                    item_name:  $('h1.product-name')[0].innerHTML,
                                    url_aliexpress: window.location.href,
                                    product_description: description,
                                    main_image_url: main_image,
                                    swatch_image: '',
                                    other_images: other_images,
                                    sizes: sizes,
                                    branch_aliexpress: document.getElementsByClassName('ui-breadcrumb')[0].innerText.replace('Back to search results ',''),
                                    colors: colors,
                                    specifics: specifics,
                                    key_works: key_works,
                                    standard_price: price
                                }

                                for(let i = 0;i< 5 ;i++)
                                {
                                    if(generic_keywords[i] == undefined)
                                    {
                                        dtPost['generic_keywords'+(i+1)] = ''
                                    }
                                    else {
                                        dtPost['generic_keywords'+(i+1)] = generic_keywords[i]
                                    }
                                }
                                for(let i=0;i<5;i++)
                                {
                                    if($(specificsLis).text() == undefined)
                                    {
                                        dtPost['bullet_point'+(i+1)] = ''
                                    }
                                    else {
                                        dtPost['bullet_point'+(i+1)] = specificsLis[i].innerText
                                    }
                                }
                                let other_images_array = other_images.split(';')
                                for (let i = 0; i< 3 ;i++)
                                {
                                    if(other_images_array[i] == undefined)
                                    {
                                        if(i == 0)
                                        {
                                            dtPost['other_image_url'] = null
                                        }
                                        dtPost['other_image_url'+(i+1)] = null
                                    }
                                    else{
                                        if(i == 0)
                                        {
                                            dtPost['other_image_url'] = other_images_array[i]
                                        }
                                        dtPost['other_image_url'+(i+1)] = other_images_array[i]
                                    }
                                }
                                console.log(dtPost)
                                $.ajax({
                                    url: 'http://localhost:3000/put/product',
                                    type: 'POST',
                                    data: {
                                        data: JSON.stringify(dtPost)
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
