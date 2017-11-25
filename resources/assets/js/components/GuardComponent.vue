<template>
    <div>
        <slot v-if="passed"></slot>
        <slot name="no-access" v-if="!passed"></slot>
    </div>
</template>

<script>
    import axios from 'axios';
    import {SET_CACHED_GUARD_CHECK} from "../vuex/mutationTypes";

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
                // Check if the cached stuff is valid
                /// state.guardCache[model][check][property][val][extra]
                let propertyNest = [model, this.check, this.property, this.value, this.additional];
                let cachedData = this.getNestedProperty(this.$store.state.guardCache, propertyNest);
                if(cachedData !== undefined){
                    let setOn = cachedData.time;
                    let result = cachedData.result;
                    let cacheTime = 60 * 1000; // 60 seconds
                    if(setOn + cacheTime < new Date().getTime()){
                        // Cache has expired
                    } else {
                        // Retrieve from the cache
                        this.passed = result;
                        return;
                    }
                }
                axios.post(route('auth.check'), {
                    model: model,
                    property: this.property,
                    check: this.check,
                    value: this.value,
                    additional: this.additional
                }).then(resp => {
                    this.passed = resp.data;
                    this.$store.commit(SET_CACHED_GUARD_CHECK, {
                        model: model,
                        check: this.check,
                        value: this.value,
                        property: this.property,
                        extra: this.additional,
                        result: resp.data
                    });
                    this.checking = false;
                }).catch(err => {
                    this.checking = false;
                    throw err;
                })
            },

            getNestedProperty(obj, property){
                let last = obj;
                for(let i = 0; i < property.length; i++){
                    if(last[property[i]] === undefined)
                        return undefined;
                    last = last[property[i]];
                }
                return last;
            }
        }
    }
</scrIpt>