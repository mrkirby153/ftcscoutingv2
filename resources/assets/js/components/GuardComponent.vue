<template>
    <div>
        <slot v-if="passed"></slot>
        <slot name="no-access" v-if="!passed"></slot>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {

        data() {
            return {
                checking: false,
                passed: false
            }
        },

        props: {
            model: {
                type: String,
                required: true
            },
            property: {
                type: String,
                required: false,
                default: 'id'
            },
            check: {
                type: String,
                required: true
            },
            value: {
                type: String,
                required: false
            },
            additional: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            this.performCheck();
        },

        watch: {
            '$route'(to, from) {
                this.performCheck(); // Re-check if the route changes
            }
        },

        methods: {
            performCheck() {
                if (this.checking)
                    return; // Prevent us firing off two checks
                this.checking = true;
                let model = this.model;
                if (!model.startsWith('\\')) {
                    model = "\\App\\Models\\" + model;
                }
                axios.post(route('auth.check'), {
                    model: model,
                    property: this.property,
                    check: this.check,
                    value: this.value,
                    additional: this.additional
                }).then(resp => {
                    this.passed = resp.data;
                    this.checking = false;
                }).catch(err => {
                    this.checking = false;
                    throw err;
                })
            }
        }
    }
</scrIpt>