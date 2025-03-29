<?php
    require('user.php') ;
    if(isset($_POST['firstname'])){
        $validation = new User($_POST) ;
        $errors = $validation->validateForm();

        // if errors is empty = save into db
    }

?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="container mx-auto py-3 px-22 "  >
        <div class="flex items-center justify-between" >
            <div>
                <span><img src="../images/black-logo.png" class=" w-20" alt="company logo"></span>

            </div>
            <div class="hidden md:flex space-x-6" >
                <a class="ml-5 hover:text-indigo-600 " href="#">About us</a>
                <a class="ml-5 hover:text-indigo-600" href="#">Blog</a>
                <a class="ml-5 hover:text-indigo-600" href="">Contact</a>
                <button class="ml-5 border-1 px-3 py-1 rounded-3xl hover:text-white hover:bg-black cursor-pointer " id="loginButton" >Login</button>

                <!-- <a class="ml-5 border-1 px-3 py-1 rounded-3xl bg-black text-white " href="">Get Started</a> -->
            </div>

            <button id="mobile-btn" class="md:hidden cursor-pointer">
                <i class="ri-menu-line text-2xl"></i>
            </button>

        </div>

            <div class="md:hidden">
                <div id="mobile-menu" class="absolute flex hidden flex-col items-center space-y-4 font-bold bg-gray-50 py-8 left-6 right-6 drop-shadow-lg border-gray-300  ">
                    <a href="#">About us</a>
                    <a href="#">Blog</a>
                    <a href="">Contact</a>
                    <a class=" border-1 px-3 py-1 rounded-3xl hover:text-white hover:bg-black " href="">Login</a>

                </div>
            </div>

       </nav>

       <section  id="firstSection" >
            <div class="flex flex-col items-center text-center p-20" >
                <h1 class="font-bold text-xl" >Please select your account type </h1>
                <div class="p-10 md:p-20 flex gap-10 " >
                    <div class="border-1 border-gray-300 w-40 h-40 px-10 py-10 cursor-pointer hover:border-blue-500 hover:border-2 " id="signupProfessor" >
                        <p class="text-7xl mb-2" >👨🏻‍🏫</p>
                        <p class="text-zinc-700" >Professor</p>
                    </div>

                    <div class="border-1 border-gray-300 w-40 h-40 px-10 py-10 cursor-pointer hover:border-blue-500 hover:border-2 " >
                        <p class="text-7xl mb-2" >👨🏻‍🎓</p>
                        <p class="text-zinc-700" >Student</p>
                    </div>

                </div>

            </div>

            <div>

            </div>

       </section>

       <section class="flex justify-center py-10 hidden " >

        <div class="flex w-3/4 shadow-sm rounded-lg " >

            <div class="w-1/2 px-10 py-10 " >
                <img src="268.Vocabulary.png" alt="Teacher"  >
            </div>
            <div class="px-10 py-5" >
                <div class="flex justify-end "   >
                <button type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 cursor-pointer" id="closeButton">
                    <span class="sr-only">Close menu</span>
                    <!-- Heroicon name: outline/x -->
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    </button>
                </div>

                <p class="font-bold text-2xl mb-5 text-gray-700" >Create Account</p>
                <form action="" method="post"   >
                    <?php if(isset($errors) && is_array($errors) && count($errors) > 0) : ?>
                        <div class="error" >
                            <?php foreach ($errors as $error) :?>
                            <?=$error?>
                            <?php endforeach;?>
                        </div>
                        <?php endif;?>
                    <div class="flex-col "  >
                        <div class="flex gap-10" >
                            <div class="input flex flex-col w-fit static group">
                            <label
                                for="uuu"
                                class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                > First Name </label>
                            <input
                                required
                                pattern="[A-Za-z]"
                                maxlength="20"
                                value="<?php echo htmlspecialchars($_POST['firstname']) ?? '' ?>"
                                id="uuu"
                                type="text"
                                placeholder="Write here..."
                                name="firstname"
                                class="text-gray-300 focus:border-blue-500 focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2 rounded-[5px] w-[210px] focus:outline-none placeholder:text-black/25"
                            />
                            <div class="text-xs text-red-500" >
                                <?php echo $errors['firstname'] ?? '' ?>
                            </div>
                            </div>
