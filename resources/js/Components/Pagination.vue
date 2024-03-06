<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    links: {
      type: Array
    },
})
</script>

<template>
  <div v-if="links.length > 3" class="flex justify-center mt-4 space-x-4">
    <Link
        v-for="(link, k) in links"
        :key="k"
        class="px-4 py-3 text-sm leading-4 rounded focus:text-indigo-500 hover:shadow"
        :class="{'bg-indigo-700 text-white hover:opacity-90': link.active}"
        :href="link.url ?? '#'"
        v-html="link.label"
    />
  </div>
</template>
