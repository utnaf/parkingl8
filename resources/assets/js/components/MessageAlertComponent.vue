<template>
    <div class="alert" :class="className" role="alert" v-show="isVisible">
        {{ text }}
    </div>
</template>

<script>
    export default {
        name: 'message-alert',
        data: () => {
            return {
                isVisible: false,
                text: '',
                className: 'alert-primary'
            }
        },
        created() {
            this.$eventHub.$on('show-dialog', this.show);
        },
        beforeDestroy() {
            this.$eventHub.$off('show-dialog', this.show);
        },
        methods: {
            show(data) {
                this.text = data.text;
                this.className = data.className;
                this.isVisible = true;
                window.scrollTo(0, 0);
                setTimeout(() => {
                    this.isVisible = false;
                }, 4000);
            }
        }
    }
</script>
