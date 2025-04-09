<script setup>
import UserLayout from '@root/Layouts/User/UserLayout.vue'
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import SpinnerBtn from '@root/Components/SpinnerBtn.vue'
import Modal from '@root/Components/Modal.vue'
import { VueDraggable } from 'vue-draggable-plus'
import { router, useForm } from '@inertiajs/vue3'
import { modal } from '@root/Composables/actionModalComposable'
import { useModalStore } from '@root/Store/modalStore'
import { ref, nextTick, computed } from 'vue'
import sharedComposable from '@root/Composables/sharedComposable'
import InputFieldError from '@root/Components/InputFieldError.vue'
defineOptions({ layout: UserLayout })
const props = defineProps({
  socialProfile: Object,
  socialPlatforms: {
    type: Object,
    default: () => []
  },
  profileTemplates: {
    type: Object,
    default: () => []
  }
})
const { textExcerpt, deleteRow, copyToClipboard, uiAvatar, trim } = sharedComposable()
const modalStore = useModalStore()
const form = useForm({
  avatar:
    props.socialProfile.avatar || uiAvatar(props.socialProfile.name, props.socialProfile.avatar),

  cover: props.socialProfile.cover,
  username: props.socialProfile.username,
  bio: props.socialProfile.bio || '',
  name: props.socialProfile?.name || '',
  social_links: props.socialProfile.social_links,
  template: props.socialProfile.template,
  email: props.socialProfile.email || '',
  phone_number: props.socialProfile.phone_number || '',
  customization: props.socialProfile?.customization,
  _method: 'PATCH'
})

const refreshIframe = () => {
  iframe.value.src = route('user.sociallink.profile-link.show', props.socialProfile.username)
}
const initialSocialLinks = ref(JSON.stringify(props.socialProfile.social_links))
const submit = () => {
  const hasLinksChanged = JSON.stringify(form.social_links) !== initialSocialLinks.value

  if (!hasLinksChanged) return

  submitUpdate()
}
const submitUpdate = () => {
  form.social_links = form.social_links.map((link, index) => {
    return {
      ...link,
      order: index + 1
    }
  })
  form.post(route('user.sociallink.linktree.update', props.socialProfile.uuid), {
    onSuccess: (page) => {
      form.social_links = page.props.socialProfile.social_links
      form.customization = page.props.socialProfile.customization
      initialSocialLinks.value = JSON.stringify(page.props.socialProfile.social_links)
      refreshIframe()
    },
    preserveScroll: true
  })
}
const showImage = (avatar) => {
  if (avatar instanceof File) {
    return URL.createObjectURL(avatar)
  } else {
    return avatar
  }
}

const activeInput = ref(null)
const iframe = ref(null)
const iframeLink = ref(route('user.sociallink.profile-link.show', props.socialProfile.username))
const newPlatformListState = ref(false)
const editTab = ref('profile')
const tabLists = [
  {
    label: 'Profile',
    value: 'profile',
    icon: 'bx:user',
    title: 'Profile Settings'
  },
  {
    label: 'Edit Template',
    value: 'template',
    icon: 'bx:layout',
    title: 'Change Template'
  },
  {
    label: 'Modify Template',
    value: 'modify',
    icon: 'fe:magic',
    title: 'Modify Customization'
  }
]
const newPlatformForm = useForm({
  uuid: props.socialProfile.uuid,
  name: '',
  url: '',
  icon: ''
})

const focusInput = (id, type) => {
  activeInput.value = `${type}-` + id

  nextTick(() => {
    const input = document.getElementById(`input-${type}-` + id)
    input.focus()
  })
}

const availablePlatforms = computed(() => {
  return props.socialPlatforms
})

const selectPlatform = (platform) => {
  newPlatformForm.name = platform.name
  newPlatformForm.icon = platform.icon
  newPlatformForm.url = ''
  newPlatformListState.value = true
}

