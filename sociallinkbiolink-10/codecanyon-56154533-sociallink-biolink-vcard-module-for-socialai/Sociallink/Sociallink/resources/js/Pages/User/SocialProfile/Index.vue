<script setup>
import PageHeader from '@root/Layouts/Admin/PageHeader.vue'
import sharedComposable from '@root/Composables/sharedComposable'
import UserLayout from '@root/Layouts/User/UserLayout.vue'
import NoDataFound from '@root/Components/Admin/NoDataFound.vue'
defineOptions({ layout: UserLayout })
const props = defineProps(['socialProfiles'])
const { textExcerpt, uiAvatar, trim, copyToClipboard } = sharedComposable()
</script>

<template>
  <main class="container flex-grow p-4 sm:p-6">
    <PageHeader title="Social Profiles" />
    <div class="mt-8">
      <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <template v-for="profile in socialProfiles" :key="profile.id">
          <div class="card card-body">
            <div class="flex items-center justify-start gap-3">
              <Link
                :href="route('user.sociallink.profile.show', profile.uuid)"
                class="logo h-16 w-16 flex-shrink-0 rounded-full"
              >
                <img
                  v-lazy="uiAvatar(profile.username, profile.avatar)"
                  alt="image"
                  class="h-full w-full rounded-full object-cover"
                />
              </Link>
              <div class="overflow-hidden">
                <Link
                  :href="route('user.sociallink.profile.show', profile.uuid)"
                  class="font-semibold capitalize"
                >
                  @{{ textExcerpt(profile.username, 40) }}
                </Link>
                <p class="line-clamp-1 text-sm capitalize leading-4 text-gray-500">
                  {{ textExcerpt(profile.bio, 40) }}
                </p>
                <button
                  type="button"
                  @click="
                    copyToClipboard(route('user.sociallink.profile-link.show', profile.username))
                  "
                  class="line-clamp-1 text-start text-sm underline"
                >
                  {{
                    textExcerpt(route('user.sociallink.profile-link.show', profile.username), 40)
                  }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </div>

      <NoDataFound v-if="socialProfiles.length < 1" />
    </div>
  </main>
</template>