<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import UserLayout from '@root/Layouts/User/UserLayout.vue'
import CategorySelector from '@sociallink/Pages/User/Setup/Partials/CategorySelector.vue'
import PlatformSelector from '@sociallink/Pages/User/Setup/Partials/PlatformSelector.vue'
import TemplateSelector from '@sociallink/Pages/User/Setup/Partials/TemplateSelector.vue'
import StepIndicator from '@sociallink/Pages/User/Setup/Partials/StepIndicator.vue'
import SpinnerBtn from '@root/Components/SpinnerBtn.vue'
import InputFieldError from '@root/Components/InputFieldError.vue'

defineOptions({ layout: UserLayout })
defineProps(['categories', 'profileTemplates'])

const currentStep = ref(1)
const selectedCategory = ref(null)
const selectedPlatforms = ref([])

const form = useForm({
  username: '',
  bio: '',
  category_id: null,
  links: [],
  current_step: 1,
  template: ''
})

const steps = [
  { number: 1, title: 'Choose a username' },
  { number: 2, title: 'Select Profession' },
  { number: 3, title: 'Choose Platforms' },
  { number: 4, title: 'Add Links' },
  { number: 5, title: 'Select Template' }
]

const selectCategory = (category) => {
  selectedCategory.value = selectedCategory.value === category ? null : category
  selectedPlatforms.value = selectedPlatforms.value.map((platform) => ({
    ...platform,
    url: ''
  }))
}

const togglePlatform = (platform) => {
  const index = selectedPlatforms.value.findIndex((p) => p.id === platform.id)
  if (index === -1) {
    selectedPlatforms.value.push({ ...platform, url: '' })
  } else {
    selectedPlatforms.value.splice(index, 1)
  }
}

const proceedToNextStep = () => {
  form.current_step = currentStep.value

  const submitForm = (data, onSuccessCallback) => {
    form
      .transform(() => ({ ...data, current_step: currentStep.value }))
      .post(route('user.sociallink.setup.store'), {
        preserveState: true,
        onSuccess: onSuccessCallback
      })
  }

  // steps
  switch (currentStep.value) {
    case 1:
      submitForm({ username: form.username, bio: form.bio }, () => currentStep.value++)
      break

    case 2:
      submitForm({ category_id: selectedCategory.value?.id }, () => currentStep.value++)
      break

    case 3:
      submitForm(
        { platforms: selectedPlatforms.value.map((platform) => platform.id) },
        () => currentStep.value++
      )
      break
    case 4:
      form.links = selectedPlatforms.value.filter((platform) => platform.url?.trim())

      submitForm({ links: form.links }, () => currentStep.value++)
      break
    case 5:
      form
        .transform(() => ({
          ...form,
          current_step: currentStep.value,
          category_id: selectedCategory.value?.id
        }))
        .post(route('user.sociallink.setup.store'), {
          onSuccess: () => {
            currentStep.value = 1
            selectedCategory.value = null
            selectedPlatforms.value = []
          }
        })
      break

    default:
      console.warn('Invalid step.')
      break
  }
}

const stepValidation = {
  1: () => form.username?.trim().length > 0,
  2: () => selectedCategory.value !== null,
  3: () => selectedPlatforms.value.length > 0,
  4: () => selectedPlatforms.value.some((platform) => platform.url?.trim()),
  5: () => form.template?.trim().length > 0
}

const canContinue = computed(() => stepValidation[currentStep.value]())
</script>

