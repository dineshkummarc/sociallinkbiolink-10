<script setup>
import AdminLayout from '@root/Layouts/Admin/AdminLayout.vue'
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import { useForm } from '@inertiajs/vue3'
import trans from '@root/Composables/transComposable'
import moment from 'moment'
import Pagination from '@root/Components/Admin/Paginate.vue'
import drawer from '@root/Plugins/Admin/drawer'
import { onMounted } from 'vue'
import SpinnerBtn from '@root/Components/Admin/SpinnerBtn.vue'
import NoDataFound from '@root/Components/Admin/NoDataFound.vue'
import FilterForm from '@root/Components/Admin/FilterForm.vue'
import sharedComposable from '@root/Composables/sharedComposable'
import InputFieldError from '@root/Components/InputFieldError.vue'
defineOptions({ layout: AdminLayout })
const { deleteRow } = sharedComposable()
onMounted(() => {
  drawer.init()
})

const props = defineProps(['socialLinks', 'socialLinkSettings', 'buttons'])

const socialLinkForm = useForm({
  credit: props.socialLinkSettings.credit
})
const editForm = useForm({
  id: '',
  uuid: '',
  name: '',
  url: '',
  username: '',
  bio: '',
  status: 'active'
})

const submit = () => {
  editForm.patch(route('admin.sociallink.linktree.update', editForm.uuid), {
    onSuccess: () => {
      editForm.value = {}
      drawer.of('#socialLinkEditDrawer').hide()
    }
  })
}
const openEditModal = (item) => {
  editForm.id = item.id
  editForm.uuid = item.social_profile.uuid
  editForm.username = item.social_profile.username
  editForm.bio = item.social_profile.bio

  editForm.name = item.name
  editForm.url = item.url
  editForm.status = item.status
  drawer.of('#socialLinkEditDrawer').show()
}
function updateOption(form, key, drawerId) {
  form.put(route('admin.option.update', key), {
    onSuccess: () => {
      drawer.of(drawerId).hide()
      editForm.reset()
    }
  })
}

const filterOptions = [
  {
    value: 'url',
    label: 'URL'
  },
  {
    label: 'Status',
    value: 'status',
    options: [
      {
        label: 'Active',
        value: 'active'
      },
      {
        label: 'Inactive',
        value: 'inactive'
      }
    ]
  }
]
</script>

