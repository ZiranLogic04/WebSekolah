<template>
    <div class="toast-container">
        <!-- Success Toast -->
        <div 
            v-if="showSuccess" 
            class="toast toast-success"
            :class="{ 'show': showSuccess }"
        >
            <div class="toast-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">{{ successTitle }}</div>
                <div class="toast-message">{{ successMessage }}</div>
            </div>
            <button class="toast-close" @click="hideSuccess">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Error Toast -->
        <div 
            v-if="showError" 
            class="toast toast-error"
            :class="{ 'show': showError }"
        >
            <div class="toast-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">{{ errorTitle }}</div>
                <div class="toast-message">{{ errorMessage }}</div>
            </div>
            <button class="toast-close" @click="hideError">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Warning Toast -->
        <div 
            v-if="showWarning" 
            class="toast toast-warning"
            :class="{ 'show': showWarning }"
        >
            <div class="toast-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">{{ warningTitle }}</div>
                <div class="toast-message">{{ warningMessage }}</div>
            </div>
            <button class="toast-close" @click="hideWarning">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Info Toast -->
        <div 
            v-if="showInfo" 
            class="toast toast-info"
            :class="{ 'show': showInfo }"
        >
            <div class="toast-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">{{ infoTitle }}</div>
                <div class="toast-message">{{ infoMessage }}</div>
            </div>
            <button class="toast-close" @click="hideInfo">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onBeforeUnmount } from 'vue';

const props = defineProps({
    duration: {
        type: Number,
        default: 5000
    }
});

const showSuccess = ref(false);
const showError = ref(false);
const showWarning = ref(false);
const showInfo = ref(false);
const successTitle = ref('');
const successMessage = ref('');
const errorTitle = ref('');
const errorMessage = ref('');
const warningTitle = ref('');
const warningMessage = ref('');
const infoTitle = ref('');
const infoMessage = ref('');

let successTimeout = null;
let errorTimeout = null;
let warningTimeout = null;
let infoTimeout = null;

const showSuccessToast = (title = 'Sukses!', message = '') => {
    successTitle.value = title;
    successMessage.value = message;
    showSuccess.value = true;
    
    if (successTimeout) clearTimeout(successTimeout);
    successTimeout = setTimeout(() => {
        hideSuccess();
    }, props.duration);
};

const showErrorToast = (title = 'Error!', message = 'Terjadi kesalahan') => {
    errorTitle.value = title;
    errorMessage.value = message;
    showError.value = true;
    
    if (errorTimeout) clearTimeout(errorTimeout);
    errorTimeout = setTimeout(() => {
        hideError();
    }, props.duration);
};

const showWarningToast = (title = 'Peringatan!', message = '') => {
    warningTitle.value = title;
    warningMessage.value = message;
    showWarning.value = true;
    
    if (warningTimeout) clearTimeout(warningTimeout);
    warningTimeout = setTimeout(() => {
        hideWarning();
    }, props.duration);
};

const showInfoToast = (title = 'Info!', message = '') => {
    infoTitle.value = title;
    infoMessage.value = message;
    showInfo.value = true;
    
    if (infoTimeout) clearTimeout(infoTimeout);
    infoTimeout = setTimeout(() => {
        hideInfo();
    }, props.duration);
};

const hideSuccess = () => {
    showSuccess.value = false;
    if (successTimeout) clearTimeout(successTimeout);
};

const hideError = () => {
    showError.value = false;
    if (errorTimeout) clearTimeout(errorTimeout);
};

const hideWarning = () => {
    showWarning.value = false;
    if (warningTimeout) clearTimeout(warningTimeout);
};

const hideInfo = () => {
    showInfo.value = false;
    if (infoTimeout) clearTimeout(infoTimeout);
};

// Clear all toasts
const clearAll = () => {
    hideSuccess();
    hideError();
    hideWarning();
    hideInfo();
};

// Expose functions to parent component
defineExpose({
    showSuccessToast,
    showErrorToast,
    showWarningToast,
    showInfoToast,
    hideSuccess,
    hideError,
    hideWarning,
    hideInfo,
    clearAll
});

onBeforeUnmount(() => {
    if (successTimeout) clearTimeout(successTimeout);
    if (errorTimeout) clearTimeout(errorTimeout);
    if (warningTimeout) clearTimeout(warningTimeout);
    if (infoTimeout) clearTimeout(infoTimeout);
});
</script>

<style scoped>
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-width: 400px;
}

.toast {
    display: flex;
    align-items: flex-start;
    padding: 16px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    pointer-events: none;
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
    pointer-events: all;
}

.toast-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border-left: 4px solid #047857;
}

.toast-error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border-left: 4px solid #b91c1c;
}

.toast-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border-left: 4px solid #b45309;
}

.toast-info {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
    border-left: 4px solid #1e40af;
}

.toast-icon {
    font-size: 24px;
    margin-right: 12px;
    flex-shrink: 0;
}

.toast-content {
    flex-grow: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 4px;
}

.toast-message {
    font-size: 14px;
    opacity: 0.9;
    line-height: 1.4;
}

.toast-close {
    background: transparent;
    border: none;
    color: white;
    opacity: 0.7;
    cursor: pointer;
    padding: 4px;
    margin-left: 8px;
    border-radius: 4px;
    transition: opacity 0.2s;
    flex-shrink: 0;
}

.toast-close:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
}

/* Animation for multiple toasts */
.toast:nth-child(1) { transition-delay: 0.1s; }
.toast:nth-child(2) { transition-delay: 0.2s; }
.toast:nth-child(3) { transition-delay: 0.3s; }
.toast:nth-child(4) { transition-delay: 0.4s; }

@media (max-width: 576px) {
    .toast-container {
        left: 10px;
        right: 10px;
        top: 10px;
        max-width: none;
    }
    
    .toast {
        padding: 12px;
    }
}
</style>