const submitNewLink = () => {
  newPlatformForm.post(route('user.sociallink.linktree.store'), {
    onSuccess: (page) => {
      newPlatformForm.reset()
      newPlatformListState.value = false
      form.social_links = page.props.socialProfile.social_links
      refreshIframe()
    },
    preserveScroll: true,
    only: ['socialProfile']
  })
}
const wheelRef = ref(null)
const defaultDirection = Math.round(props.socialProfile.customization.direction || 0)
const visualRotation = ref(defaultDirection)
const actualRotation = ref(defaultDirection)
const isDragging = ref(false)
const startAngle = ref(0)

const calculateAngle = (event) => {
  const rect = wheelRef.value.getBoundingClientRect()
  const centerX = rect.left + rect.width / 2
  const centerY = rect.top + rect.height / 2

  const radians = Math.atan2(event.clientY - centerY, event.clientX - centerX)
  let angle = radians * (180 / Math.PI)
  return (angle + 360) % 360
}

const startDrag = (event) => {
  isDragging.value = true
  startAngle.value = calculateAngle(event)
}

const onDrag = (event) => {
  if (!isDragging.value) return

  const currentAngle = calculateAngle(event)
  let angleDiff = currentAngle - startAngle.value

  if (angleDiff > 180) angleDiff -= 360
  if (angleDiff < -180) angleDiff += 360

  visualRotation.value += angleDiff

  actualRotation.value = (actualRotation.value + angleDiff + 360) % 360
  form.customization.direction = actualRotation.value
  startAngle.value = currentAngle
}

const stopDrag = () => {
  isDragging.value = false
}
const onDelete = (link) => {
  modal.initCallback(function () {
    router.delete(route('user.sociallink.linktree.destroy', link.id), {
      onSuccess: (page) => {
        form.social_links = page.props.socialProfile.social_links
        refreshIframe()
      }
    })
  })
}
</script>

