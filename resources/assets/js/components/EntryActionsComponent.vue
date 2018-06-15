<template>
    <button type="button" class="btn btn-sm"
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
    computed: {
        data() {
            let result = {
                isDisabled: true,
                text: 'No action',
                action: false,
                buttonClass: 'btn-info',
            };

            if(this.entry.payed_at === null) {
                result = {
                    isDisabled: false,
                    text: 'Pay',
                    action: 'pay',
                    buttonClass: 'btn-success',
                };
            }
            else if(this.entry.exited_at === null) {
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
        requestPrice() {
            window.axios.get(window.api.getPrice.replace(':id', this.entry.id))
                .then(function({data}) {
                    console.log(data.price);
                });
        },
        pay() {
            console.log('Pay!');
        },
        exit() {
            console.log('Exit!');
        },
        doAction(action) {
            return action === 'exit' ? this.exit() : this.requestPrice()
        }
    }
}
</script>