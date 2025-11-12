<?php
// PHP LOGIC: Sets the initial form state based on submission attempt (if user returns to this page)
session_start();

$form_mode = 'sign-in'; // Default mode

// Allow forcing mode via query param ?mode=signin|signup
if (!empty($_GET['mode'])) {
    $m = strtolower(trim($_GET['mode']));
    if ($m === 'signup' || $m === 'sign-up') $form_mode = 'sign-up';
    else $form_mode = 'sign-in';
} else {
    // Check if a form submission attempt mode was stored in the session
    if (isset($_SESSION['form_mode'])) {
        $form_mode = $_SESSION['form_mode'];
        unset($_SESSION['form_mode']); // Clear mode after retrieval
    }
}

// Set the initial class for the container
$initial_class = $form_mode;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In / Sign Up - Sliding Form (Powder Pink)</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        /* --- POWDER PINK COLOR SCHEME --- */
        :root {
            --primary-color: #F8B4C3; /* Powder Pink */
            --secondary-color: #FFC0CB; /* Slightly lighter Pink */
            --black: #000000;
            --white: #ffffff;
            --gray: #efefef;
            --gray-2: #757575;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
        }

        .container {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            height: 100vh;
            position: relative;
            z-index: 1;
        }

        .col {
            width: 50%;
        }

        .align-items-center {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 28rem;
        }

        .form {
            padding: 1rem;
            background-color: var(--white);
            border-radius: 1.5rem;
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transform: scale(0);
            transition: .5s ease-in-out;
            transition-delay: 1s;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin: 1rem 0;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            font-size: 1.4rem;
            color: var(--gray-2);
        }

        .input-group input {
            width: 100%;
            padding: 1rem 3rem;
            font-size: 1rem;
            background-color: var(--gray);
            border-radius: .5rem;
            border: 0.125rem solid var(--white);
            outline: none;
        }

        .input-group input:focus {
            border: 0.125rem solid var(--primary-color);
        }

        .form button {
            cursor: pointer;
            width: 100%;
            padding: .6rem 0;
            border-radius: .5rem;
            border: none;
            background-color: var(--primary-color);
            color: var(--white);
            font-size: 1.2rem;
            outline: none;
        }

        .form p {
            margin: 1rem 0;
            font-size: .9rem;
        }

        .flex-col {
            flex-direction: column;
        }

        .pointer {
            cursor: pointer;
            font-weight: 600;
        }

        /* --- Form Visibility (Scale In/Out) --- */
        .container.sign-in .form.sign-in,
        .container.sign-up .form.sign-up {
            transform: scale(1);
        }

        .content-row {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 6;
            width: 100%;
        }

        .text {
            margin: 4rem;
            color: var(--white);
        }

        .text h2 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 2rem 0;
            transition: 1s ease-in-out;
        }

        .text p {
            font-weight: 600;
            transition: 1s ease-in-out;
            transition-delay: .2s;
        }

        /* --- Content Sliding --- */
        .text.sign-in h2,
        .text.sign-in p,
        .img.sign-in {
            transform: translateX(-250%);
        }

        .text.sign-up h2,
        .text.sign-up p,
        .img.sign-up {
            transform: translateX(250%);
        }

        .container.sign-in .text.sign-in h2,
        .container.sign-in .text.sign-in p,
        .container.sign-in .img.sign-in,
        .container.sign-up .text.sign-up h2,
        .container.sign-up .text.sign-up p,
        .container.sign-up .img.sign-up {
            transform: translateX(0);
        }

        /* --- BACKGROUND SLIDING EFFECT (Powder Pink Banner) --- */

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            height: 100vh;
            width: 300vw;
            transform: translate(35%, 0); 
            background-image: linear-gradient(-45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: 1s ease-in-out;
            z-index: 0;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-bottom-right-radius: max(50vw, 50vh);
            border-top-left-radius: max(50vw, 50vh);
        }

        .container.sign-in::before {
            transform: translate(0, 0);
            right: 50%;
        }

        .container.sign-up::before {
            transform: translate(100%, 0);
            right: 50%;
        }

        /* --- RESPONSIVE FIXES --- */
        @media only screen and (max-width: 425px) {
            .container::before,
            .container.sign-in::before,
            .container.sign-up::before {
                height: 100vh;
                border-bottom-right-radius: 0;
                border-top-left-radius: 0;
                z-index: 0;
                transform: none;
                right: 0;
            }

            .col {
                width: 100%;
                position: absolute;
                padding: 2rem;
                background-color: var(--white);
                border-top-left-radius: 2rem;
                border-top-right-radius: 2rem;
                transform: translateY(100%);
                transition: 1s ease-in-out;
            }

            .container.sign-in .col.sign-in,
            .container.sign-up .col.sign-up {
                transform: translateY(0);
            }
            
            .content-row {
                align-items: flex-start !important;
            }

            .content-row .col {
                transform: translateY(0);
                background-color: unset;
            }

            .row {
                align-items: flex-end;
                justify-content: flex-end;
            }

            .form,
            .social-list {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .text {
                margin: 0;
            }

            .text p {
                display: none;
            }

            .text h2 {
                margin: .5rem;
                font-size: 2rem;
            }
        }
    </style>
</head>
<body onload="initializeFormMode()">

<div id="container" class="container <?php echo $initial_class; ?>">
    <?php
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!empty($_SESSION['errors'])) {
        echo '<div style="max-width:28rem;margin:1rem auto;color:#d93025;text-align:left;">';
        foreach ($_SESSION['errors'] as $err) {
            echo '<div>' . htmlspecialchars($err) . '</div>';
        }
        echo '</div>';
        unset($_SESSION['errors']);
    }
    ?>
    <div class="row">
        
        <div class="col align-items-center flex-col sign-up">
            <div class="form-wrapper align-items-center">
                <?php require_once __DIR__ . '/lib/auth.php'; $csrf = generate_csrf_token(); ?>
                <form id="signupForm" class="form sign-up" action="auth/register.php" method="POST">
                    <h2>Create Account</h2>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
                    <div class="input-group">
                        <i class='bx bxs-user'></i>
                        <input id="username_up" type="text" placeholder="Username" name="username_up" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-id-card'></i>
                        <input id="full_name_up" type="text" placeholder="Full name" name="full_name_up" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-phone'></i>
                        <input id="phone_up" type="text" placeholder="Phone number" name="phone_up">
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-gift'></i>
                        <select id="gender_up" name="gender_up" style="width:100%;padding:1rem 3rem;border-radius:.5rem;border:0.125rem solid var(--white);background-color:var(--gray);">
                            <option value="">Gender (optional)</option>
                            <option value="female">Female</option>
                            <option value="male">Male</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-cake'></i>
                        <input id="dob_up" type="date" placeholder="Date of birth" name="dob_up">
                    </div>
                    <div class="input-group">
                        <i class='bx bx-mail-send'></i>
                        <input id="email_up" type="email" placeholder="Email" name="email_up" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input id="password_up" type="password" placeholder="Password" name="password_up" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input id="password_confirm" type="password" placeholder="Confirm password" name="password_confirm" required>
                    </div>
                    <button type="submit" name="signup_submit">
                        Sign up
                    </button>
                    <p>
                        <span>
                            Already have an account?
                        </span>
                        <b onclick="toggle()" class="pointer">
                            Sign in here
                        </b>
                    </p>
                </form>
            </div>
        </div>
        <div class="col align-items-center flex-col sign-in">
            <div class="form-wrapper align-items-center">
                <form id="signinForm" class="form sign-in" action="auth/login.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
                    <h2>Sign in</h2>
                    <div class="input-group">
                        <i class='bx bxs-user'></i>
                        <input id="username_in" type="text" placeholder="Username" name="username_in" required>
                    </div>
                    <div class="input-group">
                        <i class='bx bxs-lock-alt'></i>
                        <input id="password_in" type="password" placeholder="Password" name="password_in" required>
                    </div>
                    <button type="submit" name="signin_submit">
                        Sign in
                    </button>
                    <div style="text-align:left;margin-top:.5rem;">
                        <label style="font-weight:400"><input type="checkbox" name="remember" value="1"> Remember me</label>
                    </div>
                    <p>
                        <b onclick="toggle()" class="pointer">
                         <p><a href="forgot_password.php">Forgot Password?</a></p>                        
                        </b>
                    </p>
                    <p>
                        <span>
                            Don't have an account?
                        </span>
                        <b onclick="toggle()" class="pointer">
                            Sign up here
                        </b>
                    </p>
                </form>
            </div>
            <div class="form-wrapper"></div>
        </div>
        </div>
    <div class="row content-row">
        
        <div class="col align-items-center flex-col">
            <div class="text sign-in">
                <h2>Welcome</h2>
                <p>Good to see you again on Janani</p>
            </div>
            <div class="img sign-in">
                </div>
        </div>
        <div class="col align-items-center flex-col">
            <div class="img sign-up">
                </div>
            <div class="text sign-up">
                <h2>Join with us</h2>
                <p>Enter your personal details and start your journey with us.</p>
            </div>
        </div>
        </div>
    </div>

