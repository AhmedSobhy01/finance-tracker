<template>
    <div class="form-body mb-4">
        <form id="addCashForm">
            <h4 class="mb-1">
                <i class="fas fa-plus mr-1"></i>
                {{ trans("main.add_account") }}
            </h4>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label class="col-md-2 mb-0 text-center" for="username"
                            >{{ trans("main.username") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-10">
                            <input
                                type="text"
                                id="username"
                                class="form-control"
                                name="username"
                                v-model="values.username"
                                autocomplete="off"
                            />
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.username }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row align-items-center">
                        <label class="col-md-4 mb-0 text-center" for="password"
                            >{{ trans("main.password") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-8">
                            <input
                                type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                v-model="values.password"
                                autocomplete="off"
                            />
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.password }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row align-items-center">
                        <label
                            class="col-md-4 mb-0 text-center"
                            for="password_confirmation"
                            >{{ trans("main.confirm_password") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-8">
                            <input
                                type="password"
                                id="password_confirmation"
                                class="form-control"
                                name="password_confirmation"
                                v-model="values.passwordConfirmation"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button
                        class="btn btn-primary btn-block"
                        id="addAccountBtn"
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
</template>

<script>
export default {
    props: ["addAccountUrl"],

    data() {
        return {
            values: {
                username: "",
                password: "",
                passwordConfirmation: ""
            },
            errors: {
                username: "",
                password: ""
            },
            loading: false
        };
    },

    methods: {
        validateForm() {
            this.errors.username = "";
            this.errors.password = "";

            if (this.values.username == "") {
                this.errors.username = this.trans(
                    "custom_validation.username.required"
                );
            } else if (this.values.username.length > 255) {
                this.errors.username = this.trans(
                    "custom_validation.username.max:255"
                );
            }

            if (this.values.password == "") {
                this.errors.password = this.trans(
                    "custom_validation.password.required"
                );
            } else if (this.values.password.length > 255) {
                this.errors.password = this.trans(
                    "custom_validation.password.max:255"
                );
            } else if (
                this.values.passwordConfirmation !== this.values.password
            ) {
                this.errors.password = this.trans(
                    "custom_validation.password.confirmed"
                );
            }

            return this.errors.username == "" && this.errors.password == ""
                ? true
                : false;
        },

        sendRequest(e) {
            this.loading = true;

            if (!this.validateForm()) {
                this.loading = false;
                return false;
            }

            axios
                .post(this.addAccountUrl, {
                    username: this.values.username,
                    password: this.values.password,
                    password_confirmation: this.values.passwordConfirmation,
                    timezone: moment.tz.guess()
                })
                .then(res => res.data)
                .then(data => {
                    const tableData = document.querySelector(
                        "#accountsTable tbody"
                    );
                    data = data.data;
                    let newData = `
                            <tr>
                                <th scope="row">${data.id}</th>
                                <td>${data.username}</td>
                                <td>${data.created_at}</td>
                                <td>
                                    <a href="${
                                        data.edit_url
                                    }" class="text-warning" title="${this.trans(
                        "main.edit_account"
                    )}"><i class="fas fa-user-edit"></i></a>
                                    <button class="delete-person-btn btn py-0 text-danger" onclick="deleteAccount(event, ${
                                        data.id
                                    })" title="${this.trans(
                        "main.delete_account"
                    )}"><i class="fas fa-ban"></i></button>
                                </td>
                            </tr>
                    `;
                    tableData.innerHTML = newData + tableData.innerHTML;

                    this.values.username = "";
                    this.errors.username = "";
                    this.values.password = "";
                    this.errors.password = "";
                    this.values.passwordConfirmation = "";

                    toastr.success(
                        this.trans("messages.body.account_added_successfully"),
                        this.trans("messages.title.success")
                    );
                })
                .catch(err => {
                    if (err.response.status == 400) {
                        let errors = err.response.data.errors;
                        if ("username" in errors) {
                            this.errors.username = errors.username[0];
                        }
                        if ("password" in errors) {
                            this.errors.password = errors.password[0];
                        }
                    } else {
                        toastr.error(
                            err.response.data.response_message,
                            err.response.data.response_title
                        );
                    }
                })
                .finally(() => (this.loading = false));
        }
    }
};
</script>
