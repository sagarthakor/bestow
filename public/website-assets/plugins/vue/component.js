Vue.component('vue-datepicker', {
    props: ['value', 'name'],
    watch: {
        value: function (new_value) {
            this.input_value = new_value ? moment(new_value).format('DD MMM Y') : null
        }
    },
    data: function () {
        return {
            input_value: this.value ? moment(this.value).format('DD MMM Y') : null
        }
    },
    template: `<input type="text" ref="datePicker" class="form-control" :name="name" :value="input_value" readonly>`,
    mounted: function () {
        let self = this;
        $(this.$el).datepicker({
            autoclose: true,
            todayHighlight: true,
            format: "dd M yyyy",
        }).on('changeDate', function (e) {
            self.$emit('input', e.target.value)
        });
    }
});

Vue.component('vue-counter-button', {
    props: ['value', 'min', 'max', 'priceId'],
    watch: {
        value: function (newVal) {
            this.counter = newVal;
        }
    },
    data: function () {
        return {
            counter: this.value,
            min_value: this.min,
            max_value: this.max,
            product_price_id: this.priceId
        }
    },
    //language=HTML
    template: `
    <div class="counter-component">
    <div class="input-group " style="margin-top: 0.5rem">
    <span class="input-group-append">
    <button class="reduced btn quantity-left-minus btn-dark" type="button" @click="decrement">
    <i class="fa fa-minus"></i>
    </button>
    </span>
    <input type="text" class="form-control input-number text-center" title="Qty" :value="counter.current"
    maxlength="12" readonly>
    <span class="input-group-append">
    <button class="increase btn quantity-right-plus btn-dark" type="button" @click="increment"><i
    class="fa fa-plus"></i></button>
    </span>
    </div>
    </div>
    `,
    methods: {
        increment: function () {

            if (this.max_value > this.counter.current) {
                this.counter.current += 1;
                this.$emit('input', {action: 'increment', current: this.counter.current, actual: this.counter.actual});
                this.$emit('change-counter-btn', {
                    action: 'increment',
                    current: this.counter.current,
                    product_price_id: this.product_price_id
                });
            } else {
                swal("Oops", "This item has not enough stock, Support Admin", "error");
            }

        },
        decrement: function () {

            if (this.counter.current > this.min_value) {
                this.counter.current -= 1;
                this.$emit('input', {action: 'decrement', current: this.counter.current, actual: this.counter.actual})
                this.$emit('change-counter-btn', {
                    action: 'decrement',
                    current: this.counter.current,
                    product_price_id: this.product_price_id
                });
            }
        }
    }
});

Vue.component('counter-btn', {
    props: ['value', 'min', 'max', 'priceId', 'maxAlert'],
    watch: {
        value: function (newVal) {
            this.counter = newVal;
        }
    },
    data: function () {
        return {
            counter: this.value,
            minValue: this.min,
            maxValue: this.max,
            productPriceId: this.priceId,
            maxAlertMessage: this.maxAlert
        }
    },
    //language=HTML
    template: `
    <div class="counter-component">
    <div class="input-group " style="margin-top: 0.5rem">
    <span class="input-group-append">
    <button class="reduced btn quantity-left-minus btn-dark" type="button" @click="decrement"><i
    class="fa fa-minus la la-minus"></i></button>
    </span>
    <input type="text" class="form-control input-number text-center" title="Qty" :value="counter"
    maxlength="5"
    readonly>
    <span class="input-group-append">
    <button class="increase btn quantity-right-plus btn-dark" type="button" @click="increment"><i
    class="fa fa-plus la la-plus"></i></button>
    </span>
    </div>
    </div>
    `,
    methods: {
        increment: function () {

            if (this.maxValue > this.counter) {

                this.counter = Number(this.counter) + 1;

                this.$emit('input', this.counter);

                this.$emit('change-counter-btn', {
                    action: 'increment',
                    current: this.counter,
                    product_price_id: this.productPriceId
                });

            } else {

                if (this.maxAlertMessage) {
                    swal("Warning", this.maxAlertMessage, "warning");
                } else {
                    swal("Oops", "This item has not enough stock, Support Admin", "error");
                }
            }

        },
        decrement: function () {

            if (this.counter > this.minValue) {

                this.counter = Number(this.counter) - 1;
                this.$emit('input', this.counter);

                this.$emit('change-counter-btn', {
                    action: 'decrement',
                    current: this.counter,
                    product_price_id: this.productPriceId
                });
            }
        }
    }
});

