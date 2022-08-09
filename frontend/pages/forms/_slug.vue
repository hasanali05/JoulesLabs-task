<template>
    <div>
        <div class="mb-3" v-if="this.$data.message">
            <p class="alert alert-warning w-100">{{message}}</p>
        </div>
        <template v-if="this.$data.title">
            <h3 class="py-3">
                {{title}} 
            </h3>
            <p>{{description}}</p>

            <!-- build form -->
            <template v-for="(field, fieldIndex) in fields">
                <div class="mb-3" v-if="field.type == 'text'" :key="'field_'+fieldIndex">
                    <label :for="'field_'+fieldIndex" class="form-label">{{field.name}}</label>
                    <input type="text" class="form-control" :id="'field_'+fieldIndex" v-model="field.submit_value">
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
                <div class="mb-3" v-if="field.type == 'number'" :key="'field_'+fieldIndex">
                    <label :for="'field_'+fieldIndex" class="form-label">{{field.name}}</label>
                    <input type="number" class="form-control" :id="'field_'+fieldIndex" v-model="field.submit_value">
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
                <div class="mb-3" v-if="field.type == 'radio'" :key="'field_'+fieldIndex">
                    <div class="form-check form-check-inline" v-for="value in getValues(field.options)" :key="'field_'+fieldIndex+'_option_'+value">
                        <input class="form-check-input" type="radio" :name="'field_'+fieldIndex+'_option'" :id="'field_'+fieldIndex+'_option_'+value" :value="value" v-model="field.submit_value">
                        <label class="form-check-label" :for="'field_'+fieldIndex+'_option_'+value">{{value}}</label>
                    </div>
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
                <div class="mb-3" v-if="field.type == 'checkbox'" :key="'field_'+fieldIndex">
                    <label :for="'field_'+fieldIndex" class="form-label">{{field.name}}</label>
                    <input type="text" class="form-control" :id="'field_'+fieldIndex" v-model="field.submit_value">
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
                <div class="mb-3" v-if="field.type == 'select-option'" :key="'field_'+fieldIndex">
                    <label :for="'field_'+fieldIndex" class="form-label">{{field.name}}</label>
                    <select class="form-select" :id="'field_'+fieldIndex" v-model="field.submit_value">
                        <option selected value="">Choose...</option>
                        <option v-for="value in getValues(field.options)" :value="value" :key="'field_'+fieldIndex+'_option_'+value">{{value}}</option>
                    </select>
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
                <div class="mb-3" v-if="field.type == 'textarea'" :key="'field_'+fieldIndex">
                    <label :for="'field_'+fieldIndex" class="form-label">{{field.name}}</label>
                    <textarea rows="3" class="form-control" :id="'field_'+fieldIndex" v-model="field.submit_value"></textarea>
                    <v-errors :errors="errorFor(`fields.${fieldIndex}.submit_value`)"></v-errors>
                </div>
            </template>
            <a type="submit" class="btn btn-primary mt-4" @click.prevent="submit">Submit</a>
        </template>
    </div>
</template>

<script>
import index from '../index.vue';
export default {
  components: { index },
    middleware: 'auth',
    data() {
        return {
            message: '',
        }
    },
    async asyncData({params, $axios}) {
        const form = await $axios.$get(`forms/${params.slug}`).catch((err) => {
            return {message : err.response.data.message}
        })
        return form;
    },
    methods: {
        getValues(value) {
            if(!value) return [];
            return value.split(",");
        }, 
        submit() {
            this.$axios.$post(`forms/${this.slug}/submit`, {fields: this.fields})
                .then((response) => {
                    this.$swal(
                        'Successful!',
                        response.message,
                        'success'
                    )
                    this.$nextTick(() => {
                        this.$router.push('/');
                    })
                })
                .catch((err) => {
                    this.allErrors = err.response.data.errors; 
                    this.message = err.response.data.message
                })
        },
    }
}
</script>
