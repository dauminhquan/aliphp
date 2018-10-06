const categoryUrl = [
    'aliexpress.com/category',
    'aliexpress.com/w/',
    'aliexpress.com/af/',
	'aliexpress.com/wholesale',
    'aliexpress.com/premium/'
];
const productUrl = [
    'store/product/',
    'aliexpress.com/item',

];
const urlGetRating = 'https://feedback.aliexpress.com/display/evaluationDsrAjaxService.htm?ownerAdminSeq=';
const urlGetShiper  = (productId,count=1,minPrice,maxPrice) => {
    return `https://freight.aliexpress.com/ajaxFreightCalculateService.htm?productid=${productId}&count=1&minPrice=${minPrice}&maxPrice=${maxPrice}&currencyCode=USD&transactionCurrencyCode=USD&country=US&abVersion=1`;
};
const curUrl = window.location.href;
export default {
    checkCategoryUrl(){
        return categoryUrl.some(function (u) {
            return curUrl.includes(u)
        })
    },
    checkProductUrl(){
        return productUrl.some(function (u) {
            return curUrl.includes(u);
        })
    }
}
