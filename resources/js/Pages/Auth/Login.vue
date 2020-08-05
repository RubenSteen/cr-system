<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div>
                <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-on-white.svg" alt="Workflow">
                <h2 class="mt-6 text-center text-3xl leading-9 font-extrabold text-gray-900">
                    {{ $page.app.name }}
                </h2>
            </div>
            <form class="mt-8" action="#" method="POST" @submit.prevent="submitLogin">
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm">
                    <div>
                        <input v-model="form.username" aria-label="Username" name="username" type="text" :class="{ 'border-red-300 placeholder-red-500 text-red-900 focus:border-red-300': $page.errors.username}" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Username">
                    </div>
                    <div class="-mt-px">
                        <input v-model="form.password" aria-label="Password" name="password" type="password" :class="{ 'border-red-300 placeholder-red-500 text-red-900 focus:border-red-300': $page.errors.password}" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="Password">
                    </div>
                </div>

                <p class="mt-2 text-xs text-red-600 text-center" v-show="$page.errors.username" v-for="(error, index) in $page.errors.username">
                    {{ error }}
                </p>
                <p class="text-xs text-red-600 text-center" v-show="$page.errors.password" v-for="(error, index) in $page.errors.password">
                    {{ error }}
                </p>

                <div class="mt-6">
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700 transition duration-150 ease-in-out">
          <span class="absolute left-0 inset-y-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400 transition ease-in-out duration-150" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
          </span>
                        Sign in
                    </button>
                </div>
            </form>
        </div>
    </div>

</template>

<script>
    export default {
        remember: 'form',
        data() {
            return {
                form: {
                    username: '',
                    password: '',
                },
            }
        }, // End Data
        methods: {
            submitLogin() {
                var data = new FormData();

                data.append('_method', 'POST');

                for (var field in this.form) {
                    data.append(field, this.form[field]) // append form field to request
                }

                this.$inertia.post(route('login.go'), data)
                    .then(() => {
                        // Something
                    })
            }, // End submitLogin()
        }, // End Methods
    }
</script>

<style scoped>

</style>
