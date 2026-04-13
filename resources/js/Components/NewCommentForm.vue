<script setup>

import BButton from "@/Components/Bootstrap/BButton.vue";
import BTextarea from "@/Components/Bootstrap/BTextarea.vue";
</script>

<template>
    <form>
        <h4>New comment</h4>
        <div class="mb-3">
            <BTextarea
                id="text"
                placeholder="Comment"
                height="150px"
            />
        </div>
        <BButton
            variant="primary"
            text="Add comment"
        />
    </form>
</template>

<style scoped>

</style>
