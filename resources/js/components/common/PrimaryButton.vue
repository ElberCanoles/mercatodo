<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type" class="w-100 btn btn-lg btn-primary">
        <slot />
    </button>
</template>
