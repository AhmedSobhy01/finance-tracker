<template>
    <div class="form-body mb-4">
        <form id="addCashForm">
            <h4 class="mb-1">
                <i class="fas fa-plus mr-1"></i>
                {{ trans("main.add_cash") }}
            </h4>
            <hr />
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row align-items-center">
                        <label class="col-md-3 mb-0 text-center" for="amount"
                            >{{ trans("main.amount") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-9">
                            <div class="form-group mb-0">
                                <select
                                    class="form-control"
                                    tabindex="-1"
                                    aria-hidden="true"
                                    v-model="values.amount"
                                >
                                    <option
                                        v-for="moenyPaper in moenyPapers"
                                        :key="moenyPaper.id"
                                        :value="moenyPaper.amount"
                                        >{{
                                            moenyPaper.amount + " " + currency
                                        }}
                                    </option>
                                </select>
                            </div>
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
                            for="serial_number"
                            >{{ trans("main.serial_number") }}:
                            <span class="text-danger">*</span></label
                        >
                        <div class="col-md-9">
                            <input
                                type="number"
                                min="1000000"
                                max="9999999"
                                step="1"
                                id="serial_number"
                                class="form-control"
                                name="serial_number"
                                v-model="values.serialNumber"
                            />
                            <span
                                class="invalid-feedback d-block mt-2"
                                role="alert"
                            >
                                <strong>{{ errors.serialNumber }}</strong>
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
                    <button
                        class="btn btn-primary btn-block"
                        id="addCashBtn"
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
    props: ["addCashUrl", "changeTable", "currency", "moenyPapers"],

    data() {
        return {
            values: {
                amount: this.moenyPapers[0].amount,
                serialNumber: "",
                description: ""
            },
            errors: {
                amount: "",
                serialNumber: "",
                description: ""
            },
            loading: false
        };
    },

    methods: {
        validateForm() {
            this.errors.amount = "";
            this.errors.serialNumber = "";
            this.errors.description = "";

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

            if (this.values.serialNumber == "") {
                this.errors.serialNumber = this.trans(
                    "custom_validation.serial_number.required"
                );
            } else if (isNaN(this.values.serialNumber)) {
                this.errors.serialNumber = this.trans(
                    "custom_validation.serial_number.numeric"
                );
            } else if (
                this.values.serialNumber < 1000000 ||
                this.values.serialNumber > 9999999
            ) {
                this.errors.serialNumber = this.trans(
                    "custom_validation.serial_number.invalid"
                );
            }

            if (this.values.description.length > 100) {
                this.errors.description = this.trans(
                    "custom_validation.description.max:100"
                );
            }

            return this.errors.amount == "" &&
                this.errors.serialNumber == "" &&
                this.errors.description == ""
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
                .post(this.addCashUrl, {
                    serial_number: this.values.serialNumber,
                    amount: this.values.amount,
                    description: this.values.description
                })
                .then(res => res.data)
                .then(data => {
                    if (this.changeTable) {
                        const tableData = document.querySelector(
                            "#cashesTable tbody"
                        );
                        data = data.data;
                        let newData = `
                            <tr>
                                <th scope="row">${data.process_serial}</th>
                                <td>${data.amount}</td>
                                <td>${data.serial_number}</td>
                                <td class="word-break-all">${data.description}</td>
                                <td>${data.created_at}</td>
                                <td>
                                    <a href="${data.view_url}" class="text-secondary"><i class="fas fa-eye"></i></a>
                                    <button class="delete-transaction-btn btn py-0 text-danger" onclick="deleteCash(event, ${data.id})"><i class="fas fa-ban"></i></button>
                                </td>
                            </tr>
                    `;
                        tableData.innerHTML = newData + tableData.innerHTML;
                    }

                    this.values.amount = "0.5";
                    this.errors.amount = "";
                    this.values.serialNumber = "";
                    this.errors.serialNumber = "";
                    this.values.description = "";
                    this.errors.description = "";

                    toastr.success(
                        this.trans("messages.body.cash_added_successfully"),
                        this.trans("messages.title.success")
                    );
                })
                .catch(err => {
                    if (err.response.status == 400) {
                        let errors = err.response.data.errors;
                        if ("amount" in errors) {
                            this.errors.amount = errors.amount[0];
                        }
                        if ("serialNumber" in errors) {
                            this.errors.serialNumber = errors.serialNumber[0];
                        }
                        if ("description" in errors) {
                            this.errors.description = errors.description[0];
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
