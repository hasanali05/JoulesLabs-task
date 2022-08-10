<template>
    <div class="container mt-5">
        <div class="row justify-content-md-center">
            <div class="col col-lg-4">

                <form @submit.prevent="login">
                    <div class="mb-3" v-if="message">
                        <p class="alert alert-warning w-100">{{message}}</p>
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
                    
                    <NuxtLink class="btn btn-primary text-light" to="/register" >New Registration</NuxtLink>
                    <button type="submit" class="btn btn-primary">Login</button>
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
                email: '',
                password: '',
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
                this.login();
            }
        },
        login() {
            this.$auth.loginWith('laravelSanctum', {
                data: {
                    email: this.form.email,
                    password: this.form.password,
                }
            })
            .catch((err) => {
                this.allErrors = err.response.data.errors; 
                this.message = err.response.data.message
            })
            .then((res) => {
                let user = res.data;
                this.$auth.setUser(user);
                this.$auth.setUserToken(user.token);
                this.$auth.$storage.setUniversal('user', JSON.stringify(user))
                this.$axios.defaults.headers.common['Authorization'] = `Bearer ${user.token}`;
                this.$nextTick(() => {
                    this.redirect('/forms');
                })
            })
        }
    }
}
</script>
