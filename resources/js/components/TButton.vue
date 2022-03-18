<template>
    <button class="text-white font-bold py-1 px-3 rounded" :class="className" @click="onClick">
        <slot></slot>
    </button>
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
    computed:{
      className(){
          switch(this.type) {
              case 'danger':
                    return {'bg-red-500 hover:bg-red-700': true}
              case 'success':
                  return {'bg-green-500 hover:bg-green-700': true}
              default:
                  return {'bg-blue-500 hover:bg-blue-700': true}
          }
      }
    },
    methods:{
        onClick(){
            this.$emit('click')
        }
    }
}
</script>
