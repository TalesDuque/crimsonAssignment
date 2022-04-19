define(['jquery'], function($) {
    'use strict';

    return function() {
        $.validator.addMethod(
            'greater-than-custom',
            function(value, element) {
                let lower = parseFloat($("#lowrange").val());
                return parseFloat(value) > lower;
            },
            $.mage.__('The higher range must greater than the lower range.')
        )
    }
});
