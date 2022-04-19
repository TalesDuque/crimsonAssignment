/**
 * @api
 */
define(
    [
        'jquery',
        'mage/storage'
    ],
    function ($, storage) {
        'use strict';

        return function (serviceUrl, payload) {
            $('body').trigger('processStart');
            return storage.post(
                serviceUrl, JSON.stringify(payload), true, 'application/json'
            ).fail(
                function (response) {
                }
            ).success(
                function (response) {
                }
            ).always(
                function () {
                    $('body').trigger('processStop');
                }
            );
        };
    }
);