<template>
  <!-- Main Content Starts -->
  <main class="container flex-grow p-4 sm:p-6">
    <PageHeader :title="trans('Social Links')" :buttons="buttons" />

    <div class="space-y-4">
      <FilterForm :options="filterOptions" />
      <div class="table-responsive whitespace-nowrap rounded-primary">
        <table class="table">
          <thead>
            <tr>
              <th>{{ trans('Platform') }}</th>
              <th>{{ trans('Username') }}</th>

              <th>{{ trans('URL') }}</th>

              <th>{{ trans('Date') }}</th>
              <th class="!text-right">{{ trans('Actions') }}</th>
            </tr>
          </thead>
          <tbody v-if="socialLinks.data && socialLinks.data.length">
            <tr v-for="link in socialLinks.data" :key="link.id">
              <td>
                <p class="flex items-center gap-1">
                  <Icon :icon="link.icon" class="text-xl" />
                  <span>{{ link.name }}</span>
                </p>
              </td>
              <td>
                <a
                  class="hover:underline"
                  :href="`http://stori-ai.test/bio/${link.social_profile.username}`"
                  target="_blank"
                >
                  {{ link.social_profile.username }}
                </a>
              </td>
              <td>
                <a :href="link.url" target="_blank" class="text-primary-500 hover:underline">
                  {{ link.url }}
                </a>
              </td>

              <td>{{ moment(link.created_at).format('DD MMM, YYYY.  h:mm a') }}</td>
              <td>
                <div class="flex justify-end">
                  <div class="dropdown" data-placement="bottom-start">
                    <div class="dropdown-toggle">
                      <Icon class="text-2xl" icon="bx:dots-horizontal-rounded" />
                    </div>
                    <div class="dropdown-content w-40">
                      <ul class="dropdown-list">
                        <li class="dropdown-list-item">
                          <button @click="openEditModal(link)" class="dropdown-link text-red-500">
                            <Icon icon="fe:pencil" />
                            <span>{{ trans('Edit') }}</span>
                          </button>
                        </li>
                        <li class="dropdown-list-item">
                          <button
                            @click="deleteRow(route('admin.sociallink.linktree.destroy', link.id))"
                            class="dropdown-link text-red-500"
                          >
                            <Icon icon="fe:trash" />
                            <span>{{ trans('Delete') }}</span>
                          </button>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
          <NoDataFound v-else for-table="true" />
        </table>
      </div>

      <Pagination v-if="socialLinks.data && socialLinks.data.length" :links="socialLinks.links" />
    </div>
  </main>
  <!-- Main Content Ends -->

  <div id="socialLinkSettingDrawer" class="drawer drawer-right">
    <form
      method="POST"
      @submit.prevent="updateOption(socialLinkForm, 'social_link', '#socialLinkSettingDrawer')"
    >
      <div class="drawer-header">
        <h5>{{ trans('Social Link Settings') }}</h5>
        <button
          type="button"
          class="btn btn-plain-secondary dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:bg-slate-700"
          data-dismiss="drawer"
        >
          <Icon class="text-xl" icon="mdi:close" />
        </button>
      </div>
      <div class="drawer-body">
        <div class="mb-2">
          <label class="label mb-1">{{ trans('Credit charge per social profile') }}</label>
          <input type="number" v-model="socialLinkForm.credit" class="input" required />
        </div>
      </div>
      <div class="drawer-footer">
        <div class="flex justify-end gap-2">
          <button type="button" class="btn btn-secondary" data-dismiss="drawer">
            <span>{{ trans('Close') }}</span>
          </button>

          <SpinnerBtn
            classes="btn btn-primary"
            :processing="socialLinkForm.processing"
            :btn-text="trans('Save Changes')"
          />
        </div>
      </div>
    </form>
  </div>
  <div id="socialLinkEditDrawer" class="drawer drawer-right">
    <form method="POST" @submit.prevent="submit">
      <div class="drawer-header">
        <h5>{{ trans('Social Link Editing') }}</h5>
        <button
          type="button"
          class="btn btn-plain-secondary dark:text-slate-300 dark:hover:bg-slate-700 dark:focus:bg-slate-700"
          data-dismiss="drawer"
        >
          <Icon class="text-xl" icon="mdi:close" />
        </button>
      </div>
      <div class="drawer-body space-y-1">
        <div>
          <label class="label mb-1">{{ trans('Username') }}</label>
          <input type="text" v-model="editForm.username" class="input" required />
          <InputFieldError :message="editForm.errors.username" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Bio') }}</label>
          <textarea type="text" v-model="editForm.bio" class="textarea"></textarea>
          <InputFieldError :message="editForm.errors.bio" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Name') }}</label>
          <input type="text" v-model="editForm.name" class="input" required />
          <InputFieldError :message="editForm.errors.name" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Url') }}</label>
          <input type="text" v-model="editForm.url" class="input" required />
          <InputFieldError :message="editForm.errors.url" />
        </div>
        <div>
          <label class="label mb-1">{{ trans('Status') }}</label>
          <select v-model="editForm.status" class="select">
            <option value="active">{{ trans('Active') }}</option>
            <option value="inactive">{{ trans('Inactive') }}</option>
          </select>
          <InputFieldError :message="editForm.errors.status" />
        </div>
      </div>
      <div class="drawer-footer">
        <div class="flex justify-end gap-2">
          <button type="button" class="btn btn-secondary" data-dismiss="drawer">
            <span>{{ trans('Close') }}</span>
          </button>

          <SpinnerBtn
            classes="btn btn-primary"
            :processing="editForm.processing"
            :btn-text="trans('Save Changes')"
          />
        </div>
      </div>
    </form>
  </div>
</template>