<template>
  <main class="container p-4 sm:p-6">
    <PageHeader title="Social Link Setup" />
    <StepIndicator :steps="steps" :currentStep="currentStep" />

    <div class="relative mx-auto max-w-4xl space-y-6">
      <TransitionGroup name="fade" mode="out-in" tag="div">
        <!-- Step 1 -->
        <div v-if="currentStep === 1" key="username" class="absolute w-full space-y-4">
          <div class="card card-body">
            <div class="">
              <label class="label label-required mb-1">{{ trans('Username') }}</label>
              <input type="text" v-model="form.username" class="input" placeholder="Username" />
              <InputFieldError :message="form.errors.username" class="text-sm" />
            </div>

            <div class="mt-4">
              <label class="label mb-1">{{ trans('Bio') }}</label>
              <textarea v-model="form.bio" class="textarea" placeholder="Bio"></textarea>
              <InputFieldError :message="form.errors.bio" class="text-sm" />
            </div>
          </div>

          <div class="flex justify-end">
            <button
              @click="proceedToNextStep"
              :disabled="!canContinue"
              class="btn w-full"
              :class="canContinue ? 'btn-primary' : 'btn-dark'"
            >
              {{ trans('Continue') }}
            </button>
          </div>
        </div>

        <!-- Step 2 -->
        <div v-if="currentStep === 2" key="category" class="absolute w-full space-y-4">
          <CategorySelector
            :selectedCategory="selectedCategory"
            @select="selectCategory"
            :categories="categories"
          />
          <InputFieldError :message="form.errors.category_id" class="text-sm" />
          <div class="flex justify-between">
            <button @click="currentStep--" class="btn btn-outline-danger">
              {{ trans('Back') }}
            </button>
            <button
              @click="proceedToNextStep"
              :disabled="!canContinue"
              class="btn"
              :class="canContinue ? 'btn-primary' : 'btn-dark'"
            >
              {{ trans('Continue') }}
            </button>
          </div>
        </div>

        <!-- Step 3 -->
        <div v-if="currentStep === 3" key="platforms" class="absolute w-full space-y-4">
          <PlatformSelector
            :selectedCategory="selectedCategory"
            :selectedPlatforms="selectedPlatforms"
            @select="togglePlatform"
          />
          <InputFieldError :message="form.errors.platforms" class="text-sm" />
          <div class="flex justify-between">
            <button @click="currentStep--" class="btn btn-outline-danger">Back</button>
            <button
              @click="proceedToNextStep"
              :disabled="!canContinue"
              class="btn"
              :class="canContinue ? 'btn-primary' : 'btn-dark'"
            >
              {{ trans('Continue') }}
            </button>
          </div>
        </div>
        <!-- Step 4 -->
        <div v-if="currentStep === 4" key="links" class="absolute w-full space-y-4">
          <div class="card space-y-4 p-6">
            <div
              v-for="platform in selectedPlatforms"
              :key="platform.id"
              class="flex items-center gap-2"
            >
              <div class="rounded-md border border-gray-700 p-1.5">
                <Icon :icon="platform.icon" class="h-6 w-6" />
              </div>
              <input
                :id="platform.id"
                v-model="platform.url"
                :placeholder="`${platform.name} url`"
                class="input flex-1"
                type="url"
              />
              <InputFieldError :message="form.errors[`links.${platform.id}.url`]" class="text-sm" />
            </div>
          </div>

          <div class="flex justify-between">
            <button @click="currentStep--" class="btn btn-outline-danger">
              {{ trans('Back') }}
            </button>
            <button
              @click="proceedToNextStep"
              :disabled="!canContinue"
              class="btn"
              :class="canContinue ? 'btn-primary' : 'btn-dark'"
            >
              {{ trans('Continue') }}
            </button>
          </div>
        </div>
        <!-- Step 5 -->
        <div v-if="currentStep === 5" key="links" class="absolute w-full space-y-4">
          <TemplateSelector v-model="form.template" :profileTemplates="profileTemplates" />
          <div class="flex justify-between">
            <button @click="currentStep--" class="btn btn-outline-danger">
              {{ trans('Back') }}
            </button>
            <SpinnerBtn
              @click.prevent="proceedToNextStep"
              class="btn flex items-center"
              :classes="canContinue ? 'btn-primary' : 'btn-dark'"
              :processing="form.processing"
              :disabled="!canContinue"
              :btn-text="trans('Submit')"
            />
          </div>
        </div>
      </TransitionGroup>
    </div>
  </main>
</template>
