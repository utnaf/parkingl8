<template>
    <button type="button" class="btn btn-sm"
            v-bind:id="tooltipId"
            :class="data.buttonClass"
            :disabled="data.isDisabled"
            @click.once="doAction(data.action)">
        {{ data.text }}
    </button>
</template>

<script>
    export default {
        name: 'entry-actions',
        props: ['entry'],
        created() {
            this.tooltip('');
        },
        computed: {
            tooltipId() {
                return 'ttEntry' + this.entry.id;
            },
            data() {
                let result = {
                    isDisabled: true,
                    text: 'No action',
                    action: false,
                    buttonClass: 'btn-info',
                };

                if (this.entry.price === null) {
                    result = {
                        isDisabled: false,
                        text: 'Pay',
                        action: 'requestPrice',
                        buttonClass: 'btn-success',
                    };
                }
                else if (this.entry.payed_at === null) {
                    result = {
                        isDisabled: false,
                        text: 'Confirm',
                        action: 'pay',
                        buttonClass: 'btn-success',
                    };
                }
                else if (this.entry.exited_at === null) {
                    result = {
                        isDisabled: false,
                        text: 'Exit',
                        action: 'exit',
                        buttonClass: 'btn-info',
                    };
                }

                return result;
            }
        },
        methods: {
            getStateEntryId() {
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
                window.axios.get(window.api.getPrice.replace(':id', this.entry.id))
                    .then(({data}) => {
                        this.$store.commit('updateEntryPrice', this.getStateEntryId(), data.price);
                        this.tooltip(
                            'Price is ' + this.$options.filters.formatNumber(data.price) + '. Click again to complete the payment',
                            'show'
                        );
                    });
            },
            pay() {
                console.log('Pay!');
            },
            exit() {
                console.log('Exit!');
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