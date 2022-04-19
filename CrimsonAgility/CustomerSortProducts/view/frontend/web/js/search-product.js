/**
 * @api
 */
define([
    'jquery',
    'searchProductService',
    'mage/url',
    'Magento_Customer/js/customer-data',
    'mage/validation',
    'underscore'
], function ($, searchProductService, urlBuilder, customerData, _) {
    'use strict';

    return function (config, element) {
        const searchProducts = () => {
            let serviceUrl, payload;
            let lower = parseFloat($('#lowrange').val());
            let higher = parseFloat($('#highrange').val());
            let sorting = $('#sort').val();

            payload = {
                lower_range: lower,
                higher_range: higher,
                sorting: sorting
            };

            serviceUrl = urlBuilder.build('rest/default/V1/customer/ajax/search');
            return searchProductService(serviceUrl, payload);
        }

        $(element).click(function () {
            let dataForm = $('#addData');
            if (!dataForm.validation('isValid')) {
                customerData.set('messages', {
                    messages: [{
                        type: 'error',
                        text: 'Invalid form entry. Please review inserted data.'
                    }]
                });
                return false;
            }
            
            let response = searchProducts();

            response.success((e) => {
                if (e.length === 0) {
                    customerData.set('messages', {
                        messages: [{
                            type: 'warning',
                            text: 'No products match the price range.'
                        }]
                    });
                    return true;
                }
                let table = document.querySelector("table");
                let tbody = table.getElementsByTagName('tbody')[0];
                $('#custom-tbody').empty();
                generateTable(tbody, e);
                return true;
            }).error((data) => { 
                return false;
            });
        });
        
        function generateTable(body, data) {
            for (let element of data) {
                let row = body.insertRow();
                for (let key in element) {
                    let cell = row.insertCell();
                    switch (key) {
                        case 'thumbnail':
                            let img = document.createElement('img');
                            img.src = element[key];
                            img.width = 100;
                            img.height = 100;
                            cell.appendChild(img);
                            break;
                        case 'url':
                            let a = document.createElement('a');
                            let link = document.createTextNode('Go to Product Page');
                            a.appendChild(link);
                            a.title = 'Go to Product Page.';
                            a.href = element[key];
                            a.target = '_blank';
                            cell.appendChild(a);
                            break;
                        case 'price':
                            let price = document.createTextNode(parseFloat(element[key]).toFixed(2));
                            cell.appendChild(price);
                            break
                        default:
                            let text = document.createTextNode(element[key]);
                            cell.appendChild(text);
                    }
                }
            }
        }
    };
});