<template>
  <Modal
    :state="modalStore.getState('editProfile')"
    bg-color="bg-white dark:bg-dark-800 c-modal-content-small"
    :header-state="true"
    :header-title="
      tabLists.find((tab) => tab.value === editTab)?.title || trans('Profile Settings')
    "
  >
    <div class="styled-scrollbar max-h-[80vh] overflow-y-auto pb-3 pt-4">
      <div class="space-y-1" v-if="editTab === 'profile'">
        <div>
          <label class="label mb-1">{{ trans('Cover Image') }} </label>
          <div class="relative">
            <input
              type="file"
              accept="image/*"
              class="hidden"
              ref="fileInput"
              @input="(e) => (form.cover = e.target.files[0])"
            />
            <div
              v-if="!form.cover"
              class="input flex h-32 cursor-pointer items-center justify-center border-2 border-dashed p-4"
              @click="$refs.fileInput.click()"
            >
              <span class="text-gray-500">{{ trans('Click to upload an image') }}</span>
            </div>
            <div v-else class="relative">
              <img
                :src="showImage(form.cover)"
                class="h-52 w-full rounded-lg border object-cover dark:border-dark-700"
                alt="Preview"
              />
              <button
                @click.prevent="form.cover = null"
                class="absolute right-2 top-2 rounded-full bg-red-500 p-1 text-white"
              >
                <Icon icon="lucide:x" />
              </button>
            </div>
          </div>
          <InputFieldError :message="form.errors.cover" class="text-sm" />
        </div>
        <div>
          <label class="label label-required mb-1">{{ trans('Username') }}</label>
          <input type="text" class="input" v-model="form.username" />

          <InputFieldError :message="form.errors.username" class="text-sm" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Bio') }}</label>
          <textarea class="textarea" v-model="form.bio"></textarea>

          <InputFieldError :message="form.errors.bio" class="text-sm" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Name') }}</label>
          <input type="text" class="input" v-model="form.name" />
          <InputFieldError :message="form.errors.name" class="text-sm" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Email') }}</label>
          <input type="email" class="input" v-model="form.email" />
          <InputFieldError :message="form.errors.email" class="text-sm" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Phone Number') }}</label>
          <input type="tel" class="input" v-model="form.phone_number" />
          <InputFieldError :message="form.errors.phone_number" class="text-sm" />
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3" v-if="editTab === 'template'">
        <template v-for="profileTemplate in profileTemplates" :key="profileTemplate.id">
          <button
            type="button"
            @click="form.template = profileTemplate.template"
            class="overflow-hidden rounded-lg border-2 border-transparent"
            :class="{
              'border-primary-500 dark:border-primary-500':
                form.template == profileTemplate.template
            }"
          >
            <img :src="profileTemplate.preview" alt="preview" class="h-full w-full object-cover" />
          </button>
        </template>

        <InputFieldError :message="form.errors.template" class="text-sm" />
      </div>
      <div v-if="editTab === 'modify'">
        <div>
          <div class="flex items-center justify-between pt-2">
            <div class="flex h-20 flex-col justify-evenly">
              <label class="label">{{ trans('Background Colors') }}</label>
              <div class="flex gap-2">
                <div
                  class="h-10 w-10 overflow-hidden rounded-md"
                  v-for="(background, i) in form.customization.bg_colors"
                  :key="i"
                >
                  <input
                    v-model="form.customization.bg_colors[i]"
                    class="h-24 w-24 -translate-x-5 -translate-y-4 rounded-xl bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0 focus:ring-primary-500 dark:text-slate-200"
                    type="color"
                    name="file"
                  />
                </div>
              </div>
            </div>
            <div class="flex flex-col items-center justify-center">
              <div
                ref="wheelRef"
                class="relative flex h-16 w-16 items-center justify-center rounded-full border-2 dark:border-dark-200"
                @mousedown.prevent="startDrag"
                @mousemove.prevent="onDrag"
                @mouseup.prevent="stopDrag"
                @mouseleave.prevent="stopDrag"
                @touchstart.prevent="(event) => startDrag(event.touches ? event.touches[0] : event)"
                @touchmove.prevent="(event) => onDrag(event.touches ? event.touches[0] : event)"
                @touchend.prevent="stopDrag"
                @touchcancel.prevent="stopDrag"
              >
                <!-- Wheel Pointer -->
                <div
                  class="absolute bottom-[30px] z-50 h-[29px] w-1 origin-bottom rounded-full"
                  :style="{
                    transform: `rotate(${visualRotation}deg)`,
                    transition: isDragging ? 'none' : 'transform 0.1s ease-out'
                  }"
                >
                  <span class="absolute -left-1 -top-px h-2 w-2 rounded-full bg-primary-500"></span>
                </div>

                <!-- Degree -->
                <div class="absolute top-1/2 -z-0 -translate-y-1/2 text-xs">
                  {{ Math.round(actualRotation) }}°
                </div>
              </div>
            </div>
          </div>
          <InputFieldError :message="form.errors['customization.bg_colors']" class="text-sm" />
          <InputFieldError :message="form.errors['customization.direction']" class="text-sm" />
          <!-- Preview Gradient -->
          <div
            class="mt-4 h-24 w-full rounded-md"
            :style="{
              background: `linear-gradient(${visualRotation}deg, ${form.customization.bg_colors?.join(
                ', '
              )})`
            }"
          ></div>
        </div>
        <div class="mt-5 flex flex-wrap gap-3">
          <template v-for="(value, key) in form.customization" :key="key">
            <div
              class="flex flex-1 items-center justify-between rounded-md border border-x-0 border-gray-700 px-1"
              v-if="key !== 'bg_colors' && key !== 'direction'"
            >
              <label class="label whitespace-nowrap capitalize">{{ trim(key) }}</label>
              <div class="flex gap-2">
                <div class="h-10 w-10 overflow-hidden rounded-md">
                  <input
                    v-model="form.customization[key]"
                    class="h-24 w-24 -translate-x-5 -translate-y-4 rounded-xl bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0 focus:ring-primary-500 dark:text-slate-200"
                    type="color"
                    name="file"
                  />
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div class="mt-4 flex justify-end gap-2">
        <button
          type="button"
          v-for="tab in tabLists"
          :key="tab.value"
          class="btn btn-xs"
          :class="editTab === tab.value ? 'btn-primary' : 'btn-outline-primary'"
          @click="editTab = tab.value"
        >
          <Icon :icon="tab.icon" />
          <span>
            {{ trans(tab.label) }}
          </span>
        </button>
      </div>
      <SpinnerBtn
        @click="submitUpdate"
        classes="btn btn-primary w-full mt-4"
        icon=""
        :processing="form.processing"
        :btn-text="trans('Update')"
      />
    </div>
  </Modal>
  <main class="container p-4 sm:p-6">
    <PageHeader />

    <div
      class="mt-10 grid grid-cols-1 place-items-center gap-5 xl:grid-cols-3 xl:place-items-start"
    >
      <div class="mx-auto w-full max-w-xl xl:col-span-2">
        <div class="card flex items-center justify-between p-4">
          <div class="flex items-center gap-1">
            <Icon icon="vscode-icons:file-type-firebase" />
            <span class="text-sm font-semibold"> {{ trans('Your Profile is live') }} : </span>
            <a
              class="line-clamp-1 text-sm underline"
              target="_blank"
              :href="route('user.sociallink.profile-link.show', socialProfile.username)"
            >
              {{ route('user.sociallink.profile-link.show', socialProfile.username) }}
            </a>
          </div>
          <button
            type="button"
            class="btn btn-primary btn-xs"
            @click="
              copyToClipboard(route('user.sociallink.profile-link.show', socialProfile.username))
            "
          >
            <Icon icon="bx:copy" class="text-xs" />
            <span>{{ trans('Copy') }}</span>
          </button>
        </div>
        <div class="card mb-10 mt-4 p-4">
          <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
              <button
                type="button"
                @click="$refs.avatar.click()"
                class="relative h-20 w-20 flex-shrink-0"
              >
                <img
                  :src="showImage(form.avatar)"
                  alt="avatar"
                  class="h-full w-full rounded-full object-cover"
                />
                <div
                  class="absolute bottom-1 right-1 flex h-7 w-7 items-center justify-center rounded-full bg-primary-500 text-white"
                >
                  <Icon icon="bx:pencil" class="" />
                </div>
                <input
                  @input="
                    (e) => {
                      form.avatar = e.target.files[0]
                      submitUpdate()
                    }
                  "
                  type="file"
                  class="hidden"
                  accept="image/jpeg, image/png, image/jpg"
                  ref="avatar"
                />
              </button>
              <div>
                <button
                  type="button"
                  @click="modalStore.open('editProfile')"
                  class="font-medium hover:underline xl:text-lg"
                >
                  @{{ socialProfile.username }}
                </button>
                <button
                  type="button"
                  @click="modalStore.open('editProfile')"
                  class="line-clamp-1 text-sm text-gray-500"
                >
                  {{ socialProfile.bio || trans('Add bio') }}
                </button>
              </div>
            </div>
          </div>
        </div>

        <VueDraggable
          tag="ul"
          @end="submit"
          :animation="150"
          v-model="form.social_links"
          group="g1"
        >
          <li v-for="link in form.social_links" :key="link.id" class="mb-4">
            <div class="card flex items-center p-4">
              <div
                class="flex w-20 justify-start"
                :class="form.processing ? 'cursor-not-allowed' : 'cursor-grab'"
              >
                <Icon icon="fluent:re-order-dots-vertical-16-regular" class="text-2xl" />
              </div>
              <div class="col-span-10 w-full space-y-1">
                <template v-if="activeInput === 'name-' + link.id">
                  <div class="flex items-center gap-1">
                    <Icon :icon="link.icon" class="text-xl" />
                    <input
                      @focusout="
                        () => {
                          activeInput = null
                          submit()
                        }
                      "
                      @keydown.enter="submit"
                      :id="'input-name-' + link.id"
                      type="text"
                      class="w-full border-none bg-transparent p-0 focus:ring-0"
                      v-model="link.name"
                    />
                  </div>
                </template>
                <div class="flex items-center gap-1" v-else>
                  <Icon :icon="link.icon" class="text-xl" />
                  <span class="font-semibold"> {{ link.name }}</span>
                  <Icon
                    class="cursor-pointer"
                    @click="focusInput(link.id, 'name')"
                    icon="bx:pencil"
                  />
                </div>
                <div class="flex items-center gap-2">
                  <template v-if="activeInput === 'url-' + link.id">
                    <input
                      @focusout="
                        () => {
                          activeInput = null
                          submit()
                        }
                      "
                      @keydown.enter="submit"
                      :id="'input-url-' + link.id"
                      type="text"
                      class="w-full border-none bg-transparent p-0 focus:ring-0"
                      v-model="link.url"
                    />
                  </template>
                  <template v-else>
                    <p class="line-clamp-1">{{ textExcerpt(link.url, 100) }}</p>
                    <Icon
                      class="cursor-pointer"
                      @click="focusInput(link.id, 'url')"
                      icon="bx:pencil"
                    />
                  </template>
                </div>
              </div>
              <button
                type="button"
                @click="onDelete(link)"
                class="flex w-20 justify-end hover:text-red-500"
              >
                <Icon icon="bx:trash" class="text-xl" />
              </button>
            </div>
          </li>
        </VueDraggable>

        <button
          v-if="!newPlatformListState"
          @click="newPlatformListState = true"
          type="button"
          class="btn btn-primary mt-4 flex w-full items-center"
        >
          <Icon icon="bx:plus" class="text-lg" />
          <span class="font-semibold">{{ trans('Add Link') }}</span>
        </button>

        <template v-if="availablePlatforms.length > 0 && newPlatformListState">
          <div class="card space-y-4 p-6">
            <div class="flex items-center justify-between">
              <p class="text-lg font-semibold">{{ trans('Select platform') }}</p>
              <button
                type="button"
                @click="
                  () => {
                    newPlatformListState = false
                  }
                "
              >
                <Icon icon="bx:x" class="text-2xl" />
              </button>
            </div>
            <div class="flex flex-wrap gap-3">
              <button
                v-for="platform in availablePlatforms"
                :key="platform.id"
                @click="selectPlatform(platform)"
                :class="[
                  'flex items-center gap-1 rounded-lg border px-4 py-2 text-sm transition-all duration-300',
                  newPlatformForm?.name === platform.name
                    ? 'border-primary-900 bg-primary-500'
                    : 'border-gray-500 hover:border-primary-400'
                ]"
              >
                <Icon :icon="platform.icon" class="h-6 w-6" />
                <span class="font-medium">{{ platform.name }}</span>
              </button>
            </div>
          </div>
          <div v-if="newPlatformForm?.name" class="card mt-2 flex flex-col gap-4 p-3">
            <div class="flex items-center">
              <div class="rounded-md border border-gray-700 p-1.5">
                <Icon :icon="newPlatformForm.icon" class="h-6 w-6" />
              </div>
              <input
                v-model="newPlatformForm.url"
                :placeholder="`${newPlatformForm.name} url`"
                class="input flex-1"
                type="url"
              />
              <InputFieldError :message="newPlatformForm.errors.url" class="text-sm" />
            </div>

            <SpinnerBtn
              @click="submitNewLink"
              classes="btn btn-primary w-full"
              icon=""
              :processing="newPlatformForm.processing"
              :btn-text="trans('Save')"
            />
          </div>
        </template>
      </div>
      <div class="xl:col-span-1">
        <div
          class="custom-iframe relative overflow-hidden rounded-2xl border-2 border-dark-800 shadow-md outline outline-4 outline-dark-500 dark:border-dark-800 dark:outline-dark-950"
        >
          <iframe
            ref="iframe"
            :src="iframeLink"
            frameborder="0"
            width="100%"
            height="100%"
          ></iframe>
        </div>
      </div>
    </div>
  </main>
</template>
<style scoped>
.custom-iframe {
  height: 44rem;
  width: 22rem;
}
</style>
