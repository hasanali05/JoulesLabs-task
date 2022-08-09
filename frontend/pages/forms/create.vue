<template>
    <div>   
        <h1 class="text-center">Add new form</h1>
        <div class="row justify-content-md-center">
            <div class="col col-lg-6">
                <FormInput/>
            </div>
        </div>
    </div>
</template>

<script>
import FormInput from '../../components/FormInput.vue';
export default {
    name: "FromCreate",
    middleware: 'auth',
    components: { FormInput }
}
</script>
