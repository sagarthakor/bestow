{{--
    Product picker, shared by every screen that puts products on a document -
    quotation first, then sales order, delivery challan, invoice and purchase
    order. Include it once per page, inside the javascript section (it needs
    jQuery and select2), and attach it to each product <select>:

        ProductSearch.attach($('#product3'), 'product');

    Statuses: 'product', 'service', 'bom', and 'po_product' (products plus raw
    material, which only purchase orders may pick).

    Rows are searched on the server - the product table runs to thousands of
    rows and used to be dumped into the page for every line - and matched on
    item code, size, colour, name and SKU together, so the operator can type the
    combination they know the item by, in any order.
--}}

<script>
    window.ProductSearch = (function () {
        var url = "{{ route('admin.product.search_options') }}";

        /**
         * @param $select  jQuery <select> to turn into the picker
         * @param status   which catalogue to search - see the statuses above
         * @param options  select2 overrides, e.g. {placeholder: '...'}
         */
        function attach($select, status, options) {
            if (!$select || !$select.length) {
                return;
            }

            $select.select2($.extend({
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {term: params.term, status: status || 'product'};
                    },
                    processResults: function (data) {
                        return data;
                    },
                    cache: true
                },
                // One character is enough - an item code alone is often that
                // short, and the server limits how much comes back.
                minimumInputLength: 1,
                placeholder: 'Search item code / size / name...',
                width: '100%'
            }, options || {}));
        }

        /**
         * Word-by-word matching for a dropdown whose options are already in the
         * page - the older screens still render the whole catalogue into every
         * row. Same rule as the server search: every word typed must appear in
         * the option, in any order.
         */
        function matcher(params, data) {
            var term = $.trim(params.term || '');

            if (term === '') {
                return data;
            }
            if (typeof data.text === 'undefined') {
                return null;
            }

            var text = data.text.toLowerCase();
            var words = term.toLowerCase().split(/\s+/);

            for (var i = 0; i < words.length; i++) {
                if (words[i] !== '' && text.indexOf(words[i]) === -1) {
                    return null;
                }
            }

            return data;
        }

        /**
         * @param $select  jQuery <select> whose options are already rendered
         */
        function attachLocal($select, options) {
            if (!$select || !$select.length) {
                return;
            }

            $select.each(function () {
                // The layout initialises every .js-example-basic-single on load,
                // so the plain picker has to be taken off before this one goes on.
                if ($(this).data('select2')) {
                    $(this).select2('destroy');
                }
            });

            $select.select2($.extend({
                matcher: matcher,
                placeholder: 'Search item code / size / name...',
                width: '100%'
            }, options || {}));
        }

        /**
         * Applies attachLocal() to a selector now, and again after a row is
         * added - those screens build their rows in JS and re-run the plain
         * select2 on them.
         *
         * @param selector    the product dropdowns, e.g. '#caltable select.product'
         * @param addButtons  optional selector of the "+ Add" buttons
         */
        function local(selector, addButtons) {
            var apply = function () { attachLocal($(selector)); };

            $(apply);

            if (addButtons) {
                $(document).on('click', addButtons, function () { setTimeout(apply, 60); });
            }
        }

        return {attach: attach, attachLocal: attachLocal, local: local, url: url};
    })();
</script>
