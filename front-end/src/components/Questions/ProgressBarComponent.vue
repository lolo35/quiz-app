<template>
    <div class="relative h-3 bg-gray-300 w-full rounded">
        <div class="absolute top-0 bottom-0 left-0 right-0">
            <div class="h-full bg-green-500 transition-all duration-100" :style="`width: ${progressbarWidth}%`"></div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, PropType, computed } from 'vue';

export default defineComponent({
    props: {
        totalQuestions: {
            type: Number as PropType<number>,
            required: true,
        },
        currentQuestion: {
            type: Number as PropType<number>,
            required: true,
        }
    },
    setup(props) {
        const progressbarWidth = computed(() => {
            if(props.currentQuestion === 0) {
                return 0;
            }
            return (props.currentQuestion / props.totalQuestions) * 100;
        });

        return {
            progressbarWidth,
        }
    }
});
</script>