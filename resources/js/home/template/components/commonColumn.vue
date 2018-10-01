<template>
    <div class="row">
        <div class="col-lg-4">
            <select class="form-control">
                <option ></option>
                <option value="" v-for="column in templateColumns" @key="column.id"  :value="column.id">{{column.name}}</option>
            </select>
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control" placeholder="Nhập giá trị" @change="setCommonValue(c)">
        </div>
    </div>
</template>
<script>
    export default {

    }
</script>
