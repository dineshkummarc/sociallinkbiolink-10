<script setup>
const props = defineProps({
  selectedCategory: Object,
  selectedPlatforms: Array
})

const emit = defineEmits(['select'])

const platforms = [
  {
    id: 'instagram',
    name: 'Instagram',
    icon: 'bxl-instagram'
  },
  {
    id: 'twitter',
    name: 'Twitter',
    icon: 'bxl-twitter'
  },
  {
    id: 'linkedin',
    name: 'LinkedIn',
    icon: 'bxl-linkedin-square'
  },
  {
    id: 'github',
    name: 'GitHub',
    icon: 'bxl-github'
  },
  {
    id: 'facebook',
    name: 'Facebook',
    icon: 'bxl-facebook-circle'
  },
  {
    id: 'telegram',
    name: 'Telegram',
    icon: 'bxl-telegram'
  },
  {
    id: 'discord',
    name: 'Discord',
    icon: 'bxl-discord'
  }
]

const findSelectedPlatform = (platform) => {
  return props.selectedPlatforms.find((p) => p.id === platform.id)
}
</script>

<template>
  <div class="card space-y-4 p-6">
    <div class="flex flex-wrap gap-3">
      <button
        v-for="platform in platforms"
        :key="platform.id"
        @click="emit('select', platform)"
        :class="[
          'flex items-center gap-1 rounded-lg border px-4 py-2 text-sm transition-all duration-300',
          findSelectedPlatform(platform)
            ? 'border-primary-900 bg-primary-500'
            : 'border-gray-500 hover:border-primary-400'
        ]"
      >
        <Icon :icon="platform.icon" class="h-6 w-6" />
        <span class="font-medium">{{ platform.name }}</span>
      </button>
    </div>
  </div>
</template>
