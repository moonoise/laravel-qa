<template>
    <div>
        <div class="d-flex flex-column vote-controls">
            <a @click.prevent="voteUp" :title="title('up')"
                class="vote-up" :class="classes" >
                    <i class="fas fa-caret-up fa-2x"></i>
                </a>
                <span class="votes-count">{{ count }}</span>
                <a @click.prevent="voteDown" :title="title('down')"
                class="vote-down" :class="classes" >
                        <i class="fas fa-caret-down fa-2x"></i>
                </a>
                    <favorite v-if="name === 'questions'" :question="model" ></favorite>

                    <accept v-else :answer="model"></accept>
            </div>
    </div>
</template>

<script>
import Favorite from './Favorite.vue';
import Accept from './Accept.vue';

export default {
    props: ['name','model'],

    data () {
        return {
            count: this.model.votes_count || 0,
            id: this.model.id
        }
    },

    computed : {
        classes () {
            return this.signedIn ? '' : 'off' ;
        },
        endpoint () {
            return `/${this.name}/${this.id}/vote`
        }
    },

    components: {
        Favorite,
        Accept
    },

    methods: {
        title(voteType) {
            let titles = {
                up: `This ${this.name} is useful`,
                down: `This ${this.name} is not useful`
            };
            return titles[voteType];
        },

        voteUp () {
            this._vote(1);
        },

        voteDown () {
            this._vote(-1)
        },

        _vote (vote){
            // console.log(this.signedIn)
            if(! this.signedIn) {
                this.$toast.warning(`Please login to vote the ${this.name} ` , "Warning", {
                    timeout:3000,
                    position: 'bottomLeft'
                });
                return;
            }
            axios.post(this.endpoint,{vote})
            .then(res => {
               this.$toast.success(res.data.message,"Success", {
                   timeout: 3000,
                   position: 'bottomLeft'
               });
                this.count = res.data.votesCount ;
            });
        }
    }
}
</script>
