<template>
    <div class="main-wrapper login-body">
        <div class="login-wrapper">
            <div class="container">
                <div class="loginbox">
                    <div class="login-left">
                        <img class="img-fluid" src="/assets/img/logingambar.png" alt="Ilustrasi Login">
                    </div>
                    <div class="login-right">
                        <div class="login-right-wrap">
                            <h1>Selamat Datang !</h1>
                            <p class="account-subtitle">Silakan masuk untuk menggunakan aplikasi</p>
                            <h2>Sign in</h2>

                            <div v-if="$page.props.errors?.email" class="alert alert-danger py-2">
                                {{ $page.props.errors.email }}
                            </div>

                            <form @submit.prevent="submit">
                                <div class="form-group">
                                    <label>Username atau Email <span class="login-danger">*</span></label>
                                    <input 
                                        class="form-control" 
                                        type="text" 
                                        v-model="form.email"
                                        required 
                                        autofocus
                                    >
                                    <span class="profile-views"><i class="fas fa-user-circle"></i></span>
                                </div>
                                <div class="form-group">
                                    <label>Password <span class="login-danger">*</span></label>
                                    <input 
                                        class="form-control pass-input" 
                                        :type="showPassword ? 'text' : 'password'" 
                                        v-model="form.password"
                                        required
                                    >
                                    <span class="profile-views feather-eye toggle-password" @click="showPassword = !showPassword"></span>
                                </div>
                                <div class="forgotpass">
                                    <div class="remember-me">
                                        <label class="custom_check mr-2 mb-0 d-inline-flex remember-me"> Ingat saya
                                            <input type="checkbox" v-model="form.remember">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit" :disabled="form.processing">
                                        <span v-if="form.processing"><i class="fas fa-spinner fa-spin"></i> Loading...</span>
                                        <span v-else>Login</span>
                                    </button>
                                </div>
                                <label class="mr-2 mb-0 d-inline-flex text-end"> Belum Punya Akun ?</label>
                                <a href="/">Kembali ke Beranda</a>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const showPassword = ref(false);

const submit = () => {
    form.post('/login');
};

onMounted(() => {
    // Initialize feather icons if available
    if (window.feather) {
        window.feather.replace();
    }
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: 'Roboto', sans-serif;
}

.main-wrapper {
    width: 100%;
    height: 100vh;
    min-height: 100vh;
}

.login-body {
    display: table;
    height: 100vh;
    min-height: 100vh;
    background-color: #f7f7fa;
}

.login-wrapper {
    width: 100%;
    height: 100%;
    display: table-cell;
    vertical-align: middle;
}

.login-wrapper .loginbox {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    display: flex;
    margin: 1.875rem auto;
    max-width: 900px;
    min-height: 550px;
    width: 100%;
}

.login-wrapper .loginbox .login-left {
    align-items: center;
    background: linear-gradient(135deg, #18aefa 0%, #3d5ee1 100%);
    flex-direction: column;
    justify-content: center;
    width: 450px;
    display: flex;
    border-radius: 8px 0 0 8px;
    position: relative;
    overflow: hidden;
    padding: 0;
}

.login-wrapper .loginbox .login-left img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
}

.login-wrapper .loginbox .login-right {
    align-items: center;
    display: flex;
    justify-content: center;
    padding: 40px;
    width: 400px;
}

.login-wrapper .loginbox .login-right .login-right-wrap {
    max-width: 100%;
    flex: 0 0 100%;
}

.login-wrapper .loginbox .login-right h1 {
    font-size: 25px;
    font-weight: 600;
    margin-bottom: 5px;
    text-align: left;
    color: #000;
}

.account-subtitle {
    color: #aaa;
    font-size: 16px;
    margin-bottom: 1.875rem;
    text-align: left;
}

.login-wrapper .loginbox .login-right h2 {
    font-size: 23px;
    font-weight: 500;
    color: #000;
    margin-bottom: 28px;
}

.form-group {
    margin-bottom: 1.25rem;
    position: relative;
}

.form-group label {
    font-size: 13px;
    color: #ababab;
    font-weight: 500;
    position: absolute;
    top: -10px;
    left: 10px;
    background: #fff;
    margin-bottom: 0;
    padding: 0 5px;
    z-index: 1;
}

.login-danger {
    color: red;
}

.form-control {
    border: 1px solid #ddd;
    box-shadow: none;
    color: #333;
    font-size: 15px;
    height: 45px;
    width: 100%;
    padding: 10px 40px 10px 15px;
    border-radius: 4px;
}

.form-control:focus {
    border-color: #18aefa;
    box-shadow: 0 0 0 3px rgba(24, 174, 250, 0.1);
    outline: none;
}

.profile-views {
    position: absolute;
    right: 17px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    cursor: pointer;
}

.toggle-password:hover {
    color: #18aefa;
}

.forgotpass {
    display: flex;
    margin-bottom: 20px;
}

.remember-me .custom_check {
    color: #878787;
    font-size: 14px;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.remember-me .custom_check input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.btn-primary {
    background-color: #18aefa;
    border-color: #18aefa;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-block {
    display: block;
    width: 100%;
}

.btn-primary:hover {
    background-color: #3d5ee1;
    border-color: #3d5ee1;
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.alert {
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.text-end {
    color: #666;
}

.text-end + a {
    color: #18aefa;
    text-decoration: none;
}

.text-end + a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
    .login-wrapper .loginbox {
        flex-direction: column;
        max-width: 400px;
        min-height: auto;
    }

    .login-wrapper .loginbox .login-left {
        width: 100%;
        min-height: 250px;
        border-radius: 8px 8px 0 0;
        position: relative;
    }

    .login-wrapper .loginbox .login-left img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0;
        left: 0;
    }

    .login-wrapper .loginbox .login-right {
        width: 100%;
        padding: 30px;
    }

    .login-wrapper .loginbox .login-right h1 {
        font-size: 20px;
    }

    .login-wrapper .loginbox .login-right h2 {
        font-size: 18px;
    }
}
</style>