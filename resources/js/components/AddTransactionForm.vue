<template>
    <div class="form-body mb-4">
        <form id="addTransactionForm">
            <h4 class="mb-1">
                <i class="fas fa-plus mr-1"></i>
                {{ trans("main.add_transaction") }}
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
                        <label class="col-md-3 mb-0 text-center" for="type">
                            {{ trans("main.type") }}:
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-9">
                            <div class="d-flex">
                                <div class="input-container">
                                    <input
                                        type="radio"
                                        id="income"
                                        class="cursor-pointer"
                                        name="type"
                                        value="1"
                                        checked
                                        v-model="values.type"
                                    />
                                    <label for="income">{{
                                        trans("main.income")
                                    }}</label>
                                </div>
                                <div class="input-container">
                                    <input
                                        type="radio"
                                        id="expense"
                                        class="cursor-pointer"
                                        name="type"
                                        value="0"
                                        v-model="values.type"
                                    />
                                    <label for="expense">{{
                                        trans("main.expense")
                                    }}</label>
                                </div>
                            </div>
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
    props: ["addTransactionUrl", "changeTable", "isMorePages"],

    data() {
        return {
            values: {
                amount: "",
                type: "1",
                description: ""
            },
            errors: {
                amount: "",
                description: ""
            },
            loading: false
        };
    },

    methods: {
        validateForm() {
            this.errors.amount = "";
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

            if (this.values.description.length > 100) {
                this.errors.description = this.trans(
                    "custom_validation.description.max:100"
                );
            }

            return this.errors.amount == "" && this.errors.description == ""
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
                .post(this.addTransactionUrl, {
                    type: this.values.type,
                    amount: this.values.amount,
                    description: this.values.description
                })
                .then(res => res.data)
                .then(data => {
                    if (this.changeTable) {
                        const tableData = document.querySelector(
                            "#transactionsTable tbody"
                        );
                        data = data.data;
                        if (this.isMorePages) tableData.lastChild.remove();
                        let newData = `
                            <tr class="${
                                data.type_raw == 1 ? "bg-success" : "bg-danger"
                            }">
                                <th scope="row">${data.process_serial}</th>
                                <td>${data.type}</td>
                                <td>${data.amount}</td>
                                <td class="word-break-all">${
                                    data.description
                                }</td>
                                <td>${data.created_at}</td>
                                <td>
                                    <a href="${
                                        data.view_url
                                    }" class="text-white"><i class="fas fa-eye"></i></a>
                                    <button class="delete-transaction-btn btn py-0 text-white" onclick="deleteTransaction(event, ${
                                        data.id
                                    })"><i class="fas fa-ban"></i></button>
                                </td>
                            </tr>
                    `;
                        tableData.innerHTML = newData + tableData.innerHTML;
                    }

                    this.values.amount = "";
                    this.errors.amount = "";
                    this.values.description = "";
                    this.errors.description = "";

                    toastr.success(
                        this.trans(
                            "messages.body.transaction_added_successfully"
                        ),
                        this.trans("messages.title.success")
                    );
                })
                .catch(err => {
                    if (err.response.status == 400) {
                        let errors = err.response.data.errors;
                        if ("amount" in errors) {
                            this.errors.amount = errors.amount[0];
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

<style scoped>
.input-container input[type="radio"] {
    opacity: 0;
    width: 100%;
    height: 42px;
    background-color: blue;
    position: relative;
    z-index: 1;
}

.input-container {
    height: 42px;
    width: 100%;
    line-height: 42px;
    text-align: center;
    position: relative;
}

.input-container:first-child label {
    border-radius: 5px 0 0 5px;
}

.input-container:last-child label {
    border-radius: 0 5px 5px 0;
    border-right: 2px solid #ccc;
}

.input-container label {
    width: 100%;
    height: 100%;
    position: absolute;
    border: 2px solid #ccc;
    border-right: inherit;
    top: 0;
    left: 0;
    font-family: arial;
    color: #737373;
}

.input-container:nth-child(1) input:checked + label {
    background-color: #388e3c;
    top: 0;
    left: 0;
    border: 2px solid #388e3c !important;
    z-index: 2;
    color: white;
}

.input-container:nth-child(2) input:checked + label {
    background-color: #c62828;
    top: 0;
    left: 0;
    border: 2px solid #c62828 !important;
    z-index: 2;
    color: white;
}
</style>
