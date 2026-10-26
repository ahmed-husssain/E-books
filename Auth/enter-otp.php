<?php
session_start();
$otpError = $_SESSION['otp_error'] ?? null;
unset($_SESSION['otp_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - E - Books</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            950: '#020617',
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            500: '#64748b',
                            400: '#94a3b8',
                            300: '#cbd5e1'
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #020617 0%, #1e293b 100%);
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            overflow: hidden; /* Prevent scrolling */
        }
        .otp-digit {
            font-family: 'Courier New', monospace;
        }
        .otp-digit.filled {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
            color: white;
        }
        .fallback-input {
            font-family: 'Courier New', monospace;
            letter-spacing: 3px;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center gradient-bg">
    <div class="max-w-md w-full mx-auto px-4 sm:px-6 py-8">
        <div class="glass-effect rounded-2xl p-8 animate-fade-in">
            <!-- Icon -->
            <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-white mb-2">Verify Your Email</h1>
                <p class="text-slate-300 text-sm">We've sent a 6-digit verification code to your email address. Please enter it below to reset your password.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="verify-otp.php" id="otpForm">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-300 mb-2 uppercase tracking-wide">Enter Verification Code</label>
                    <div class="otp-input-container flex gap-2 justify-center mb-4" id="digitInputs">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="0">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="1">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="2">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="3">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="4">
                        <input type="text" class="otp-digit w-12 h-14 p-2 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-xl font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20" maxlength="1" data-index="5">
                    </div>
                    <input type="text" name="otp" class="fallback-input w-full p-3 bg-slate-900 text-slate-300 border border-slate-700 rounded-lg text-center text-lg font-bold focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 hidden" id="fallbackInput" placeholder="Enter 6-digit OTP" maxlength="6">
                    <div class="toggle-input text-center text-sm text-emerald-400 hover:text-emerald-500 cursor-pointer underline mt-2" onclick="toggleInputMethod()">
                        <span id="toggleText">Having trouble? Use single input instead</span>
                    </div>
                </div>

                <?php if (!empty($otpError)): ?>
                    <div class="bg-red-900/60 border border-red-700 text-red-200 rounded-lg p-3 mb-6 text-sm text-center">
                        <?php echo htmlspecialchars($otpError); ?>
                    </div>
                <?php endif; ?>
                <div class="error-message bg-red-900 border border-red-700 text-red-200 rounded-lg p-3 mb-6 text-sm hidden" id="errorMessage"></div>
                <div class="success-message bg-slate-800 border border-slate-700 text-emerald-400 rounded-lg p-3 mb-6 text-sm hidden" id="successMessage"></div>

                <div class="timer-display bg-slate-800 border border-slate-700 text-slate-300 rounded-lg p-3 mb-6 text-sm flex items-center justify-center gap-2" id="timerDisplay">
                    <i class="fas fa-hourglass-half"></i> Code expires in <span id="timer">10:00</span>
                </div>

                <button type="submit" class="verify-button w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg" id="verifyBtn">
                    Verify & Continue
                </button>
            </form>

            <div class="resend-section mt-6 pt-4 border-t border-slate-700 text-center">
                <p class="resend-text text-slate-300 text-sm mb-2">Didn't receive the code?</p>
                <button type="button" class="resend-button bg-transparent border border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-white py-2 px-4 rounded-full text-sm font-medium transition-all duration-300" id="resendBtn">
                    Resend Code
                </button>
            </div>
        </div>
    </div>

    <script>
        // OTP functionality
        const digitInputs = document.querySelectorAll('.otp-digit');
        const fallbackInput = document.getElementById('fallbackInput');
        const form = document.getElementById('otpForm');
        const timerDisplay = document.getElementById('timerDisplay');
        const timer = document.getElementById('timer');
        const resendBtn = document.getElementById('resendBtn');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');
        
        let currentInputMethod = 'digits'; // 'digits' or 'single'
        let timeLeft = 600; // 10 minutes in seconds
        let timerInterval;

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            startTimer();
            setupDigitInputs();
        });

        function setupDigitInputs() {
            digitInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (e.target.value.length === 1) {
                        e.target.classList.add('filled');
                        if (index < digitInputs.length - 1) {
                            digitInputs[index + 1].focus();
                        }
                    } else {
                        e.target.classList.remove('filled');
                    }
                    updateOTPValue();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        digitInputs[index - 1].focus();
                        digitInputs[index - 1].classList.remove('filled');
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = e.clipboardData.getData('text');
                    if (paste.length === 6 && /^\d{6}$/.test(paste)) {
                        fillDigits(paste);
                    }
                });
            });
        }

        function fillDigits(otp) {
            for (let i = 0; i < 6; i++) {
                digitInputs[i].value = otp[i];
                digitInputs[i].classList.add('filled');
            }
            updateOTPValue();
        }

        function updateOTPValue() {
            const otp = Array.from(digitInputs).map(input => input.value).join('');
            fallbackInput.value = otp;
        }

        function toggleInputMethod() {
            const digitContainer = document.getElementById('digitInputs');
            const toggleText = document.getElementById('toggleText');
            
            if (currentInputMethod === 'digits') {
                digitContainer.style.display = 'none';
                fallbackInput.style.display = 'block';
                toggleText.textContent = 'Use individual digit inputs instead';
                currentInputMethod = 'single';
                fallbackInput.focus();
            } else {
                digitContainer.style.display = 'flex';
                fallbackInput.style.display = 'none';
                toggleText.textContent = 'Having trouble? Use single input instead';
                currentInputMethod = 'digits';
                digitInputs[0].focus();
            }
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timer.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    timerDisplay.innerHTML = '<i class="fas fa-hourglass-end"></i> Code has expired';
                    timerDisplay.classList.add('bg-red-900', 'border-red-700', 'text-red-200');
                    document.getElementById('verifyBtn').disabled = true;
                    resendBtn.disabled = false;
                }
                timeLeft--;
            }, 1000);
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const otp = fallbackInput.value;
            
            if (otp.length !== 6) {
                showError('Please enter a valid 6-digit OTP');
                return;
            }
            
            // Add loading state
            const verifyBtn = document.getElementById('verifyBtn');
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
            verifyBtn.disabled = true;
            
            // Submit form
            setTimeout(() => {
                this.submit();
            }, 1000);
        });

        // Resend functionality
        resendBtn.addEventListener('click', function() {
            showSuccess('New OTP sent to your email!');
            timeLeft = 600;
            timerDisplay.classList.remove('bg-red-900', 'border-red-700', 'text-red-200');
            timerDisplay.innerHTML = '<i class="fas fa-hourglass-half"></i> Code expires in <span id="timer">10:00</span>';
            startTimer();
            this.disabled = true;
            setTimeout(() => {
                this.disabled = false;
            }, 30000); // 30 seconds cooldown
        });

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }

        function showSuccess(message) {
            successMessage.textContent = message;
            successMessage.style.display = 'block';
            errorMessage.style.display = 'none';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 5000);
        }
    </script>
</body>
</html>