<!-- begin -->
                            <div class="input flex flex-col w-fit static group">
                            <label
                                for="text"
                                class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                > Last Name </label
                            >
                            <input
                                required
                                id="text"
                                pattern="[A-Za-z]"
                                maxlength="20"
                                value="<?php echo htmlspecialchars($_POST['lastname']) ?? '' ?>"
                                type="text"
                                placeholder="Write here..."
                                name="lastname"
                                class="text-gray-300 focus:border-blue-500 focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2 rounded-[5px] w-[210px] focus:outline-none placeholder:text-black/25"
                            />
                            <div class="text-xs text-red-500" >
                                <?php echo $errors['lastname'] ?? '' ?>
                            </div>
                            </div>
<!-- end -->

                        </div>
                        <div class="input flex flex-col w-full static group">
                            <label
                                for="email"
                                class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                > Email </label
                            >
                            <input
                                required
                                id="email"
                                value="<?php echo htmlspecialchars($_POST['email']) ?? '' ?>"
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2;4}$"
                                type="email"
                                placeholder="Write here..."
                                name="email"
                                class="input  rounded-[5px] w-[460px]  placeholder:text-black/25
                                text-gray-300  focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2  focus:outline-none"
                            />
                            <div class="text-xs text-red-500" >
                                <?php echo $errors['email'] ?? '' ?>
                            </div>
                            </div>

                            <div class="input flex flex-col w-full static group ">
                            <label
                                for="ddd"
                                class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                > Password </label
                            >
                            <input
                                required
                                id="password"
                                type="password"
                                value="<?php echo htmlspecialchars($_POST['password']) ?? '' ?>"
                                placeholder="Write here..."
                                name="password"
                                class=" input  rounded-[5px] w-[460px]  placeholder:text-black/25
                                text-gray-300  focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2  focus:outline-none"
                            />
                            <div class="text-xs text-red-500" >
                                <?php echo $errors['password'] ?? '' ?>
                            </div>
                            </div>
                        <div>

                        </div>

                        <style>
.validation-item {
    --icon-color: #ef4444; /* Red-500 */
    --icon-content: '✖';
    position: relative;
    margin-left: 1.5rem;
}

.validation-item::before {
    content: var(--icon-content);
    color: var(--icon-color);
    position: absolute;
    left: -1.5rem;
    font-weight: bold;
    font-family: Arial, sans-serif; /* Ensure symbol support */
}

.validation-item.valid {
    --icon-color: #10b981; /* Green-500 */
    --icon-content: '✓';
}

.validation-item.valid::before {
    animation: check-pop 0.2s ease;
}

@keyframes check-pop {
    0% { transform: scale(0.8); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
</style>

<!-- Validation Message -->
<div id="message" class="mt-3 hidden">
    <h3 class="text-gray-600 mb-3 font-medium">Password must contain the following:</h3>
    <p data-validation="lowercase" class="validation-item">A <b>lowercase</b> letter</p>
    <p data-validation="uppercase" class="validation-item">A <b>capital (uppercase)</b> letter</p>
    <p data-validation="number" class="validation-item">A <b>number</b></p>
    <p data-validation="length" class="validation-item">Minimum <b>8 characters</b></p>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const messageDiv = document.getElementById('message');

    const validationChecks = {
        lowercase: (pw) => /[a-z]/.test(pw),
        uppercase: (pw) => /[A-Z]/.test(pw),
        number: (pw) => /\d/.test(pw),
        length: (pw) => pw.length >= 8
    };

    function validatePassword() {
        const password = passwordInput.value;
        messageDiv.classList.toggle('hidden', password === '');

        document.querySelectorAll('.validation-item').forEach((item, index) => {
            const isValid = Object.values(validationChecks)[index](password);
            item.classList.toggle('valid', isValid);
        });
    }

    passwordInput.addEventListener('input', validatePassword);
});
</script>


                      <button class="bg-indigo-500 hover:bg-indigo-400 text-white py-2 mt-4 rounded-[5px] w-[460px] cursor-pointer text-base" >Create Account</button>
                    </div>

                </form>
                <p class="text-xs text-gray-500 mt-2 mb-4" >Already have an account ? <a href="#" class="text-blue-500" >Login</a></p>



            </div>
        </div>
       </section>


   <script src="../js/script.js" ></script>
   <script src="script.js"></script>

</body>
</html>
