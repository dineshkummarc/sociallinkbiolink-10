<script setup>
const model = defineModel()
const props = defineProps(['profileTemplates'])
const setModelValue = (value) => {
  model.value = value
}
</script>
<template>
  <div class="grid grid-cols-3 gap-6">
    <template v-for="profile in profileTemplates" :key="profile.id">
      <button
        type="button"
        @click="setModelValue(profile.template)"
        class="overflow-hidden rounded-lg border-2 border-transparent"
        :class="{
          'border-primary-500 dark:border-primary-500': model == profile.template
        }"
      >
        <img :src="profile.preview" alt="preview" class="h-full w-full object-cover" />
      </button>
    </template>
  </div>
</template>
