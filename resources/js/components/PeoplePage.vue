<template>
    <div>
        <div class="form-body mb-4" v-if="createPeoplePer">
            <form id="addCashForm">
                <h4 class="mb-1">
                    <i class="fas fa-plus mr-1"></i>
                    {{ trans("main.add_person") }}
                </h4>
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row align-items-center">
                            <label class="col-md-3 mb-0 text-center" for="name"
                                >{{ trans("main.name") }}:
                                <span class="text-danger">*</span></label
                            >
                            <div class="col-md-9">
                                <input
                                    type="text"
                                    id="name"
                                    class="form-control"
                                    name="name"
                                    v-model="values.name"
                                />
                                <span
                                    class="invalid-feedback d-block mt-2"
                                    role="alert"
                                >
                                    <strong>{{ errors.name }}</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button
                            class="btn btn-primary btn-block"
                            id="addPersonBtn"
                            @click.prevent="sendRequest($event)"
                            :disabled="loading"
                        >
                            <i
                                :class="{
                                    fas: true,
                                    'fa-circle-notch': true,
                                    'fa-spin': true,
                                    'd-none': !loading
                                }"
                            ></i>
                            {{ trans("main.add") }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <hr v-if="createPeoplePer" />
        <div class="row mb-3">
            <input
                type="text"
                class="form-control text-center col-md-6 offset-md-3"
                :placeholder="trans('main.search')"
                v-model="q"
            />
        </div>
        <div class="table-responsive">
            <table class="table table-striped text-center" id="peopleTable">
                <thead>
                    <tr>
                        <th scope="col">{{ trans("main.id") }}</th>
                        <th scope="col">{{ trans("main.name") }}</th>
                        <th scope="col">{{ trans("main.created_at") }}</th>
                        <th scope="col">{{ trans("main.actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="person in people" :key="person.id">
                        <th scope="row">{{ person.id }}</th>
                        <td>{{ person.name }}</td>
                        <td>{{ person.created_at }}</td>
                        <td>
                            <a
                                :href="person.show_url"
                                class="text-warning"
                                :title="trans('main.view_person_dues')"
                                ><i class="fas fa-scroll"></i
                            ></a>
                            <button
                                class="btn py-0 text-danger"
                                @click.prevent="deletePerson($event, person.id)"
                                :title="trans('main.delete_person')"
                                v-if="deletePeoplePer"
                            >
                                <i class="fas fa-ban"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        "createPeoplePer",
        "deletePeoplePer",
        "addPersonUrl",
        "peopleArr",
        "deletePersonUrl"
    ],

    data() {
        return {
            values: {
                name: ""
            },
            errors: {
                name: ""
            },
            loading: false,

            all: this.peopleArr,
            people: this.peopleArr,
            q: ""
        };
    },

    watch: {
        q: function() {
            this.search();
        },
        all: function() {
            this.people = this.all;
        }
    },

    methods: {
        validateForm() {
            this.errors.name = "";

            if (this.values.name == "") {
                this.errors.name = this.trans(
                    "custom_validation.name.required"
                );
            } else if (this.values.name.length > 255) {
                this.errors.name = this.trans("custom_validation.name.max:255");
            }

            return this.errors.name == "" ? true : false;
        },

        sendRequest(e) {
            this.loading = true;

            if (!this.validateForm()) {
                this.loading = false;
                return false;
            }

            axios
                .post(this.addPersonUrl, {
                    name: this.values.name
                })
                .then(res => res.data)
                .then(data => {
                    this.all.unshift(data.data);

                    this.values.name = "";
                    this.errors.name = "";

                    toastr.success(
                        this.trans("messages.body.person_added_successfully"),
                        this.trans("messages.title.success")
                    );
                })
                .catch(err => {
                    if (err.response.status == 400) {
                        let errors = err.response.data.errors;
                        if ("name" in errors) {
                            this.errors.name = errors.name[0];
                        }
                    } else {
                        toastr.error(
                            err.response.data.response_message,
                            err.response.data.response_title
                        );
                    }
                })
                .finally(() => (this.loading = false));
        },

        deletePerson(e, personId) {
            Swal.fire({
                title: this.trans("main.are_you_sure"),
                text: this.trans("main.not_revert").replace("\\", ""),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: this.trans("main.yes_delete_it"),
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .delete(this.deletePersonUrl, {
                            data: {
                                personId: personId
                            }
                        })
                        .then(res => res.data)
                        .then(data => {
                            this.all = this.all.filter(
                                item => item.id != personId
                            );

                            toastr.success(
                                data.response_message,
                                data.response_title
                            );
                        })
                        .catch(err => {
                            toastr.error(
                                err.response.data.response_message,
                                err.response.data.response_title
                            );
                        });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        },

        search() {
            this.people = this.all;
            if (this.q) {
                this.people = this.people
                    .filter(item =>
                        item.name.toLowerCase().includes(this.q.toLowerCase())
                    )
                    .map(item => {
                        let points = 0,
                            name = item.name.toLowerCase(),
                            q = this.q.toLowerCase();

                        if (name.startsWith(q)) {
                            points += 2;
                        } else if (name.includes(q)) {
                            points += 1;
                        }
                        return { ...item, points };
                    })
                    .sort((a, b) => b.points - a.points);
            }
        }
    }
};
</script>
