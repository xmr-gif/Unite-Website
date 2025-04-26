<!doctype html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-gray-50">
    <nav class="container mx-auto py-3 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <a href="../Home page/index.html">
                <img src="../images/black-logo.png" class="w-20" alt="company logo">
            </a>
        </div>
    </nav>

    <section class="min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full mx-4 flex items-center justify-center">
            <div class="bg-white rounded-2xl shadow-lg p-8 sm:p-12">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Choose Your Avatar</h1>
                    <p class="text-gray-600">Select an avatar that represents you best</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
                    <!-- Avatar Items -->
                    <div class="avatar-item relative group">
                        <img src="Avatars/1.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/2.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/3.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/4.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/5.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/8.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/7.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/6.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="1">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <!-- Repeat for other avatars (2-8) -->
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4">

                <form action="update_avatar.php" method="post">
                    <input type="hidden" id="selected_avatar" name="selected_avatar" value="">
                    <button class="w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white rounded-lg
                            hover:bg-indigo-700 transition-colors duration-300 disabled:opacity-50
                            disabled:cursor-not-allowed cursor-pointer"
                            id="continueBtn"
                            disabled>
                        Continue to Dashboard
                    </button>
                </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Avatar Selection Logic
        const avatarItems = document.querySelectorAll('.avatar-item');
        const continueBtn = document.getElementById('continueBtn');
        let selectedAvatar = null;

        avatarItems.forEach(item => {
            item.addEventListener('click', () => {
                // Remove previous selection
                if(selectedAvatar) {
                    selectedAvatar.querySelector('img').classList.remove('scale-105', 'ring-indigo-100');
                    selectedAvatar.querySelector('div').classList.remove('opacity-100');
                }

                // Set new selection
                item.querySelector('img').classList.add('scale-105', 'ring-indigo-100');
                item.querySelector('div').classList.add('opacity-100');
                selectedAvatar = item;

                // Enable continue button
                continueBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
