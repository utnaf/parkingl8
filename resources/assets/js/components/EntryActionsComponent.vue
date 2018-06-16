<template>
    <button type="button" class="btn btn-sm"
            v-bind:id="tooltipId"
            :class="data.buttonClass"
            :disabled="data.isDisabled || isSubmitting"
            @click="doAction(data.action)">
        {{ data.text }}
    </button>
</template>

<script>
    import moment from 'moment-timezone';

    export default {
        name: 'entry-actions',
        props: ['entry'],
        created() {
            this.tooltip('');
        },
        data: () => {
            return {
                isSubmitting: false
            }
        },
        computed: {
            tooltipId() {
                return 'ttEntry' + this.entry.id;
            },
            data() {
                let result = {
                    isDisabled: true,
                    text: this.$options.filters.translate('no_action'),
                    action: false,
                    buttonClass: 'btn-info',
                };

                if (this.entry.price === null) {
                    result = {
                        isDisabled: false,
                        text: this.$options.filters.translate('pay'),
                        action: 'requestPrice',
                        buttonClass: 'btn-success',
                    };
                }
                else if (this.entry.payed_at === null) {
                    result = {
                        isDisabled: false,
                        text: this.$options.filters.translate('confirm'),
                        action: 'pay',
                        buttonClass: 'btn-success',
                    };
                }
                else if (this.entry.exited_at === null) {
                    result = {
                        isDisabled: false,
                        text: this.$options.filters.translate('exit'),
                        action: 'exit',
                        buttonClass: 'btn-info',
                    };
                }

                return result;
            }
        },
        methods: {
            getStateEntryIndex() {
                return this.$store.state.entries.indexOf(this.entry);
            },
            tooltip(text, action = null) {
                const element = $('#' + this.tooltipId);
                element.tooltip({
                    title: text,
                    placement: 'left'
                });

                if (action !== null) {
                    element.tooltip(action);
                }
            },
            requestPrice() {
                this.isSubmitting = true;
                window.axios.get(window.api.getPrice.replace(':id', this.entry.id))
                    .then(({data}) => {
                        const price = data.price;
                        this.isSubmitting = false;
                        this.$store.commit('updateEntryPrice', {
                            index: this.getStateEntryIndex(),
                            price: price
                        });
                        this.tooltip(
                            this.$options.filters.translate(
                                'price_tooltip',
                                this.$options.filters.formatNumber(price)
                            ),
                            'show'
                        );
                    });
            },
            pay() {
                this.isSubmitting = true;
                window.axios.patch(
                    window.api.updateEntry.replace(':id', this.entry.id),
                    {
                        price: this.entry.price
                    }
                ).then(({data}) => {
                    this.isSubmitting = false;
                    this.tooltip('', 'disable');
                    this.$store.commit('updateEntry', {
                        index: this.getStateEntryIndex(),
                        entry: data.entry
                    });
                });
            },
            exit() {
                this.isSubmitting = true;
                window.axios.patch(
                    window.api.updateEntry.replace(':id', this.entry.id),
                    {
                        exited_at: moment().format('YYYY-MM-DD HH:mm:ss')
                    }
                ).then(({data}) => {
                    this.$eventHub.$emit('show-dialog', {
                        className: 'alert-success',
                        text: this.$options.filters.translate('entry_is_archived')
                    });
                    this.isSubmitting = false;
                    this.$store.commit('updateEntry', {
                        index: this.getStateEntryIndex(),
                        entry: data.entry
                    });
                });
            },
            doAction(action) {
                switch (action) {
                    case 'exit':
                        return this.exit();
                    case 'pay':
                        return this.pay();
                    case 'requestPrice':
                        return this.requestPrice();
                }
            }
        }
    }
</script>