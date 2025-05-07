<?php
$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';

// Connect to database
try {
    $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
} catch (PDOException $e) {
    echo "La connexion n'est pas reussie ".$e->getMessage();
    exit;
}

// Get current user data - replace with your actual session logic
session_start();
$userEmail = $_SESSION['email'] ?? ''; // Replace with how you store the current user's email

$userRole = ''; // Will be set based on DB query
$userData = [];

// Get user data based on email
if(!empty($userEmail)) {
    // Check if user is a professor
    $sql = "SELECT * FROM professeur WHERE Email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $userEmail]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if($userData) {
        $userRole = 'Professor';
    } else {
        // Check if user is a student
        $sql = "SELECT * FROM etudiant WHERE Email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $userEmail]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if($userData) {
            $userRole = 'Student';
        }
    }
}

// Handle form submission
$updateMessage = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $avatar = $_POST['avatar'] ?? '';
    $additionalField = $_POST['additionalField'] ?? '';

    if(!empty($firstName) && !empty($lastName) && !empty($email)) {
        // Update the appropriate table based on user role
        if($userRole === 'Professor') {
            $sql = "UPDATE professeur SET Nom = :lastName, Prenom = :firstName, Email = :email, Avatar = :avatar WHERE Email = :currentEmail";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'lastName' => $lastName,
                'firstName' => $firstName,
                'email' => $email,
                'avatar' => $avatar,
                'currentEmail' => $userEmail
            ]);
        } else if($userRole === 'Student') {
            $sql = "UPDATE etudiant SET Nom = :lastName, Prenom = :firstName, Email = :email, Avatar = :avatar, Filiere_precedente = :additionalField WHERE Email = :currentEmail";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'lastName' => $lastName,
                'firstName' => $firstName,
                'email' => $email,
                'avatar' => $avatar,
                'additionalField' => $additionalField,
                'currentEmail' => $userEmail
            ]);
        }

        if($stmt->rowCount() > 0) {
            $updateMessage = 'Profile updated successfully!';
            // If email changed, update session
            if($email !== $userEmail) {
                $_SESSION['email'] = $email;
                $userEmail = $email;
            }
            // Refresh user data
            if($userRole === 'Professor') {
                $sql = "SELECT * FROM professeur WHERE Email = :email LIMIT 1";
            } else {
                $sql = "SELECT * FROM etudiant WHERE Email = :email LIMIT 1";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $userEmail]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $updateMessage = 'No changes made or update failed.';
        }
    } else {
        $updateMessage = 'First name, last name, and email are required.';
    }
}

// Available avatars - add your actual avatar filenames here
$availableAvatars = ['ChooseAvatar/Avatars/1.png', 'ChooseAvatar/Avatars/2.png', 'ChooseAvatar/Avatars/3.png', 'ChooseAvatar/Avatars/4.png','ChooseAvatar/Avatars/5.png','ChooseAvatar/Avatars/6.png','ChooseAvatar/Avatars/7.png','ChooseAvatar/Avatars/8.png'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>
    <title>Profile Settings</title>
    <style>
        .avatar-option {
            transition: all 0.2s ease;
        }
        .avatar-option.selected {
            border: 3px solid #4F46E5;
            transform: scale(1.05);
        }
        #successNotification {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="flex">
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
            <img src="../../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

            <div class="space-y-2">
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Dashboard/index.php">
                  <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                  Dashboard
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="users.php">
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Subjects/index.php">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Choices/index.html">
                  <i data-lucide="book-open" class="w-4 h-4"></i>
                  Choices
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="index.html">
                  <i data-lucide="file-text" class="w-4 h-4"></i>
                  Blogs
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="calendar.html">
                  <i data-lucide="users" class="w-4 h-4"></i>
                  Groups
                </a>
              </div>
            </div>

            <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <span class="flex items-center gap-2">
                  <i data-lucide="settings" class="w-4 h-4"></i>
                  Settings
                </span>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a href="logout.php" class="flex items-center gap-2">
                  <i data-lucide="log-out" class="w-4 h-4"></i>
                  Log Out
                </a>
              </div>
            </div>
        </div>

        <div class="h-screen bg-gray-100 py-5 w-4/5 px-7">
            <div>
                <div class="flex justify-end w-full mb-5">
                    <img src="<?php echo !empty($userData['Avatar']) ? $userData['Avatar'] : 'PP.webp'; ?>" alt="Profile Picture" class="w-10 h-10 rounded-full">
                </div>

                <div class="bg-white px-9 py-6 rounded-3xl h-[80vh] flex flex-col">
                    <div class="flex justify-between border-b pb-5 border-zinc-200">
                        <h1 class="text-2xl font-medium">Profile Settings</h1>

                        <?php if(!empty($updateMessage)): ?>
                        <div id="successNotification" class="bg-green-500 text-white px-4 py-2 rounded-lg">
                            <?php echo $updateMessage; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="py-4 flex-1 overflow-y-auto">
                        <form method="POST" action="" class="space-y-6">
                            <!-- Avatar selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Select Avatar</label>
                                <div class="flex flex-wrap gap-4">
                                    <?php foreach($availableAvatars as $index => $avatar): ?>
                                    <div class="avatar-option rounded-lg overflow-hidden cursor-pointer <?php echo (!empty($userData['Avatar']) && $userData['Avatar'] === $avatar) ? 'selected' : ''; ?>" data-avatar="<?php echo $avatar; ?>">
                                        <img src="<?php echo $avatar; ?>" alt="Avatar <?php echo $index+1; ?>" class="w-16 h-16 object-cover">
                                    </div>
                                    <?php endforeach; ?>
                                    <input type="hidden" name="avatar" id="selectedAvatar" value="<?php echo !empty($userData['Avatar']) ? $userData['Avatar'] : $availableAvatars[0]; ?>">
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" id="firstName" name="firstName" value="<?php echo $userData['Prenom'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" id="lastName" name="lastName" value="<?php echo $userData['Nom'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo $userData['Email'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <?php if($userRole === 'Student'): ?>
                            <div>
                                <label for="additionalField" class="block text-sm font-medium text-gray-700 mb-1">Previous Field of Study</label>
                                <input type="text" id="additionalField" name="additionalField" value="<?php echo $userData['Filiere_precedente'] ?? ''; ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <?php endif; ?>

                            <!-- Account Type Information (read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Account Type</label>
                                <div class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-700"><?php echo $userRole ?? 'User'; ?></div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Notification -->
    <div id="successNotification" class="hidden fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300">
        Profile updated successfully!
    </div>

    <script>lucide.createIcons();</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Avatar selection functionality
            const avatarOptions = document.querySelectorAll('.avatar-option');
            const selectedAvatarInput = document.getElementById('selectedAvatar');

            avatarOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Remove selected class from all options
                    avatarOptions.forEach(opt => opt.classList.remove('selected'));

                    // Add selected class to clicked option
                    this.classList.add('selected');

                    // Update hidden input value
                    selectedAvatarInput.value = this.dataset.avatar;
                });
            });

            // Auto-hide notification after 3 seconds
            const notification = document.getElementById('successNotification');
            if (notification && notification.textContent.trim() !== '') {
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 300);
                }, 3000);
            }
        });
    </script>
</body>
</html>
