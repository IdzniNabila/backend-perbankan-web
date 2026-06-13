const LoginPage = {
    requiresAuth: false,

    render() {
        return `
            <style>
                .login-container {
                    display: flex;
                    height: 100vh;
                    align-items: center;
                    justify-content: center;
                    background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                }

                .login-box {
                    background: white;
                    border-radius: 12px;
                    padding: 40px;
                    width: 100%;
                    max-width: 400px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                }

                .login-header {
                    text-align: center;
                    margin-bottom: 30px;
                }

                .login-icon {
                    width: 60px;
                    height: 60px;
                    background: linear-gradient(135deg, #3b82f6, #1e40af);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 32px;
                    margin: 0 auto 15px;
                }

                .login-title {
                    font-size: 28px;
                    font-weight: 700;
                    color: #1f2937;
                    margin: 0;
                }

                .login-subtitle {
                    color: #6b7280;
                    font-size: 14px;
                    margin-top: 5px;
                }

                .login-form .form-group {
                    margin-bottom: 20px;
                }

                .login-form .form-label {
                    display: block;
                    font-weight: 600;
                    color: #1f2937;
                    margin-bottom: 8px;
                    font-size: 14px;
                }

                .login-form .form-input {
                    width: 100%;
                    padding: 12px;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 14px;
                    transition: all 0.3s ease;
                }

                .login-form .form-input:focus {
                    outline: none;
                    border-color: #3b82f6;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
                }

                .login-alert {
                    padding: 12px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                    background: rgba(239, 68, 68, 0.1);
                    color: #ef4444;
                    border: 1px solid #ef4444;
                    font-size: 14px;
                    display: none;
                }

                .login-alert.show {
                    display: block;
                }

                .login-btn {
                    width: 100%;
                    padding: 12px;
                    background: linear-gradient(135deg, #3b82f6, #1e40af);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    margin-top: 10px;
                }

                .login-btn:hover:not(:disabled) {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
                }

                .login-btn:disabled {
                    opacity: 0.7;
                    cursor: not-allowed;
                }

                .login-footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #6b7280;
                }

                .demo-creds {
                    background: #f9fafb;
                    padding: 12px;
                    border-radius: 8px;
                    margin-top: 15px;
                    font-size: 13px;
                    color: #6b7280;
                    line-height: 1.6;
                }

                .demo-creds strong {
                    color: #1f2937;
                }
            </style>

            <div class="login-container">
                <div class="login-box">
                    <div class="login-header">
                        <div class="login-icon">🏦</div>
                        <h1 class="login-title">Bank System</h1>
                        <p class="login-subtitle">Sistem Manajemen Perbankan</p>
                    </div>

                    <div class="login-alert" id="login-alert"></div>

                    <form class="login-form" id="login-form">
                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input 
                                type="text" 
                                id="username" 
                                class="form-input" 
                                placeholder="Masukkan username"
                                required
                                autocomplete="username"
                            >
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input 
                                type="password" 
                                id="password" 
                                class="form-input" 
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            >
                        </div>

                        <button type="submit" class="login-btn" id="login-btn">
                            Login
                        </button>
                    </form>

                    <div class="demo-creds">
                        <strong>Demo Credentials:</strong><br>
                        Username: admin<br>
                        Password: password
                    </div>

                    <div class="login-footer">
                        © 2024 Banking System. All rights reserved.
                    </div>
                </div>
            </div>
        `;
    },

    init() {
        const form = document.getElementById('login-form');
        const alertBox = document.getElementById('login-alert');
        const loginBtn = document.getElementById('login-btn');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            loginBtn.disabled = true;
            loginBtn.textContent = 'Logging in...';

            try {
                await auth.login(username, password);
                window.location.href = '/app.html?page=dashboard';
            } catch (error) {
                alertBox.textContent = error.message || 'Login failed';
                alertBox.classList.add('show');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login';
            }
        });
    }
};

router.register('login', LoginPage);
