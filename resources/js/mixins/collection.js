export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        add(item) {
            this.items.push(item);

            this.$emit('added');
        },
        remove(index) {
            this.items.slice(index, 1);

            this.$emit('removed');

        }
    }
}
