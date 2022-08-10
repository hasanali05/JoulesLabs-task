<template>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col col-lg-4">
                <form @submit.prevent="register">
                    <div class="mb-3" v-if="message">
                        <p class="alert alert-warning w-100">{{message}}</p>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="name" class="form-control" id="name" v-model="form.name">
                        <v-errors :errors="errorFor('name')"></v-errors>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" v-model="form.email">
                        <v-errors :errors="errorFor('email')"></v-errors>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" v-model="form.password" @keyup="keyup">
                        <v-errors :errors="errorFor('password')"></v-errors>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="password_confirmation" v-model="form.password_confirmation" @keyup="keyup">
                        <v-errors :errors="errorFor('password_confirmation')"></v-errors>
                    </div>
                    
                    <NuxtLink class="btn btn-primary text-light" to="/login" >Back to Login</NuxtLink>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    layout: 'login',
    data() {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            },
            message: '',
        }
    },
    mounted() {
        if(this.$auth.loggedIn) {
            this.redirect('/forms')
        }
    },
    methods: {
        redirect(path = '/') {
            if (this.$route.query.next && isRelativeURL(this.$route.query.next)) {
                this.$router.push(this.$route.query.next);
            } else {
                this.$router.push(path);
            }
        },
        keyup(e) {
            if (e.keyCode === 13) {
                this.register();
            }
        },
        register() {
            this.allErrors = null;
            this.$store.dispatch('authModule/register', this.form)
                .then((response) => {
                    this.$swal(
                        'Successful!',
                        response.message,
                        'success'
                    )
                    this.$nextTick(() => {
                        this.login();
                    })
                })
                .catch((err) => {
                    this.allErrors = err.errors
                    this.message = err.message
                })
        },
        login() {
            this.allErrors = null;

            this.$store.dispatch('authModule/login', this.form)
                .then((response) => {
                    this.$nextTick(() => {
                        this.redirect('/forms');
                    })
                })
                .catch((err) => {
                    this.allErrors = err.errors ; 
                    this.message = err.message
                })
        }
    }
}
</script>
