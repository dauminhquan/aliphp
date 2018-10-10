chrome.storage.local.get('statusTool', function (result) {
    var status = result.statusTool
    console.log(status)
    if(status == null || status == undefined || status == '')
    {
        chrome.storage.local.set({'statusTool': "0"});
        status = 0
    }
    if(status == 0)
    {
        let stText = document.getElementById('text-status')
        stText.innerText = 'Tắt'
    }
    else if(status == 1){
        let stText = document.getElementById('text-status')
        stText.innerText = 'Bật'
    }

    let btnBat = document.getElementById('bat')
    btnBat.onclick = function () {
        chrome.storage.local.set({'statusTool': "1"});
        let stText = document.getElementById('text-status')
        stText.innerText = 'Bật'
    }

    let btnTat = document.getElementById('tat')
    btnTat.onclick = function () {
        chrome.storage.local.set({'statusTool': "0"});
        let stText = document.getElementById('text-status')
        stText.innerText = 'Tắt'
    }

});

chrome.storage.local.get('config',function (result) {
    result = JSON.parse(result.config)

    let cf_arr= ['keyword','price','time','star','dateship',"shipstar",'dateship2',"shipstar2"]
    let number = ['price','time']
    cf_arr.forEach(item => {
        data = document.getElementById(item)
        if(result[item] != undefined)
        {
            data.value= result[item]
        }
    })
})

let formAction = document.getElementById('form-action')
formAction.onsubmit = function (evt) {
    evt.preventDefault();
    let cf_arr= ['keyword','price','time','star','dateship',"shipstar",'dateship2',"shipstar2"]
    let config = {

    }
    cf_arr.forEach(item => {
        let data = document.getElementById(item).value
        config[item] = data
    })
    config = JSON.stringify(config)
    chrome.storage.local.set({'config': config},function () {
        alert('thành công')
    });
    return false
}


