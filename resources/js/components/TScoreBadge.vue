<template>
   <span
       class="text-sm font-semibold inline-flex items-center p-1.5 px-2 mr-1 rounded-full dark:text-blue-800"
       :class="className"
   >
       <slot></slot>
   </span>
</template>

<script>
export default {
    name: "TButton",
    props: {
        type: {
            type: String,
            required: false,
            default: null
        }
    },
    computed: {
        className() {
            switch (this.type) {
                case 'gray':
                    return { 'bg-gray-400': true }
                case 'success':
                    return { 'bg-green-500': true }
                case 'danger':
                    return { 'bg-red-500': true }
                default:
                    return { 'bg-gray-100': true }
            }
        }
    },
}
</script>
