<script setup>
defineProps({
  steps: { number: '', title: '' },
  currentStep: Number
})
</script>

<template>
  <div class="mb-12 flex items-center justify-center">
    <div class="flex w-full max-w-2xl items-center">
      <template v-for="(step, index) in steps" :key="step.number">
        <!-- Step circle -->
        <div
          :class="[
            'relative flex h-7 w-7 items-center justify-center rounded-full text-sm font-medium transition-colors',
            currentStep >= step.number ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-500'
          ]"
        >
          {{ step.number }}

          <span
            class="absolute -bottom-6 left-1/2 -translate-x-1/2 whitespace-nowrap text-xs font-medium"
            :class="currentStep >= step.number ? 'text-primary-600' : 'text-gray-500'"
          >
            {{ step.title }}
          </span>
        </div>
        <!-- line -->
        <div
          v-if="index < steps.length - 1"
          class="mx-2 h-0.5 flex-1"
          :class="currentStep > step.number ? 'bg-primary-600' : 'bg-gray-200'"
        ></div>
      </template>
    </div>
  </div>
</template>
