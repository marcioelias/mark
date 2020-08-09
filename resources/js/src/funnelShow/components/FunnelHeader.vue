<template>
    <div class="row d-flex justify-content-between">
        <div id="product" class="ml-1 flex-grow-1">
            <label>Produto</label>
            <div class="form-control">{{ productName }}</div>
        </div>
        <div id="tag" class="ml-2 flex-grow-1">
            <label>Tag</label>
            <div class="form-control">{{ tagName }}</div>
        </div>
        <div id="active" class="ml-2 mr-1">
            <label>Ativo</label>
            <div class="form-control pl-1 pr-1">
                <i class="fas fa-check text-success" v-if="active"></i>
                <i class="fas fa-times text-danger" v-else></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        productName: {
            type: String,
            required: true
        },
        tagName: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
            required: true
        }
    }
}
</script>
