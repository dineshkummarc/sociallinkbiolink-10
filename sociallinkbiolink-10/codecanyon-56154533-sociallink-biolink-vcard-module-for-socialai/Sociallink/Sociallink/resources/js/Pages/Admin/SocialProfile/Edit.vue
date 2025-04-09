<script setup>
import AdminLayout from '@root/Layouts/Admin/AdminLayout.vue'
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import { useForm } from '@inertiajs/vue3'
import trans from '@root/Composables/transComposable'
import SpinnerBtn from '@root/Components/Admin/SpinnerBtn.vue'
import InputFieldError from '@root/Components/InputFieldError.vue'
import { onMounted } from 'vue'

defineOptions({ layout: AdminLayout })

const props = defineProps({
  profile: {
    type: Object,
    required: true
  },
  categories: {
    type: Array,
    required: true
  },
  templates: {
    type: Array,
    required: true
  }
})

const editForm = useForm({
  username: props.profile.username,
  name: props.profile.name,
  email: props.profile.email,
  phone_number: props.profile.phone_number,
  bio: props.profile.bio,
  category_id: props.profile.category_id,
  avatar: null,
  _method: 'PUT'
})

const submit = () => {
  editForm.post(route('admin.sociallink.profile.update', props.profile.id), {
    preserveScroll: true,
    onSuccess: () => {
      editForm.reset()
    }
  })
}
</script>

<template>
  <main class="container flex-grow p-4 sm:p-6">
    <PageHeader />

    <form method="POST" @submit.prevent="submit" class="card card-body mx-auto max-w-3xl">
      <h5 class="mb-4">{{ trans('Edit Social Profile') }}</h5>

      <div class="space-y-3">
        <!-- Avatar Upload -->
        <div>
          <label class="label mb-1">{{ trans('Avatar') }}</label>
          <input
            type="file"
            @change="(e) => (editForm.avatar = e.target.files[0])"
            class="input"
            accept="image/*"
          />
          <InputFieldError :message="editForm.errors.avatar" />
        </div>

        <!-- Basic Information -->
        <div>
          <label class="label mb-1">{{ trans('Username') }}</label>
          <input type="text" v-model="editForm.username" class="input" required />
          <InputFieldError :message="editForm.errors.username" />
        </div>

        <div>
          <label class="label mb-1">{{ trans('Name') }}</label>
          <input type="text" v-model="editForm.name" class="input" />
          <InputFieldError :message="editForm.errors.name" />
        </div>

        <div>
          <label class="label mb-1">{{ trans('Email') }}</label>
          <input type="email" v-model="editForm.email" class="input" />
          <InputFieldError :message="editForm.errors.email" />
        </div>

        <div>
          <label class="label mb-1">{{ trans('Phone Number') }}</label>
          <input type="text" v-model="editForm.phone_number" class="input" />
          <InputFieldError :message="editForm.errors.phone_number" />
        </div>

        <div>
          <label class="label mb-1">{{ trans('Bio') }}</label>
          <textarea v-model="editForm.bio" class="textarea"></textarea>
          <InputFieldError :message="editForm.errors.bio" />
        </div>

        <div>
          <label class="label mb-1">{{ trans('Profession') }}</label>
          <select v-model="editForm.category_id" class="select" required>
            <option value="">{{ trans('Select Category') }}</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.title }}
            </option>
          </select>
          <InputFieldError :message="editForm.errors.category_id" />
        </div>
      </div>

      <div class="mt-3 flex justify-end">
        <SpinnerBtn
          classes="btn btn-primary"
          :processing="editForm.processing"
          :btn-text="trans('Save Changes')"
        />
      </div>
    </form>
  </main>
</template>
