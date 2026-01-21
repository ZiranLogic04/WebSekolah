<template>
    <div v-if="links.length > 3">
        <div class="d-flex flex-wrap -mb-1">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" class="mr-1 mb-1 px-3 py-2 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label" />
                <Link v-else
                    class="mr-1 mb-1 px-3 py-2 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
                    :class="{ 'bg-primary text-white': link.active, 'bg-white': !link.active }" :href="link.url"
                    v-html="link.label" />
            </template>
        </div>
    </div>
</template>
  
<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        links: Array,
    },
}
</script>

<style scoped>
.mr-1 { margin-right: 0.25rem; }
.mb-1 { margin-bottom: 0.25rem; }
.px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.text-sm { font-size: 0.875rem; line-height: 1.25rem; }
.border { border-width: 1px; }
.rounded { border-radius: 0.25rem; }
.bg-white { background-color: #ffffff; }
.bg-primary { background-color: #3d5ee1; border-color: #3d5ee1; }
.text-white { color: #ffffff; }
.hover\:bg-white:hover { background-color: #ffffff; }
a { text-decoration: none; color: inherit; display: inline-block; }
a:hover { text-decoration: none; background-color: #f8f9fa; }
</style>
