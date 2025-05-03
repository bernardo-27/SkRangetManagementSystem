<x-guest-layout>


    <div class="login-container">
        <div class="login-box">
            <!-- Session Status -->
            <x-auth-session-status :status="session('status')" />

            <h2 class="login-title">Welcome Back</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <!-- Remember Me -->
                <div class="remember-me">
                    <label for="remember_me">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <!-- Forgot Password & Buttons -->
                <div class="form-footer">
                    @if (Route::has('password.request'))
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Forgot your password?
                        </a>
                    @endif

                    <div class="button-group">
                        <button type="submit" class="login-btn">Log in</button>
                        I don't have an Account?
                        <a class="text-m text-red-600 hover:text-gray-900"
                                href="{{ route('register') }}">
                            {{ __(' Register') }}
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>



    <!-- CSS -->
<style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: url("/images/SK's.png");
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        font-family: 'Arial', sans-serif;
    }

    /* Blurred Overlay */
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        backdrop-filter: blur(5px); /* Adjust the blur intensity */
        -webkit-backdrop-filter: blur(5x); /* Safari support */
        background: rgba(0, 0, 0, 0.3); /* Dark overlay for better contrast */
    }

    /* Centering Login Box */
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        position: relative; /* Ensures it sits above the blur */
        z-index: 1;
    }

    /* Frosted Glass Effect for Login Box */
    .login-box {
        padding: 2rem;
        border-radius: 10px;
        width: 350px;
        text-align: center;
        background: rgb(255, 255, 255);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(7, 7, 7, 0.3);
    }

    /* Styling for Title */
    .login-title {
        font-size: 24px;
        font-weight: bold;
        color: #000000;
        margin-bottom: 20px;
    }

    /* Input Fields */
    .form-group {
        margin-bottom: 15px;
        text-align: left;
    }

    .input-field {
        width: 100%;
        padding: 10px;
        border: 1px solid rgba(0, 0, 0, 0.5);
        border-radius: 6px;
        font-size: 16px;
        outline: none;
        background: rgba(255, 255, 255, 0.3);
        color: #000000;
        transition: border-color 0.3s ease-in-out;
    }

    .input-field::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .input-field:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 5px rgba(79, 70, 229, 0.3);
    }

    /* Error Messages */
    .error-message {
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }

    /* Remember Me */
    .remember-me {
        text-align: left;
        margin-bottom: 15px;
    }

    .remember-me label {
        font-size: 14px;
        color: #000000;
    }

    /* Forgot Password */
    .forgot-password {
        font-size: 14px;
        color: #0004ff;
        text-decoration: none;
        transition: color 0.3s ease-in-out;
    }

    .forgot-password:hover {
        color: #bd0000;
    }

    /* Buttons */
    .button-group {
        margin-top: 20px;
    }

    .login-btn {
        margin: 0 0 10px 0;
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .login-btn {
        background: #4F46E5;
        color: #fff;
    }

    .login-btn:hover {
        background: #3730A3;
    }




</style>
</x-guest-layout>
