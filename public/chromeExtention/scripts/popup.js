/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ 23:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(24);


/***/ }),

/***/ 24:
/***/ (function(module, exports) {

chrome.storage.local.get('statusTool', function (result) {
    var status = result.statusTool;
    console.log(status);
    if (status == null || status == undefined || status == '') {
        chrome.storage.local.set({ 'statusTool': "0" });
        status = 0;
    }
    if (status == 0) {
        var stText = document.getElementById('text-status');
        stText.innerText = 'Tắt';
    } else if (status == 1) {
        var _stText = document.getElementById('text-status');
        _stText.innerText = 'Bật';
    }

    var btnBat = document.getElementById('bat');
    btnBat.onclick = function () {
        chrome.storage.local.set({ 'statusTool': "1" });
        var stText = document.getElementById('text-status');
        stText.innerText = 'Bật';
    };

    var btnTat = document.getElementById('tat');
    btnTat.onclick = function () {
        chrome.storage.local.set({ 'statusTool': "0" });
        var stText = document.getElementById('text-status');
        stText.innerText = 'Tắt';
    };
});

chrome.storage.local.get('config', function (result) {
    result = JSON.parse(result.config);
    console.log(result);
    var cf_arr = ['keyword', 'price', 'time'];
    var number = ['price', 'time'];
    cf_arr.forEach(function (item) {
        data = document.getElementById(item);
        if (result[item] != undefined) {
            data.value = result[item];
        }
    });
});

var formAction = document.getElementById('form-action');
formAction.onsubmit = function (evt) {
    evt.preventDefault();
    var cf_arr = ['keyword', 'price', 'time'];
    var config = {};
    cf_arr.forEach(function (item) {
        var data = document.getElementById(item).value;
        config[item] = data;
    });
    config = JSON.stringify(config);
    chrome.storage.local.set({ 'config': config }, function () {
        alert('thành công');
    });
    return false;
};

/***/ })

/******/ });