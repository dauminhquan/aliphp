<template>
    <select name="" id="">
        <option v-if="hasNull == true" :value="null"></option>
        <option v-for="option in options" :key="option" :value="option">{{trans.find(option)}}</option>
    </select>
</template>
<script>
    import trans from './../../home/config'
   export default {
       props: ['options', 'value','hasNull'],
       mounted: function () {
           var vm = this
           $(this.$el)
           // init select2
               .select2()
               .val(this.value)
               .trigger('change')
               // emit event on change.
               .on('change', function () {
                   vm.$emit('input', this.value)
               })
       },
       watch: {
           value: function (value) {
               // update value
               $(this.$el)
                   .val(value)
                   .trigger('change')
           },
           options: function (options) {
               // update options
               $(this.$el).empty().select2()
           }
       },
       destroyed: function () {
           $(this.$el).off().select2('destroy')
       },
       data(){
           return {
               trans: trans
           }
       }
   }
</script>