<script>
    let container = document.getElementById('container');

    // Toggles between 'sign-in' and 'sign-up' classes, triggering the CSS transitions.
    toggle = () => {
        container.classList.toggle('sign-in');
        container.classList.toggle('sign-up');
    }
    
    // Function to initialize the form mode
    function initializeFormMode() {
        // Set the default mode to 'sign-in' on the very first load
        if (!container.classList.contains('sign-in') && !container.classList.contains('sign-up')) {
            setTimeout(() => {
                container.classList.add('sign-in');
            }, 200);
        }
        
        // Note: Status message display logic has been removed as the page navigates away.
    }
</script>

<!-- jQuery and jQuery Validate includes -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<style>
    /* Simple styles for validation messages */
    label.error {
        color: #d93025;
        font-size: 0.9rem;
        margin-top: 0.25rem;
        display: block;
    }
    input.error {
        border-color: #d93025 !important;
    }
</style>

<script>
    $(function(){
        // Sign-up form validation
        $("#signupForm").validate({
            rules: {
                username_up: { required: true, minlength: 3 },
                email_up: { required: true, email: true },
                password_up: { required: true, minlength: 6 },
                password_confirm: { required: true, equalTo: "#password_up" }
            },
            messages: {
                username_up: {
                    required: "Please enter a username",
                    minlength: "Username must be at least 3 characters"
                },
                email_up: {
                    required: "Please enter your email",
                    email: "Please enter a valid email address"
                },
                password_up: {
                    required: "Please provide a password",
                    minlength: "Password must be at least 4 characters"
                },
                password_confirm: {
                    required: "Please confirm your password",
                    equalTo: "Passwords do not match"
                }
            },
            errorPlacement: function(error, element) {
                // Place the error message after the input-group
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                // Optionally perform additional checks or show a loading state
                form.submit();
            }
        });

        // Sign-in form validation
        $("#signinForm").validate({
            rules: {
                username_in: { required: true },
                password_in: { required: true }
            },
            messages: {
                username_in: { required: "Please enter your username" },
                password_in: { required: "Please enter your password" }
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                // Store the redirect URL in session storage as a fallback
                sessionStorage.setItem('after_login_redirect', '/JANANI/4congratulations.php');
                form.submit();
            }
        });
    });
</script>

</body>
</html>