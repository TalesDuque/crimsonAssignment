var config = {
    map: {
        '*': {
            searchProductService: 'CrimsonAgility_CustomerSortProducts/js/search-product-service',
            searchProduct: 'CrimsonAgility_CustomerSortProducts/js/search-product',
        }
    },
    config: {
        mixins: {
            'mage/validation': {
                'CrimsonAgility_CustomerSortProducts/js/fivetimesvalidation-mixin': true,
                'CrimsonAgility_CustomerSortProducts/js/greaterthanvalidation-mixin': true
            }
        }
    }
}
