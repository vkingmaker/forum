<template>
        <div id="'reply-'+id" class="card" :class="isBest ? 'bg-secondary' : 'bg-light'">
            <div class="card-header">
                <div class="level">
                    <h5 class="flex">
                        <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name"></a>
                        said <span v-text="ago"></span>
                    </h5>

                    <div v-if="signedIn">
                        <favorite :reply="reply"></favorite>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div v-if="editing">
                    <form @submit="update">
                        <div class="form-group">
                            <textarea class="form-control" v-model="body" required></textarea>
                        </div>
                            <button class="btn btn-xs btn-primary">Update</button>
                            <button class="btn btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                        </form>
                    </div>

                <div v-else v-html="body"></div>
            </div>

            <div class="card-footer level" v-if="authorize('owns', reply) || authorize('owns', reply.thread)">
            <div v-if="authorize('owns',reply)">
                <button class="btn btn-xs mr-1 btn-secondary" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>
                <button class="btn btn-default btn-xs mr-1 border ml-auto" @click="markBestReply" v-if="authorize('owns', reply.thread)" v-show="!isBest">Best Reply?</button>
            </div>
        </div>

</template>


<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';

    export default {
        props: ['reply'],

        components: {
            Favorite
        },

        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
            };
        },

        computed: {
            ago() {
                return moment (this.reply.created_at).fromNow()+'...';
            }
        },

        created() {

            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id);
            });
        },


        methods: {
            update() {
                axios.patch('/replies/' + this.id, {
                    body: this.body
                })
                .catch(error => {

                    flash(error.response.data, 'danger');
                });

                this.editing = false;

                flash('Updated!');
            },

            destroy() {

                axios.delete('/replies/' + this.id);

                $(this.$el).fadeOut(300, () => {

                    flash('Your reply has been deleted','danger');

                });

                // this.$emit('deleted', this.data.id);
            },

            markBestReply() {

                    axios.post('/replies/' + this.id + '/best');

                    window.events.$emit('best-reply-selected', this.id);

            }

        }
    }

</script>
