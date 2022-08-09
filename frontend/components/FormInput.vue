<template>
    <form>
        <div class="mb-3" v-if="message">
            <p class="alert alert-warning w-100">{{message}}</p>
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" v-model="form.title">
            <v-errors :errors="errorFor('title')"></v-errors>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" rows="3" id="description" v-model="form.description"></textarea>
            <v-errors :errors="errorFor('description')"></v-errors>
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="isPublicChecked" :checked="form.is_public" v-model="form.is_public">
                <label class="form-check-label" for="isPublicChecked">Share publicly (anyone with this link can see)</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_published" id="is_published_true" :value="true" v-model="form.is_published">
                <label class="form-check-label" for="is_published_true">Published</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_published" id="is_published_false" :value="false" v-model="form.is_published">
                <label class="form-check-label" for="is_published_false">Unpublished</label>
            </div>
        </div>
        <hr>
        <!-- fields -->
        <template v-for="(field, fieldIndex) in form.fields">
            <h4 class="d-flex justify-content-between">
                Field {{fieldIndex + 1}}
                <div>
                    <a class="btn btn-sm btn-danger" v-if="form.fields.length > 1" @click.prevent="deleteField(fieldIndex)">
                        <i class="lni lni-trash-can" style="font-size: 12px"></i> Remove 
                    </a>
                    <a class="btn btn-sm btn-success"  @click.prevent="addField()">
                        <i class="lni lni-plus" style="font-size: 12px"></i> Add 
                    </a>
                </div>
            </h4>
            <div class="mb-3">
                <label :for="'fieldTypes_'+fieldIndex" class="form-label">Type</label>
                <select class="form-select" :id="'fieldTypes_'+fieldIndex" v-model="field.type">
                    <option selected value="">Choose...</option>
                    <option v-for="fieldType in fieldTypes" :value="fieldType" :key="'fieldType_'+fieldIndex+'_'+fieldType">{{fieldType}}</option>
                </select>
                <v-errors :errors="errorFor(`fields.${fieldIndex}.type`)"></v-errors>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="title" v-model="field.name">
                <v-errors :errors="errorFor(`fields.${fieldIndex}.name`)"></v-errors>
            </div>
            <div class="mb-3" v-if="['radio', 'checkbox', 'select-option'].includes(field.type)">
                <label for="title" class="form-label">Options (comma separated options.)</label>
                <input type="text" class="form-control" id="title" v-model="field.options">
                <v-errors :errors="errorFor(`fields.${fieldIndex}.options`)"></v-errors>
            </div>
            <h5 class="d-flex justify-content-between mb-3">
                Rules
                <!-- <div>
                    <a class="btn btn-sm btn-success"><i class="lni lni-plus" style="font-size: 12px"></i> Add </a>
                </div> -->
            </h5>
            
            <!-- Rules -->
            <template v-for="(rule, ruleIndex) in field.rules">
                <div class="input-group">
                    <select class="form-select" v-model="rule.logic" @change="ruleValueUpdate(fieldIndex, ruleIndex)">
                        <option selected value="">Choose...</option>
                        <option v-for="ruleType in ruleTypes" :value="ruleType" :key="'ruleType_'+fieldIndex+'_'+ruleIndex+'_'+ruleType">{{ruleType}}</option>
                    </select>
                    <template>
                        <div class="form-check" v-if="['required'].includes(rule.logic)" style="margin-left: 10px">
                            <input class="form-check-input" type="checkbox" :value="true" :checked="form.fields[fieldIndex].rules[ruleIndex].value" v-model="rule.value" placeholder="value">
                        </div>
                        <input type="text" class="form-control" v-model="form.fields[fieldIndex].rules[ruleIndex].value" v-else>
                    </template>
                    <a class="btn btn-outline-secondary" type="button" v-if="field.rules.length > 1" @click.prevent="deleteRule(fieldIndex, ruleIndex)">
                        <i class="lni lni-trash-can " style="font-size: 16px; font-weight: bold;"></i>
                    </a>
                    <a class="btn btn-outline-secondary" type="button"  @click.prevent="addRule(fieldIndex)">
                        <i class="lni lni-plus" style="font-size: 16px; font-weight: bold;"></i>
                    </a>
                </div>
                <v-errors :errors="errorFor(`fields.${fieldIndex}.rules.${ruleIndex}.logic`)"></v-errors>
                <v-errors :errors="errorFor(`fields.${fieldIndex}.rules.${ruleIndex}.value`)"></v-errors>
                <p class=" mb-3"></p>
            </template>
        </template>
        <a type="submit" class="btn btn-primary mt-4" @click.prevent="submit">Submit</a>
    </form>
</template>

<script>
import index from '../pages/index.vue';
export default {
  components: { index },
    name: 'FormInput',
    props: {
        formId: {
            default: ''
        },
    },
    data() {
        return {
            form: {
                title: "",
                description: "",
                is_public: false,
                is_published: false,
                fields: [],
            },
            newField: {
                type: '',
                name: '',
                options: '',
                rules: [],
            },
            newRule: {
                logic: '',
                value: ''
            },
            fieldTypes: [
                'text',
                'number',
                'radio',
                'checkbox',
                'select-option',
                'textarea'
            ],
            ruleTypes: [
                'required',
                'min',
                'max',
            ],
            message: "",
        };
    },
    methods: {
        addField() {
            let newField = JSON.parse(JSON.stringify(this.newField));
            newField.rules.push(this.newRule)
            this.form.fields.push(newField)
        },
        addRule(fieldIndex) {
            this.form.fields[fieldIndex].rules.push(JSON.parse(JSON.stringify(this.newRule)))
        },
        deleteRule(fieldIndex, ruleIndex) {
            this.form.fields[fieldIndex].rules.splice(ruleIndex, 1);
        },
        deleteField(fieldIndex) {
            this.form.fields.splice(fieldIndex, 1);
        },
        ruleValueUpdate(fieldIndex, ruleIndex) {
            if(['required'].includes(this.form.fields[fieldIndex].rules[ruleIndex].logic)) {
                this.form.fields[fieldIndex].rules[ruleIndex].value = true;
            }
        },
        resetForm() {
            this.form = {
                title: "",
                description: "",
                is_public: true,
                is_published: false,
                fields: [],
            }
        },
        submit() {
            this.$axios.$post(`forms`, this.form)
                .then((response) => {
                    this.$swal(
                        'Successful!',
                        response.message,
                        'success'
                    )
                    this.$nextTick(() => {
                        this.$router.push('/forms');
                    })
                })
                .catch((err) => {
                    this.allErrors = err.response.data.errors; 
                    this.message = err.response.data.message
                })
        }
    },
    created() {
        this.resetForm();
        this.addField();
    },
}
</script>

