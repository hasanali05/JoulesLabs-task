<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <NuxtLink class="nav-link" :class="{active: $nuxt.$route.name == 'forms'}" to="/forms" >Forms</NuxtLink>
                        </li>
                    </ul>
                </div>
                <button class="btn btn-danger mr-3" @click.prevent="logout" v-if="$auth.loggedIn">Logout</button>
                <NuxtLink class="btn btn-primary mr-3" to="/login" v-else >Login</NuxtLink>
            </div>
        </nav>
        <div class="container">
            <Nuxt />
        </div>
    </div>
</template>

<script>
export default {
    name: 'LayoutsDefault',
    methods: {
        logout() {
            this.$store.dispatch('authModule/logout')
        }
    }
}
</script>
