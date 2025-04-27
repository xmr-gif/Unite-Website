<?php
require_once 'user.php'; // Include your User class
require_once 'config.php'; // Include database configuration
//dddddddd


$errors = [];
$_POST = array_map(fn($v) => $v ?? '', $_POST);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create LoginUser instance
        $loginUser = new LoginUser($_POST);

        // Validate form
        $errors = $loginUser->validateForm();

        // If no validation errors, try authentication
        if (empty($errors)) {
            if ($loginUser->authenticate()) {
                // Redirect on success
                echo"success" ; 
                //header('Location: dashboard.php'); // Adjust the path as needed
                //exit();
            }
            // Get authentication errors
            $errors = $loginUser->getErrors();
        }
    } catch (Exception $e) {
        $errors['database'] = 'System error: ' . $e->getMessage();
    }
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
                <a href="../Home page/index.html" ><img src="../images/black-logo.png" class=" w-20" alt="company logo"></a>

            </div>
            <div class="hidden space-x-6" >
                <a class="ml-5 hover:text-indigo-600 " href="#">About us</a>
                <a class="ml-5 hover:text-indigo-600" href="#">Blog</a>
                <a class="ml-5 hover:text-indigo-600" href="#">Contact</a>
                <button class="ml-5 border-1 px-3 py-1 rounded-3xl hover:text-white hover:bg-black cursor-pointer " id="loginButton" >Login</button>

                </div>

            <button id="mobile-btn" class="hidden cursor-pointer">
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

         <section  id="firstSection" class="flex justify-center items-center py-12 md:py-24" >
             <div class="flex w-full flex-col md:flex-row md:w-3/4  rounded-lg " >

         <div class="w-1/2 hidden md:block px-10 py-10 " >
             <img src="211.-Coffee.png" alt="Teacher"   >
         </div>
         <div class="px-10 py-5" >


             <p class="font-bold text-2xl mb-5 text-gray-700" >Sign in to your Account</p>
             <form action="" method="post"    >
                     <?php if(isset($errors['database'])): ?>
                         <div class="error text-red-500 text-xs mb-4">
                             <?php echo $errors['database'] ?>
                         </div>
                     <?php endif; ?>
                 <div class="flex-col "  >
                     <div class="flex-col gap-10" >

                 <div class="input flex flex-col w-full static group">
                             <label
                                 for="AccountType"
                                 class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                 > Account Type </label
                             >
                             <select
                                 name="AccountType"
                                 id=""
                                 class="input  rounded-[5px] w-[460px]  placeholder:text-black/25
                                 text-gray-300  focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2  focus:outline-none"
                                 >
                                 <option value="student">Student</option>
                                 <option value="professor">Professor</option>
                             </select>
                             <div class="text-xs text-red-500">
                                 <?php echo $errors['AccountType'] ?? '' ?>
                             </div>
                         </div>

                 <div class="input flex flex-col  md:w-full static group">
                             <label
                                 for="email"
                                 class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                 > Email </label
                             >
                             <input
                                 required
                                 id="email"
                                 value="<?php echo htmlspecialchars($_POST['email'] ?? '') ?>"
                                 pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
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

                             <div class="input flex flex-col  md:w-full static group ">
                             <label
                                 for="ddd"
                                 class="text-gray-400 group-focus-within:text-blue-500 js-changeColor text-xs font-semibold relative top-2 ml-[20px] px-[5px] bg-white w-fit transition-colors z-10"
                                 > Password </label
                             >
                             <input
                                 required
                                 id="password"
                                 type="password"
                                 value="<?php echo htmlspecialchars($_POST['password'] ?? '') ?>"
                                 placeholder="Write here..."
                                 name="password"
                                 class=" input  rounded-[5px] w-[460px]  placeholder:text-black/25
                                 text-gray-300  focus:text-blue-400 input px-[10px] py-[11px] text-xs bg-white border-2  focus:outline-none"
                             />
                             <div class="flex justify-end" >
                                 <a  href="#" class="text-xs text-gray-500 hover:text-blue-500 hover:underline mt-2 mb-4" >Forgot Password?</a>
                             </div>
                             <div class="text-xs text-red-500">
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




                     <button class="bg-indigo-500 hover:bg-indigo-400 text-white py-2 mt-4 rounded-[5px] w-[350px] md:w-[460px] cursor-pointer text-base" >Login</button>
                     </div>

             </form>

             <p class="text-xs text-gray-500 mt-2 mb-4" >Don't have an account ? <a href="../SignupPage/index.php" class="text-blue-500 hover:underline" >Register Now</a></p>



         </div>
         </div>

     </section>





</body>
</html>
