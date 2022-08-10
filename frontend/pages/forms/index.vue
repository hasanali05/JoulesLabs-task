<template>
<div>
    <h3 class="py-3">
        Form list
        <NuxtLink class="btn btn-primary text-light" to="/forms/create" >Add new</NuxtLink>
    </h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>TItle</th>
                <th>Status</th>
                <th>Updated at</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(form, index) in forms" :key="'form_' + index">
                <td>
                    <a :href="form.file_link" target="_blank">{{form.title}}</a>
                </td>
                <td>
                    <template>
                        <span class="badge bg-success" v-if="form.is_published">Published</span>
                        <span class="badge bg-danger" v-else>Not published</span>
                    </template>
                    <template>
                        <span class="btn btn-danger btn-sm" v-if="form.is_published" @click.prevent="statusChange(form.id, 'is_published', false)">Unpublish</span>
                        <span class="btn btn-success btn-sm" v-else @click.prevent="statusChange(form.id, 'is_published', true)">Publish</span>
                    </template>
                </td>
                <td>{{$dayjs(form.updated_at).format('DD MMM, YYYY. hh:mm a')}}</td>
                <td>
                    <NuxtLink class="text-primary btn btn-sm" :to="`/forms/${form.slug}`" >
                        <i class="lni lni-eye"></i>
                    </NuxtLink>
                    <button class="text-danger btn btn-sm" @click.prevent="deleteForm(form.id)">
                        <i class="lni lni-trash-can"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>

<script>
export default {
    middleware: 'auth',
    layout: 'main',
    data() {
      return {
        forms: []
      }
    },
    async fetch() {
      this.forms = await this.$axios.$get('api/forms')
    },
    methods: {
        async statusChange(formId, key, value) {
            if(!await this.takeConfirmation('this will change the status!')) return;

            this.$axios.$post(`api/forms/${formId}/status-change`, {
                key: key,
                value: value,
            })
            .then((response) => {
                this.$fetch();
                this.$swal(
                    'Updated!',
                    response.data.message,
                    'success'
                )
            })
        },
        async deleteForm(formId, key, value) {
            if(!await this.takeConfirmation('this will delete form and form related data!')) return;

            this.$axios.$delete(`api/forms/${formId}`)
            .then((response) => {
                this.$fetch();
                this.$swal(
                    'Updated!',
                    response.data.message,
                    'success'
                )
            })
        },
        async takeConfirmation(message = 'this will change the status!') {
            let result = await this.$swal({
                    title: 'Are you sure?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, do it!'
                })
            if (result.value) {
                return true
            }
            return false;
        }
    }
}
</script>
