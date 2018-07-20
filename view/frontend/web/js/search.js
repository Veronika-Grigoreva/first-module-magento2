define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/url'
], function ($, customerData, urlBuilder) {
    var xhr = null;
    $("#sku-input").keyup(function () {
        var search = {'sku' : $('.text-search').val()};
        if ($('.text-search').val().length >= 1) {
            if (xhr) xhr.abort();
            xhr = $.ajax({
                type: 'post',
                url: urlBuilder.build('rest/V1/addCart/search'),
                data: JSON.stringify(search),
                dataType: 'JSON',
                contentType: 'application/json',
                success: function (response) {
                    $('#search-result').empty();
                    response.forEach(function (val) {
                        $('#search-result').append('<option>' + val['sku'] + '</option>');
                    });
                }
            });
        }
    });
    }
);