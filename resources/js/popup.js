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

chrome.storage.local.get('keyword',function (result) {
    data = document.getElementById('keyword')
    if(result.keyword != undefined)
    {
        data.value = result.keyword
    }
})

let btnKew = document.getElementById('saveKeyword')
btnKew.onclick  = function () {
    let data = document.getElementById('keyword')
    chrome.storage.local.set({'keyword': data.value},function () {
        alert('thành công')
    });
}

