<template>
    <div>
        <label
            v-if="label"
            class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
            for="grid-first-name"
        >
            {{ label }}
        </label>
        <input
            class="block w-full text-gray-700 border rounded py-1 px-2 mb-3 focus:outline-none focus:bg-white"
            id="grid-first-name"
            type="text"
            :placeholder="placeholder"
            v-model="inputValue"
        >
    </div>
</template>

<script>
    export default {
        props: {
            label: {
                type: String,
                default: null
            },
            placeholder: {
                type: String,
                default: null
            },
            value: {
                type: [String, Number],
                default: null
            }
        },
        data(){
            return {
                inputValue: this.value
            }
        },
        watch:{
            inputValue(){
                this.$emit('input', this.inputValue)
            },
            value(){
                this.inputValue = this.value
            }
        }
    }
</script>
