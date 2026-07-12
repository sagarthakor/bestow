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

Vue.component("date-picker", {
    props: ["value", "inputSize"],
    data: function () {
        return {
            dateValue: this.value
        }
    },
    template: '<input type="text" class="form-control" :value="dateValue" :class="inputSize ? inputSize : \'\'" placeholder="YYYY-MM-DD" readonly>',
    methods: {},
    mounted: function () {
        var e = this,
            t = this;
        $(this.$el).flatpickr({
            dateFormat: "Y-m-d",
            onChange: function (n, a, i) {
                e.dateValue = a, t.$emit("input", a)
            }
        })
    }
});

Vue.component("time-picker", {
    props: ["value", "inputSize"],
    data: function () {
        return {
            timeValue: this.value
        }
    },
    template: '<input type="text" class="form-control" :value="timeValue" :class="inputSize ? inputSize : \'\'" placeholder="HH:MM" readonly>',
    methods: {},
    mounted: function () {
        var e = this,
            t = this;
        $(this.$el).flatpickr({
            enableTime: !0,
            noCalendar: !0,
            onChange: function (n, a, i) {
                e.timeValue = a, t.$emit("input", a)
            }
        })
    }
});

Vue.component('vue-counter-button', {
    props: ['value', 'min', 'max', 'productPriceId'],
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
            priceId: this.productPriceId,
        }
    },
    template: ` <div class="input-group ">
                    <span class="btn btn-primary text-white btn-sm font-medium" @click="decrement"><i class="ti ti-minus fs-4"></i></span>
                     <input type="text" :value="counter.current" class="form-control text-center p-0" readonly/>
                    <span class="btn btn-primary text-white btn-sm font-medium" @click="increment"><i class="ti ti-plus fs-4"></i></span>
                 </div>`,
    methods: {
        increment: function () {

            if (this.max_value > this.counter.current) {
                this.counter.current += 1;

                this.$emit('input', {
                    action: 'increment',
                    current: this.counter.current,
                    productPriceId: this.priceId
                });

                this.$emit('change-counter-btn', {
                    action: 'increment',
                    current: this.counter.current,
                    productPriceId: this.priceId
                });
            }
            else {
                swal("Oops", "This item has not enough stock, Support Admin", "error");
            }

        },
        decrement: function () {

            if (this.counter.current > this.min_value) {
                this.counter.current -= 1;

                this.$emit('input', {
                    action: 'decrement',
                    current: this.counter.current,
                    productPriceId: this.priceId
                });

                this.$emit('change-counter-btn', {
                    action: 'decrement',
                    current: this.counter.current,
                    productPriceId: this.priceId
                });
            }
        }
    }
});

Vue.component('vue-select2', {
    props: ["options", "value", "allowSearch", "placeHolder", "multiple", "selectSize", "modalId", "emptyAfterSelect"],
    data: function () {
        return {
            select_options: this.options,
            doEmptyAfterSelect: this.emptyAfterSelect ? this.emptyAfterSelect : 0
        }
    },
    template: `<select class="form-control select2">
               <option value="" v-if="placeHolder">{{ placeHolder }}</option>
               <option v-for="select_option in select_options" :value="select_option.id">{{ select_option.name }}</option>
               </select>`,
    mounted: function () {

        let self = this;

        $(this.$el).select2({
            dropdownParent: self.modalId ? $("#" + self.modalId) : $("body"),
            minimumResultsForSearch: this.allowSearch,
            placeholder: this.placeHolder,
            multiple: this.multiple,
            containerCssClass: this.selectSize ? "select2-" + this.selectSize : ""
        }).val(this.value).trigger('change').on('select2:select', function (event) {

            self.$emit('input', event.target.value);
            self.eventOnChange(event.target.value);

            if (parseInt(self.doEmptyAfterSelect) === 1) {
                setTimeout(() => {
                    $(self.$el).select2({
                        placeholder: self.placeHolder
                    }).val(null).trigger('change');
                }, 1500);
            }

        }).on("select2:unselect", (function() {
            self.$emit("input", $(t.$el).val()), self.eventOnChange($(self.$el).val())
        }));

    },
    watch: {
        value: function (value) {
            $(this.$el).val(value).trigger('change')
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

Vue.mixin({methods: {}});

// --- Global Filters ---- //
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
Vue.filter("formatNumber", function (value) {
    return Math.floor(value).toFixed(2)
});
$('.select2').select2();
