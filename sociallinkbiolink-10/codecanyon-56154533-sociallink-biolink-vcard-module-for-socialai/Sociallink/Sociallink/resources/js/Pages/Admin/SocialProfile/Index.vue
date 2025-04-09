<script setup>
import AdminLayout from '@root/Layouts/Admin/AdminLayout.vue'
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import { useForm } from '@inertiajs/vue3'
import trans from '@root/Composables/transComposable'
import moment from 'moment'
import Pagination from '@root/Components/Admin/Paginate.vue'
import sharedComposable from '@root/Composables/sharedComposable'
import NoDataFound from '@root/Components/Admin/NoDataFound.vue'
import FilterForm from '@root/Components/Admin/FilterForm.vue'

defineOptions({ layout: AdminLayout })
const { deleteRow, uiAvatar } = sharedComposable()

const props = defineProps({
  socialProfiles: {
    type: Object,
    required: true
  }
})

const filterOptions = [
  {
    value: 'name',
    label: 'Name'
  },
  {
    value: 'username',
    label: 'Username'
  },
  {
    value: 'url',
    label: 'URL'
  }
]
</script>

<template>
  <main class="container flex-grow p-4 sm:p-6">
    <PageHeader :title="trans('Social Profiles')" />

    <div class="space-y-4">
      <FilterForm :options="filterOptions" />

      <div class="table-responsive whitespace-nowrap rounded-primary">
        <table class="table">
          <thead>
            <tr>
              <th>{{ trans('Profile') }}</th>
              <th>{{ trans('User Name') }}</th>
              <th>{{ trans('Profession') }}</th>
              <th>{{ trans('Created At') }}</th>
              <th class="!text-right">{{ trans('Actions') }}</th>
            </tr>
          </thead>
          <tbody v-if="socialProfiles.data && socialProfiles.data.length">
            <tr v-for="profile in socialProfiles.data" :key="profile.id">
              <td>
                <a
                  class="flex items-center gap-3"
                  :href="`/bio/${profile.username}`"
                  target="_blank"
                >
                  <img
                    :src="uiAvatar(profile.name, profile.avatar)"
                    :alt="profile.name"
                    class="h-10 w-10 rounded-full object-cover"
                  />
                  <div>
                    <p class="font-medium">{{ profile.name }}</p>
                    <p class="text-sm text-gray-500">@{{ profile.username }}</p>
                  </div>
                </a>
              </td>

              <td>
                <a :href="route('admin.users.show', profile.user?.id)" class="underline">
                  {{ profile.user?.name }}
                </a>
              </td>
              <td>{{ profile.category?.title }}</td>
              <td>{{ moment(profile.created_at).format('DD MMM, YYYY h:mm a') }}</td>
              <td>
                <div class="flex justify-end">
                  <div class="dropdown" data-placement="bottom-start">
                    <div class="dropdown-toggle">
                      <Icon class="text-2xl" icon="bx:dots-horizontal-rounded" />
                    </div>
                    <div class="dropdown-content w-40">
                      <ul class="dropdown-list">
                        <li class="dropdown-list-item">
                          <Link
                            :href="route('admin.sociallink.profile.edit', profile.uuid)"
                            class="dropdown-link"
                          >
                            <Icon icon="fe:pencil" />
                            <span>{{ trans('Edit') }}</span>
                          </Link>
                        </li>
                        <li class="dropdown-list-item">
                          <a
                            :href="`/bio/${profile.username}`"
                            class="dropdown-link"
                            target="_blank"
                          >
                            <Icon icon="fe:eye" />
                            <span>{{ trans('View') }}</span>
                          </a>
                        </li>
                        <li class="dropdown-list-item">
                          <button
                            @click="
                              deleteRow(route('admin.sociallink.profile.destroy', profile.uuid))
                            "
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

      <Pagination :links="socialProfiles.links" />
    </div>
  </main>
</template>
