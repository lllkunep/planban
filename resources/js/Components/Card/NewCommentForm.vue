<script setup>

import Button from "@/Components/Common/Button.vue";
import Textarea from "@/Components/Form/Textarea.vue";
</script>

<template>
    <form>
        <h4>New comment</h4>
        <div class="mb-3">
            <Textarea
                id="text"
                placeholder="Comment"
                height="150px"
            />
        </div>
        <Button variant="primary">
            Add comment
        </Button>
    </form>
</template>

<style scoped>

</style>
