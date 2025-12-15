<template>
    <div class="login-page">
        <div class="login-container">
            <!-- Left Side - Branding -->
            <div class="login-branding">
                <div class="branding-content">
                    <img src="/assets/img/hope/header_logo1.png" alt="Logo Sekolah" class="logo">
                    <h1>MI AL-HIKMAH</h1>
                    <p>Sistem Informasi Madrasah</p>
                    <div class="features">
                        <div class="feature-item">
                            <i class="fas fa-shield-alt"></i>
                            <span>Aman & Terenkripsi</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-clock"></i>
                            <span>Akses 24/7</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Responsive</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-section">
                <div class="login-form-wrapper">
                    <div class="form-header">
                        <h2>Selamat Datang!</h2>
                        <p>Silakan masuk untuk mengakses dashboard</p>
                    </div>

                    <div v-if="$page.props.errors.email" class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $page.props.errors.email }}
                    </div>

                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="email">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input 
                                id="email"
                                type="email" 
                                v-model="form.email" 
                                class="form-control" 
                                placeholder="Masukkan email anda"
                                required 
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label for="password">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <div class="password-wrapper">
                                <input 
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'" 
                                    v-model="form.password" 
                                    class="form-control" 
                                    placeholder="Masukkan password"
                                    required
                                >
                                <button type="button" class="toggle-password" @click="showPassword = !showPassword">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options">
                            <label class="remember-checkbox">
                                <input type="checkbox" v-model="form.remember">
                                <span class="checkmark"></span>
                                Ingat saya
                            </label>
                        </div>

                        <button type="submit" class="btn-login" :disabled="form.processing">
                            <span v-if="form.processing">
                                <i class="fas fa-spinner fa-spin"></i> Memproses...
                            </span>
                            <span v-else>
                                <i class="fas fa-sign-in-alt"></i> Masuk
                            </span>
                        </button>
                    </form>

                    <div class="back-home">
                        <a href="/">
                            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const showPassword = ref(false);

const submit = () => {
    form.post('/login');
};
</script>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.login-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #116E63 0%, #0a4a42 100%);
    padding: 20px;
}

.login-container {
    display: flex;
    width: 100%;
    max-width: 1000px;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
}

/* Left Branding */
.login-branding {
    flex: 1;
    background: linear-gradient(135deg, #116E63 0%, #0d5249 100%);
    padding: 60px 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.login-branding::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
    animation: pulse 15s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.branding-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.branding-content .logo {
    width: 100px;
    height: 100px;
    object-fit: contain;
    background: #fff;
    border-radius: 20px;
    padding: 10px;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.branding-content h1 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: 2px;
}

.branding-content > p {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 40px;
}

.features {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    opacity: 0.9;
}

.feature-item i {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Right Form */
.login-form-section {
    flex: 1;
    padding: 60px 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.login-form-wrapper {
    width: 100%;
    max-width: 360px;
}

.form-header {
    margin-bottom: 35px;
}

.form-header h2 {
    font-size: 28px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 8px;
}

.form-header p {
    color: #666;
    font-size: 14px;
}

.alert {
    padding: 12px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.alert-danger {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.form-group {
    margin-bottom: 24px;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
}

.form-group label i {
    color: #116E63;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.form-control:focus {
    outline: none;
    border-color: #116E63;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(17, 110, 99, 0.1);
}

.password-wrapper {
    position: relative;
}

.password-wrapper .form-control {
    padding-right: 50px;
}

.toggle-password {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 5px;
}

.toggle-password:hover {
    color: #116E63;
}

.form-options {
    margin-bottom: 24px;
}

.remember-checkbox {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    font-size: 14px;
    color: #555;
}

.remember-checkbox input {
    display: none;
}

.remember-checkbox .checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.remember-checkbox input:checked + .checkmark {
    background: #116E63;
    border-color: #116E63;
}

.remember-checkbox input:checked + .checkmark::after {
    content: '✓';
    color: #fff;
    font-size: 12px;
    font-weight: bold;
}

.btn-login {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #116E63 0%, #0d5249 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-login:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(17, 110, 99, 0.35);
}

.btn-login:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.back-home {
    text-align: center;
    margin-top: 30px;
}

.back-home a {
    color: #666;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s ease;
}

.back-home a:hover {
    color: #116E63;
}

/* Responsive */
@media (max-width: 768px) {
    .login-container {
        flex-direction: column;
    }

    .login-branding {
        padding: 40px 30px;
    }

    .branding-content .logo {
        width: 80px;
        height: 80px;
    }

    .branding-content h1 {
        font-size: 22px;
    }

    .features {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }

    .feature-item {
        font-size: 12px;
    }

    .feature-item span {
        display: none;
    }

    .login-form-section {
        padding: 40px 30px;
    }
}
</style>