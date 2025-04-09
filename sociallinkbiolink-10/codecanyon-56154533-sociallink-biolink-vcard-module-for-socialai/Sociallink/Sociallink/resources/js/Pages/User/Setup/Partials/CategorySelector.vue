<script setup>
const props = defineProps({
  selectedCategory: Object,
  categories: Array
})
const emit = defineEmits(['select'])
</script>

<template>
  <div class="card space-y-4 p-6">
    <div class="grid grid-cols-1 gap-4">
      <button
        v-for="category in categories"
        :key="category.id"
        @click="emit('select', category)"
        :class="[
          'flex items-center space-x-3 rounded-lg border p-2 transition-all duration-300',
          selectedCategory?.id === category.id
            ? 'border-dark-600 bg-primary-500'
            : 'border-gray-500 hover:border-primary-400'
        ]"
      >
        <img :src="category.icon" class="h-10 w-10" />
        <span class="font-medium">{{ category.title }}</span>
      </button>
    </div>
  </div>
</template>
