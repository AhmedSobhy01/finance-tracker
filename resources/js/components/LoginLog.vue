<template>
    <div>
        <div class="h1 mb-4 text-center">
            {{ trans("main.last_10_logins") }}
        </div>

        <table class="table text-center">
            <thead>
                <tr>
                    <th scope="col">{{ trans("main.user_id") }}</th>
                    <th scope="col">{{ trans("main.user_username") }}</th>
                    <th scope="col">{{ trans("main.login_time") }}</th>
                    <th scope="col">{{ trans("main.login_ip") }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="log in logs" :key="log.id">
                    <th scope="row">
                        <a
                            :href="log.user_url"
                            class="text-decoration-none text-secondary"
                            >{{ log.user_id }}</a
                        >
                    </th>
                    <td>
                        <a
                            :href="log.user_url"
                            class="text-decoration-none text-secondary"
                            >{{ log.user_username }}</a
                        >
                    </td>
                    <td>{{ log.login_time }}</td>
                    <td>{{ log.login_ip }}</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-center align-items-center">
            <div class="loadingio-spinner-rolling-yj2b1kram4" v-if="loading">
                <div class="ldio-mfar5lyde2">
                    <div></div>
                </div>
            </div>
            <button
                class="btn btn-primary"
                v-if="!loading && !end"
                @click="loadLog"
            >
                {{ trans("main.load_more") }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["getLogUrl"],

    data() {
        return {
            logs: [],
            page: 1,
            loading: true,
            end: false
        };
    },

    methods: {
        loadLog() {
            this.loading = true;
            axios
                .post(this.getLogUrl, null, {
                    params: {
                        page: this.page
                    }
                })
                .then(res => res.data)
                .then(data => {
                    this.logs.push(...data.data.logs);
                    this.page++;
                    this.end = data.data.end ? true : false;
                })
                .catch(err => {
                    toastr.error(
                        err.response.data.response_message,
                        err.response.data.response_title
                    );
                })
                .finally(() => (this.loading = false));
        }
    },

    mounted() {
        this.loadLog();
    }
};
</script>

<style scoped>
@keyframes ldio-mfar5lyde2 {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}
.ldio-mfar5lyde2 div {
    position: absolute;
    width: 60px;
    height: 60px;
    border: 10px solid #000000;
    border-top-color: transparent;
    border-radius: 50%;
}
.ldio-mfar5lyde2 div {
    animation: ldio-mfar5lyde2 1s linear infinite;
    top: 50px;
    left: 50px;
}
.loadingio-spinner-rolling-yj2b1kram4 {
    width: 40px;
    height: 40px;
    display: inline-block;
    overflow: hidden;
    background: none;
}
.ldio-mfar5lyde2 {
    width: 100%;
    height: 100%;
    position: relative;
    transform: translateZ(0) scale(0.4);
    backface-visibility: hidden;
    transform-origin: 0 0;
}
.ldio-mfar5lyde2 div {
    box-sizing: content-box;
}
</style>
