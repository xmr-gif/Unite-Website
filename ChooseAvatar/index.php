<?php
session_start();

// Debug: Print session data
// echo "<pre>Session Data:\n";
// print_r($_SESSION);
// echo "</pre>";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_avatar'])) {
    $avatar_number = $_POST['selected_avatar'];

    // Check if essential session data exists
    if (
        isset($_SESSION['account_type'], $_SESSION[$_SESSION['account_type'] . '_id'])
    ) {
        $account_type = $_SESSION['account_type'];
        $user_id = $_SESSION[$account_type . '_id'];
        echo "id is = $user_id";

        // Define table and column names (case-sensitive)
        $table = ($account_type === 'Professeur') ? 'Professeur' : 'Etudiant';
        $id_column = ($account_type === 'Professeur') ? 'ID_Professeur' : 'ID_Etudiant';
        $avatar_column = 'Avatar';

        try {
            // Database connection
            $host = 'localhost';
            $db = 'unite_db';
            $user = 'root';
            $pass = '';
            $pdo = new PDO("mysql:host=$host;port=3307;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Update the avatar
            $sql = "UPDATE $table SET $avatar_column = :avatar WHERE $id_column = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':avatar', $avatar_number);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                // Redirect to dashboard
                $dashboard = ($account_type === 'professeur')
                    ? '../Dashboard/dashboardProf.php'
                    : '../Dashboard/dashboardEt.php';
                header("Location: $dashboard");
                exit();
            } else {
                die("Error updating avatar: " . implode(", ", $stmt->errorInfo()));
            }

        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

    } else {
        die("Essential user information or User ID not found in session.");
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
                             data-avatar="2">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/3.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="3">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/4.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="4">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/5.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="5">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/8.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="8">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/7.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="7">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <div class="avatar-item relative group">
                        <img src="Avatars/6.png"
                             class="w-full h-auto rounded-full cursor-pointer transform transition-all duration-300
                                    hover:scale-105 hover:ring-4 hover:ring-indigo-100"
                             data-avatar="6">
                        <div class="absolute inset-0 ring-2 ring-indigo-500 rounded-full scale-105 opacity-0
                                    transition-all duration-300 pointer-events-none"></div>
                    </div>
                    <!-- Repeat for other avatars (2-8) -->
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4">

                <form action="" method="post">
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

const avatarItems = document.querySelectorAll('.avatar-item');
const continueBtn = document.getElementById('continueBtn');
const selectedAvatarInput = document.getElementById('selected_avatar'); // Get the input field
let selectedAvatar = null;

avatarItems.forEach(item => {
    item.addEventListener('click', () => {
        // ... (visual selection logic) ...

        if(selectedAvatar) {
                    selectedAvatar.querySelector('img').classList.remove('scale-105', 'ring-indigo-100');
                    selectedAvatar.querySelector('div').classList.remove('opacity-100');
                }

        item.querySelector('img').classList.add('scale-105', 'ring-indigo-100');
        item.querySelector('div').classList.add('opacity-100');

        selectedAvatar = item;
        const avatarNumber = selectedAvatar.querySelector('img').getAttribute('data-avatar');
        selectedAvatarInput.value = avatarNumber; // Set the value of the hidden input
        console.log('Selected Avatar:', avatarNumber);

        continueBtn.disabled = false;
    });
});

    </script>
</body>
</html>
