define ([
    'jquery',
    'Magento_Customer/js/customer-data',
    'mage/url'
],   function ($, customerData, urlBuilder) {
            function main(config) {
            //var AjaxUrl  = config.AjaxUrl;
            $(document).on('click', '.sub', function (event) {
                var formData = new FormData();
                event.preventDefault();
                    formData.append('skuInput', $('.text-search').val());
                    formData.append('csvInput', $('#csv-input')[0].files[0]);
                $.ajax({
                    showLoader: true,
                    url: urlBuilder.build('rest/V1/addCart/add'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    type: 'POST'
                }).done(function () {
                    document.getElementById('form-add').reset();
                    delete(formData);
                    customerData.reload(['cart']);
                });
            });
        };
        return main;

    }
);