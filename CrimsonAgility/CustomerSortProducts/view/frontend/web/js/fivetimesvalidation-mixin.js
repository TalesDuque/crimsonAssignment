define(['jquery'], function($) {
    'use strict';

    return function() {
        $.validator.addMethod(
            'validate-five-times',
            function(value, element) {
                let lower = parseFloat($("#lowrange").val());
                return ((parseFloat(value) <= 5*lower && lower !== 0.0) || (lower === 0.0 && value <= 5));
            },
            $.mage.__('The higher range must not be greater than 5 times the lower range.')
        )
    }
});
