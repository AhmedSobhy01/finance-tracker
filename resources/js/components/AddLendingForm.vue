<template>
    <div class="form-body mb-4">
        <form id="addLendingForm">
            <h4 class="mb-1">
                <i class="fas fa-plus mr-1"></i>
                {{ trans("main.add_lending") }}
            </h4>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 mb-0 text-center" for="amount"
                            >{{ trans("main.person") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-9">
                            <select
                                class="form-control"
                                name="person"
                                id="person"
                                v-model="values.person"
                            >
                                <option
                                    v-for="person in people"
                                    :key="person.id"
                                    :value="person.id"
                                    >{{ person.name }}</option
                                >
                            </select>
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.person }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 mb-0 text-center" for="amount"
                            >{{ trans("main.amount") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-9">
                            <input
                                type="number"
                                min="0.1"
                                max="1000000"
                                step="0.1"
                                id="amount"
                                class="form-control"
                                name="amount"
                                v-model="values.amount"
                            />
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.amount }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label
                            class="col-md-3 mb-0 text-center"
                            for="description"
                            >{{ trans("main.description") }}:</label
                        >
                        <div class="col-md-9">
                            <textarea
                                maxlength="100"
                                id="description"
                                class="form-control"
                                name="description"
                                style="resize: none;"
                                v-model="values.description"
                            ></textarea>
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.description }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label
                            class="col-md-3 mb-0 text-center"
                            for="created_at"
                            >{{ trans("main.created_at") }}:
                        </label>
                        <div class="col-md-9">
                            <input
                                type="datetime-local"
                                id="created_at"
                                class="form-control"
                                name="created_at"
                                v-model="values.created_at"
                            />
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.created_at }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button
                        class="btn btn-primary btn-block"
                        id="addTransactionBtn"
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
    props: ["addLendingUrl", "isMorePages", "people", "addToTable"],

    data() {
        return {
            values: {
                person: "",
                amount: "",
                description: "",
                created_at: ""
            },
            errors: {
                person: "",
                amount: "",
                description: "",
                created_at: ""
            },
            loading: false
        };
    },

    methods: {
        validateForm() {
            this.errors.person = "";
            this.errors.amount = "";
            this.errors.description = "";
            this.errors.created_at = "";

            if (this.values.person == "") {
                this.errors.person = this.trans(
                    "custom_validation.person.required"
                );
            }

            if (this.values.amount == "") {
                this.errors.amount = this.trans(
                    "custom_validation.amount.required"
                );
            } else if (isNaN(this.values.amount)) {
                this.errors.amount = this.trans(
                    "custom_validation.amount.numeric"
                );
            } else if (this.values.amount < 0.01) {
                this.errors.amount = this.trans(
                    "custom_validation.amount.min:0.01"
                );
            } else if (this.values.amount > 1000000) {
                this.errors.amount = this.trans(
                    "custom_validation.amount.max:1000000"
                );
            }

            if (this.values.description.length > 100) {
                this.errors.description = this.trans(
                    "custom_validation.description.max:100"
                );
            }

            return this.errors.person == "" &&
                this.errors.amount == "" &&
                this.errors.description == "" &&
                this.errors.created_at == ""
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
                .post(this.addLendingUrl, {
                    personId: this.values.person,
                    amount: this.values.amount,
                    description: this.values.description,
                    created_at: moment
                        .utc(moment(this.values.created_at).utc())
                        .format()
                })
                .then(res => res.data)
                .then(data => {
                    if (this.addToTable) {
                        const tableData = document.querySelector(
                            "#lendingsTable tbody"
                        );
                        data = data.data;
                        if (this.isMorePages) tableData.lastChild.remove();
                        let newData = `
                            <tr>
                                <th scope="row">${data.process_serial}</th>
                                <td><a href="${
                                    data.person_show_url
                                }" class="text-decoration-none text-secondary">${
                            data.person.name
                        }</a></td>
                                <td>${data.amount}</td>
                                <td class="word-break-all">${
                                    data.description
                                }</td>
                                <td>${data.created_at}</td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        ${
                                            data.paid_at === null
                                                ? '<button class="paid-lending-btn btn py-0 text-success" onclick="changeState(event, ' +
                                                  data.id +
                                                  ')" title="' +
                                                  this.trans(
                                                      "main.lending_paid"
                                                  ) +
                                                  '"><i class="fas fa-check"></i></button>'
                                                : '<button class="paid-lending-btn btn py-0 text-danger" onclick="changeState(event, ' +
                                                  data.id +
                                                  ')" title="' +
                                                  this.trans(
                                                      "main.lending_unpaid"
                                                  ) +
                                                  '"><i class="fas fa-times"></i></button>'
                                        }
                                        <a href="${
                                            data.view_url
                                        }" class="text-secondary" title="${this.trans(
                            "main.view_process"
                        )}"><i class="fas fa-eye"></i></a>
                                        <button class="delete-lending-btn btn py-0 text-danger" onclick="deleteLending(event, ${
                                            data.id
                                        })" title="${this.trans(
                            "main.delete_lending"
                        )}"><i class="fas fa-ban"></i></button>
                                    </div>
                                </td>
                            </tr>
                    `;
                        tableData.innerHTML = newData + tableData.innerHTML;
                    }

                    this.values.person = "";
                    this.errors.person = "";
                    this.values.amount = "";
                    this.errors.amount = "";
                    this.values.description = "";
                    this.errors.description = "";
                    this.values.created_at = "";
                    this.errors.created_at = "";

                    toastr.success(
                        this.trans("messages.body.due_added_successfully"),
                        this.trans("messages.title.success")
                    );
                })
                .catch(err => {
                    if (err.response.status == 400) {
                        let errors = err.response.data.errors;
                        if ("person" in errors) {
                            this.errors.person = errors.person[0];
                        }
                        if ("amount" in errors) {
                            this.errors.amount = errors.amount[0];
                        }
                        if ("description" in errors) {
                            this.errors.description = errors.description[0];
                        }
                        if ("created_at" in errors) {
                            this.errors.created_at = errors.created_at[0];
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
