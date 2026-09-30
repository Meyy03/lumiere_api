<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lumière | Admin Login</title>

    <link rel="stylesheet" href="{{ asset('css/admin/login.css') }}">

</head>


<body>

    <div class="login-page">


        {{-- =====================================================
        LEFT BRAND PANEL
        ===================================================== --}}

        <section class="brand-panel">

            <div class="brand-content">


                {{-- LOGO --}}

                <div class="logo-circle">

                    <img src="{{ asset('images/admin/lumiere-logo.png') }}" alt="Lumière Logo" class="brand-logo-image"
                        onerror="
                        this.style.display='none';
                        document.getElementById('logoFallback').style.display='block';
                    ">


                    <span id="logoFallback" class="brand-logo-fallback" style="display: none;">
                        L
                    </span>

                </div>


                <h1 class="brand-name">
                    LUMIÈRE
                </h1>


                <p class="brand-type">
                    ADMIN SYSTEM
                </p>


                <div class="brand-line"></div>


                <p class="brand-description">
                    Cosmetic &amp; Skincare E-Commerce
                    Management System
                </p>


                <div class="brand-features">

                    <span>Products</span>

                    <span>Orders</span>

                    <span>Customers</span>

                    <span>Reports</span>

                </div>

            </div>

        </section>


        {{-- =====================================================
        RIGHT LOGIN PANEL
        ===================================================== --}}

        <section class="login-panel">

            <div class="login-container">


                <p class="small-title">
                    ADMINISTRATOR PORTAL
                </p>


                <h2>
                    Welcome Back
                </h2>


                <p class="subtitle">
                    Sign in to your administrator account
                    to manage the Lumière system.
                </p>


                {{-- =================================================
                ORIGINAL SUCCESS MESSAGE
                ================================================= --}}

                @if(session('success'))

                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>

                @endif


                {{-- =================================================
                ORIGINAL INVALID LOGIN MESSAGE
                ================================================= --}}

                @error('login')

                    <div class="alert alert-error">
                        {{ $message }}
                    </div>

                @enderror


                {{-- =================================================
                LOGIN FORM
                ================================================= --}}

                <form method="POST" action="{{ route('admin.login.submit') }}" id="adminLoginForm" autocomplete="off"
                    novalidate>

                    @csrf


                    {{-- =================================================
                    EMAIL ADDRESS
                    ================================================= --}}

                    <div class="form-group">

                        <label for="email">
                            Email Address
                        </label>


                        <div class="input-wrapper">

                            <input type="email" id="email" name="email" class="form-control" value=""
                                placeholder="Enter your email address" maxlength="254" autocomplete="off"
                                autocapitalize="none" spellcheck="false" data-lpignore="true" data-1p-ignore="true"
                                data-form-type="other" readonly>


                            {{-- CLEAR EMAIL ICON --}}

                            <button type="button" id="clearEmailButton" class="input-action-button clear-email-button"
                                aria-label="Clear email" title="Clear email">

                                <svg viewBox="0 0 24 24" aria-hidden="true">

                                    <path d="M18 6L6 18"></path>

                                    <path d="M6 6L18 18"></path>

                                </svg>

                            </button>

                        </div>


                        <p class="field-error" id="emailError"></p>

                    </div>


                    {{-- =================================================
                    PASSWORD
                    ================================================= --}}

                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>


                        <div class="password-wrapper">

                            <input type="password" id="password" name="password" class="form-control" value=""
                                placeholder="Enter your password" maxlength="64" autocomplete="off"
                                autocapitalize="none" spellcheck="false" data-lpignore="true" data-1p-ignore="true"
                                data-form-type="other" readonly>


                            {{-- PASSWORD EYE ICON --}}

                            <button type="button" id="passwordToggle" class="input-action-button password-toggle-button"
                                aria-label="Show password" title="Show password">


                                {{-- EYE OPEN --}}

                                <svg id="eyeOpenIcon" viewBox="0 0 24 24" aria-hidden="true">

                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"></path>

                                    <circle cx="12" cy="12" r="3"></circle>

                                </svg>


                                {{-- EYE CLOSED --}}

                                <svg id="eyeClosedIcon" class="hidden-icon" viewBox="0 0 24 24" aria-hidden="true">

                                    <path d="M3 3l18 18"></path>

                                    <path d="M10.6 10.7a2 2 0 0 0 2.7 2.7"></path>

                                    <path d="M9.9 4.2A10.8 10.8 0 0 1 12 4c6.5 0 10 8 10 8a18 18 0 0 1-2.1 3.2"></path>

                                    <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 8 10 8c1.7 0 3.1-.5 4.4-1.2"></path>

                                </svg>

                            </button>

                        </div>


                        <p class="field-error" id="passwordError"></p>

                    </div>


                    {{-- =================================================
                    REMEMBER ME
                    ================================================= --}}

                    <div class="form-options">

                        <label class="remember">

                            <input type="checkbox" name="remember" id="rememberCheckbox" value="1">

                            <span>
                                Remember me
                            </span>

                        </label>

                    </div>


                    {{-- =================================================
                    LOGIN BUTTON
                    ================================================= --}}

                    <button type="submit" class="login-button">
                        Log In
                    </button>

                </form>


                {{-- =================================================
                SECURITY NOTE
                ================================================= --}}

                <div class="security-note">

                    <div class="security-icon">
                        ✓
                    </div>


                    <div>

                        <strong>
                            Secure Administrator Access
                        </strong>


                        <p>
                            Only authorized Lumière administrators
                            can access this portal.
                        </p>

                    </div>

                </div>


                {{-- =================================================
                FOOTER
                ================================================= --}}

                <footer class="login-footer">

                    © {{ date('Y') }} Lumière.
                    All rights reserved.

                </footer>

            </div>

        </section>

    </div>


    {{-- =========================================================
    SHARED LUMIÈRE MESSAGE MODAL
    ========================================================= --}}

    @include('admin.partials.confirm-modal')


    <script>

        // =========================================================
        // ELEMENTS
        // =========================================================

        const loginForm =
            document.getElementById(
                'adminLoginForm'
            );

        const emailInput =
            document.getElementById(
                'email'
            );

        const emailError =
            document.getElementById(
                'emailError'
            );

        const clearEmailButton =
            document.getElementById(
                'clearEmailButton'
            );

        const passwordInput =
            document.getElementById(
                'password'
            );

        const passwordError =
            document.getElementById(
                'passwordError'
            );

        const passwordToggle =
            document.getElementById(
                'passwordToggle'
            );

        const eyeOpenIcon =
            document.getElementById(
                'eyeOpenIcon'
            );

        const eyeClosedIcon =
            document.getElementById(
                'eyeClosedIcon'
            );

        const rememberCheckbox =
            document.getElementById(
                'rememberCheckbox'
            );


        function unlockEmail() {

            emailInput.removeAttribute(
                'readonly'
            );

        }


        function unlockPassword() {

            passwordInput.removeAttribute(
                'readonly'
            );

        }


        emailInput.addEventListener(
            'focus',
            unlockEmail
        );


        emailInput.addEventListener(
            'click',
            unlockEmail
        );


        passwordInput.addEventListener(
            'focus',
            unlockPassword
        );


        passwordInput.addEventListener(
            'click',
            unlockPassword
        );


        // =========================================================
        // EMAIL VALIDATION
        // =========================================================

        function validateEmail() {

            const email =
                emailInput.value.trim();


            const emailPattern =
                /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;


            if (email === '') {

                emailError.textContent =
                    'Please enter your email address.';


                emailInput.classList.add(
                    'input-error'
                );


                return false;

            }


            if (
                !emailPattern.test(email)
            ) {

                emailError.textContent =
                    'Enter a complete email address, e.g. admin@example.com.';


                emailInput.classList.add(
                    'input-error'
                );


                return false;

            }


            emailError.textContent =
                '';


            emailInput.classList.remove(
                'input-error'
            );


            return true;

        }


        // =========================================================
        // PASSWORD VALIDATION
        // =========================================================

        function validatePassword() {

            const password =
                passwordInput.value;


            /*
             * Requirements:
             *
             * Minimum 8 characters
             * Maximum 64 characters
             * At least 1 uppercase letter
             * At least 1 lowercase letter
             * At least 1 number
             * At least 1 symbol
             * No spaces
             */

            const passwordPattern =
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])\S{8,64}$/;


            if (
                password === ''
            ) {

                passwordError.textContent =
                    'Please enter your password.';


                passwordInput.classList.add(
                    'input-error'
                );


                return false;

            }


            if (
                !passwordPattern.test(
                    password
                )
            ) {

                passwordError.textContent =
                    'Use 8+ characters with uppercase, lowercase, number and symbol, with no spaces.';


                passwordInput.classList.add(
                    'input-error'
                );


                return false;

            }


            passwordError.textContent =
                '';


            passwordInput.classList.remove(
                'input-error'
            );


            return true;

        }


        // =========================================================
        // EMAIL CLEAR ICON
        // =========================================================

        function updateClearEmailButton() {

            if (
                emailInput
                    .value
                    .trim()
                    .length > 0
            ) {

                clearEmailButton
                    .classList
                    .add(
                        'active'
                    );

            } else {

                clearEmailButton
                    .classList
                    .remove(
                        'active'
                    );

            }

        }


        clearEmailButton.addEventListener(
            'click',
            function () {

                unlockEmail();


                emailInput.value =
                    '';


                emailError.textContent =
                    '';


                emailInput.classList.remove(
                    'input-error'
                );


                updateClearEmailButton();


                emailInput.focus();

            }
        );


        // =========================================================
        // PASSWORD SHOW / HIDE
        // =========================================================

        passwordToggle.addEventListener(
            'click',
            function () {

                unlockPassword();


                const passwordIsHidden =
                    passwordInput.type ===
                    'password';


                passwordInput.type =
                    passwordIsHidden
                        ? 'text'
                        : 'password';


                if (
                    passwordIsHidden
                ) {

                    eyeOpenIcon.classList.add(
                        'hidden-icon'
                    );


                    eyeClosedIcon
                        .classList
                        .remove(
                            'hidden-icon'
                        );


                    passwordToggle
                        .setAttribute(
                            'aria-label',
                            'Hide password'
                        );


                    passwordToggle
                        .setAttribute(
                            'title',
                            'Hide password'
                        );

                } else {

                    eyeOpenIcon
                        .classList
                        .remove(
                            'hidden-icon'
                        );


                    eyeClosedIcon
                        .classList
                        .add(
                            'hidden-icon'
                        );


                    passwordToggle
                        .setAttribute(
                            'aria-label',
                            'Show password'
                        );


                    passwordToggle
                        .setAttribute(
                            'title',
                            'Show password'
                        );

                }


                passwordInput.focus();

            }
        );


        // =========================================================
        // EMAIL REAL-TIME VALIDATION
        // =========================================================

        emailInput.addEventListener(
            'input',
            function () {

                updateClearEmailButton();


                if (
                    emailInput
                        .value
                        .trim()
                        .length > 0
                ) {

                    validateEmail();

                } else {

                    emailError.textContent =
                        '';


                    emailInput
                        .classList
                        .remove(
                            'input-error'
                        );

                }

            }
        );


        emailInput.addEventListener(
            'blur',
            function () {

                if (
                    emailInput
                        .value
                        .trim()
                        .length > 0
                ) {

                    validateEmail();

                }

            }
        );


        // =========================================================
        // PASSWORD REAL-TIME VALIDATION
        // =========================================================

        passwordInput.addEventListener(
            'input',
            function () {

                if (
                    passwordInput
                        .value
                        .length > 0
                ) {

                    validatePassword();

                } else {

                    passwordError.textContent =
                        '';


                    passwordInput
                        .classList
                        .remove(
                            'input-error'
                        );

                }

            }
        );


        passwordInput.addEventListener(
            'blur',
            function () {

                if (
                    passwordInput
                        .value
                        .length > 0
                ) {

                    validatePassword();

                }

            }
        );


        // =========================================================
        // SUBMIT VALIDATION
        // =========================================================

        loginForm.addEventListener(
            'submit',
            function (event) {

                unlockEmail();

                unlockPassword();


                const emailIsValid =
                    validateEmail();


                const passwordIsValid =
                    validatePassword();


                if (
                    !emailIsValid ||
                    !passwordIsValid
                ) {

                    event.preventDefault();


                    if (
                        !emailIsValid
                    ) {

                        emailInput.focus();

                    } else {

                        passwordInput.focus();

                    }

                }

            }
        );


        // =========================================================
        // CLEAR LOGIN FIELDS
        // =========================================================

        function clearLoginFields() {

            emailInput.value =
                '';


            passwordInput.value =
                '';


            emailError.textContent =
                '';


            passwordError.textContent =
                '';


            emailInput.classList.remove(
                'input-error'
            );


            passwordInput.classList.remove(
                'input-error'
            );


            passwordInput.type =
                'password';


            eyeOpenIcon.classList.remove(
                'hidden-icon'
            );


            eyeClosedIcon.classList.add(
                'hidden-icon'
            );


            if (
                rememberCheckbox
            ) {

                rememberCheckbox.checked =
                    false;

            }


            updateClearEmailButton();

        }


        // =========================================================
        // LOGIN FAILED → TRY AGAIN
        // =========================================================

        window.addEventListener(
            'lumiere:clear-login',
            function () {

                clearLoginFields();


                unlockEmail();

                unlockPassword();


                setTimeout(
                    function () {

                        emailInput.focus();

                    },
                    100
                );

            }
        );


        // =========================================================
        // CLEAR FIELDS WHEN LOGIN PAGE OPENS
        // =========================================================

        window.addEventListener(
            'load',
            function () {

                clearLoginFields();

                setTimeout(
                    clearLoginFields,
                    100
                );


                setTimeout(
                    clearLoginFields,
                    500
                );

            }
        );


        // =========================================================
        // INITIAL STATE
        // =========================================================

        updateClearEmailButton();

    </script>

</body>

</html>