Vue.component('vue-filer', {
    props: ['value', 'limit', 'maxSize', 'extensions', 'showThumb', 'addMore', 'name'],
    template: '<input type="file" :name="nameValue">',
    data: function () {
        return {
            nameValue: this.name ? this.name : 'image',
            filerInput: null,
            limitValue: this.limit ? this.limit : 1,
            maxSizeValue: this.maxSize ? this.maxSize : 2,
            allowExt: this.extensions ? this.extensions : ['jpg', 'jpeg', 'png'],
            displayThumbs: this.showThumbs ? this.showThumbs : true,
            addMoreValue: this.addMore === '0' ? false : true
        }
    },
    watch: {
        value: function (newValue, oldValue) {
            // $(this.$el).val(value).trigger('change')
        }
    },
    mounted: function () {

        let self = this;

        $(this.$el).filer({
            limit: self.limitValue,
            maxSize: self.maxSizeValue,
            extensions: self.allowExt,
            showThumbs: self.displayThumbs,
            addMore: self.addMoreValue,
            changeInput: true,
            onSelect: function () {

                setTimeout(() => {

                    let images = $('.jFiler-item-thumb-image').map(function () {
                        let imageSource = $(this).children('img').attr('src');
                        return imageSource.replace(/^data:image\/[a-z]+;base64,/, "");
                    });

                    self.$emit('input', images);

                }, 500);
            },
            onRemove: function () {

                setTimeout(() => {

                    let images = $('.jFiler-item-thumb-image').map(function () {
                        let imageSource = $(this).children('img').attr('src');
                        return imageSource.replace(/^data:image\/[a-z]+;base64,/, "");
                    });

                    self.$emit('input', images);

                }, 500);
            }
        });

    }
});

Vue.component('vue-select2', {
    props: ['options', 'value', 'allowSearch', 'placeHolder', 'emptyAfterSelect'],
    data: function () {
        return {
            select_options: this.options,
            doEmptyAfterSelect: this.emptyAfterSelect ? this.emptyAfterSelect : 0
        }
    },
    template: `<select class="full-width">
    <option value="" v-if="placeHolder">{{ placeHolder }}</option>
    <option v-for="select_option in select_options" :value="select_option.value">{{ select_option.label }}</option>
    </select>`,
    mounted: function () {

        let self = this;

        $(this.$el).select2({minimumResultsForSearch: this.allowSearch}).val(this.value).trigger('change').on('select2:select', function (event) {

            self.$emit('input', event.target.value);
            self.eventOnChange(event.target.value);

            if (parseInt(self.doEmptyAfterSelect) === 1) {
                setTimeout(() => {
                    $(self.$el).select2().val('').trigger('change');
                }, 1500);
            }

        });

    },
    watch: {
        value: function (value) {
            // $(this.$el).val(value).trigger('change')
        },
        options: function (options) {
            $(this.$el).empty().select2({data: options})
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    },
    methods: {
        eventOnChange(value) {
            this.$emit('change-select2', value);
        }
    }
});

Vue.filter('currency', function (value) {
    return new Intl.NumberFormat("en-IN", {style: "currency", currency: "INR"}).format(value);
});

Vue.filter('capitalize', function (value) {
    if (!value) return '';
    value = value.toString();
    return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.filter('slugify', function (value) {
    return value.toString().toLowerCase().replace(/\s+/g, '-').replace(/[^\w\-]+/g, '').replace(/\-\-+/g, '-').replace(/^-+/, '').replace(/-+$/, '');
});

Vue.filter('date', function (value, argument) {
    return moment(value).format(argument);
});

Vue.filter('str_limit', function (string, value) {
    if (string) {
        return string.length <= value ? string : string.substring(0, value) + '...';
    }
    return string;
});
