<x-guest-layout>
    <div class="register-container">
        <div class="register-box">
            <h2 class="register-title">Create an Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="input-field" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="error-message" />
                </div>

                <!-- Email Address -->
                <div class="form-group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="input-field" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="error-message" />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="input-field" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="error-message" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="input-field" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="error-message" />
                </div>


                <!-- Register Button -->
                <div class="button-group">
                    <button type="submit" class="register-btn">Register</button>
                </div>

                <!-- Already Registered -->
                <div class="form-footer mt-4">
                    Already registered?
                    <a class="already-registered" href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        /* General Page Styling */
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
            overflow: hidden;
        }

        /* Blurred Background Overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            background: rgba(0, 0, 0, 0.3);
        }

        /* Centering Register Box */
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Frosted Glass Effect */
        .register-box {
            padding: 3rem 3rem 1rem 3rem;
            border-radius: 10px;
            width: 350px;
            text-align: center;
            background: rgb(255, 255, 255);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(7, 7, 7, 0.3);
        }

        /* Styling for Title */
        .register-title {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            margin: -30px 20px auto;
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
            border-color: #3c34e0;
            box-shadow: 0 0 5px rgba(79, 70, 229, 0.5);
        }

        /* Error Messages */
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        /* Already Registered */
        .already-registered {
            font-style: italic;
            font-size: 17px;
            font-weight: bold;
            color: #2200ff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .already-registered:hover {
            color: #ff0000;
        }

        /* Register Button */
        .button-group {
            margin-top: 20px;
        }

        .register-btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            background: #4F46E5;
            color: #fff;
        }

        .register-btn:hover {
            background: #3730A3;
        }
    </style>
</x-guest-layout>
