<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "unite_db";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$canChooseSubject = false;
$message = '';
$isStudent = isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'Etudiant';


if ($isStudent) {
    // Get student information
    $student_id = $_SESSION['Etudiant_id'];
    $stmt = $conn->prepare("SELECT ID_Groupe, Est_Chef FROM Etudiant WHERE ID_Etudiant = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();




    if ($student) {
        $id_groupe = $student['ID_Groupe'];
        $est_chef = $student['Est_Chef'];



        if ($id_groupe === null) {
            $message = "You must be part of a group to choose a subject.";
        } elseif ($est_chef != 1) {
            $message = "Only the group leader can choose a subject.";
        } else {
            // Check if group has no subject assigned
            // Check if group exists and has no subject assigned
            $stmt = $conn->prepare("SELECT ID_Sujet FROM Groupe WHERE ID_Groupe = ?");
            $stmt->bind_param("i", $id_groupe);
            $stmt->execute();
            $result = $stmt->get_result();
            $group = $result->fetch_assoc();
            $stmt->close();

            if ($group) {
                if ($group['ID_Sujet'] === null) {
                    $canChooseSubject = true;
                } else {
                    $message = "Your group has already selected a subject.";
                }
            } else {
                $message = "Error: Group not found.";
            }
        }
    }
}

// Get available subjects
$sql = "SELECT s.ID_Sujet, s.Titre, s.Description, s.Date_Ajout,
               p.Nom, p.Prenom
        FROM sujet s
        LEFT JOIN Professeur p ON s.ID_Professeur = p.ID_Professeur
        WHERE s.ID_Sujet NOT IN (SELECT ID_Sujet FROM Groupe WHERE ID_Sujet IS NOT NULL)";

$result = $conn->query($sql);
$subjects = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subjects</title>
    <meta name="description" content="Team Task Manager" />
    <meta name="author" content="Lovable" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons via CDN -->
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>

    <style>
      /* Custom styles */
      .task-card {
        transition: all 0.2s ease;
      }
      .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      }

      /* Toast Notification Styles */
      #toast {
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
        opacity: 0;
        transform: translateY(-20px);
      }
      #toast.show {
        opacity: 1;
        transform: translateY(0);
      }

      /* Modal Styles */
      .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 100;
        overflow: auto;
      }

      .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 600px;
      }
    </style>
  </head>

  <body class="bg-gray-50">
  <div id="toast" class="fixed top-4 right-4 z-50 hidden">
      <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3">
        <i data-lucide="check-circle" class="h-5 w-5"></i>
        <span id="toast-message"></span>
      </div>
    </div>

    <!-- Modal for Subject Details -->
    <div id="subjectModal" class="modal">
      <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold" id="modalTitle">Subject Details</h2>
          <button class="text-gray-500 hover:text-gray-700 close-modal">
            <i data-lucide="x" class="h-6 w-6"></i>
          </button>
        </div>
        <div class="space-y-4">
          <div>
            <h3 class="text-lg font-semibold">Title</h3>
            <p id="modalSubjectTitle" class="text-gray-700"></p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">Description</h3>
            <p id="modalDescription" class="text-gray-700"></p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">Added on</h3>
            <p id="modalDateAdded" class="text-gray-700"></p>
          </div>
          <div>
            <h3 class="text-lg font-semibold">Added By</h3>
            <p id="modalProfessor" class="text-gray-700"></p>
          </div>
        </div>
        <div class="mt-6 flex justify-end">
          <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded close-modal">
            Close
          </button>
        </div>
      </div>
    </div>

    <!-- Rest of the existing HTML remains the same as in the previous implementation -->
    <div class="flex min-h-screen">
      <!-- Sidebar -->
      <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
        <img src="../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

        <div class="space-y-2">
            <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2" href="#">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Sujects
                </a>
              </div>
          <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
            <a class="flex items-center gap-2" href="#" >
              <i data-lucide="users" class="w-4 h-4"></i>
              My Group
            </a>
          </div>
          <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
            <span class="flex items-center gap-2">
              <i data-lucide="clipboard-list" class="w-4 h-4"></i>
              Tasks Manager
            </span>
          </div>
          <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
            <a class="flex items-center gap-2" href="calendar.html" >
              <i data-lucide="calendar-days" class="w-4 h-4"></i>
              Calendar
            </a>
          </div>
        </div>

        <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
          <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
            <span class="flex items-center gap-2">
              <i data-lucide="settings" class="w-4 h-4"></i>
              Settings
            </span>
          </div>
          <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
            <span class="flex items-center gap-2">
              <i data-lucide="log-out" class="w-4 h-4"></i>
              Log Out
            </span>
          </div>
        </div>
      </div>
    <!-- ... (keep the toast and modal sections unchanged) ... -->

    <!-- Main Content -->
    <div class="h-screen bg-gray-100 py-5 w-3/4 px-7 overflow-y-auto">
      <div>
        <div class="flex justify-end w-full mb-5">
            <div class="h-10 w-10 ml-3 rounded-full bg-gray-300 overflow-hidden">
            <img src="../ChooseAvatar/Avatars/3.png" alt="Profile" class="h-full w-full object-cover" />
            </div>
        </div>

        <?php foreach ($subjects as $subject): ?>
            <?php
            $shortDesc = strlen($subject['Description']) > 100
                ? substr($subject['Description'], 0, 100) . '...'
                : $subject['Description'];
            ?>

            <div class="bg-[#2F2C2C] text-white px-10 py-5 rounded-3xl mb-5 shadow-lg">
              <p class="text-2xl mb-3 font-semibold "><?php echo htmlspecialchars($subject['Titre']); ?></p>
              <p class="mb-7  "><?php echo htmlspecialchars($shortDesc); ?></p>
          <div class="flex justify-end">
            <button class="bg-white px-4 py-1 text-black rounded-lg choose-subject <?php echo $canChooseSubject ? '' : 'bg-gray-300 cursor-not-allowed' ?>"
                    data-id="<?php echo $subject['ID_Sujet']; ?>"
                    <?php if (!$canChooseSubject) echo 'disabled title="' . htmlspecialchars($message) . '"'; ?>>
              Choose Subject
            </button>
            <button class="bg-white px-4 py-1 text-black rounded-lg ml-2 details-btn"
                        data-id="<?php echo $subject['ID_Sujet']; ?>"
                        data-title="<?php echo htmlspecialchars($subject['Titre']); ?>"
                        data-description="<?php echo htmlspecialchars($subject['Description']); ?>"
                        data-date="<?php echo htmlspecialchars($subject['Date_Ajout']); ?>"
                        data-professor="<?php echo htmlspecialchars($subject['Nom'] . ' ' . $subject['Prenom']); ?>">Details</button>
          </div>
          </div>
        <?php endforeach; ?>


      </div>
    </div>

    <script>
  lucide.createIcons();

  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('subjectModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const detailButtons = document.querySelectorAll('.details-btn');

    // Modal handling (existing code)
    detailButtons.forEach(button => {
      button.addEventListener('click', function() {
        const title = this.getAttribute('data-title');
        const description = this.getAttribute('data-description');
        const date = this.getAttribute('data-date');
        const professor = this.getAttribute('data-professor');

        document.getElementById('modalSubjectTitle').textContent = title;
        document.getElementById('modalDescription').textContent = description;
        document.getElementById('modalDateAdded').textContent = date;
        document.getElementById('modalProfessor').textContent = professor;

        modal.style.display = 'block';
      });
    });

    // Close modal handling (existing code)
    closeButtons.forEach(button => {
      button.addEventListener('click', function() {
        modal.style.display = 'none';
      });
    });

    window.addEventListener('click', function(event) {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });

    // ========== UPDATED CHOOSE SUBJECT HANDLER ==========
    const chooseButtons = document.querySelectorAll('.choose-subject');
    chooseButtons.forEach(button => {
      button.addEventListener('click', async function() {
        <?php if (!$canChooseSubject): ?>
          alert("<?php echo addslashes($message) ?>");
          return;
        <?php endif; ?>

        // Add confirmation dialog
        const confirmation = confirm("Are you sure you want to choose this subject?");
        if (!confirmation) return;

        try {
          const subjectId = this.getAttribute('data-id');
          const response = await fetch('assign_subject.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              subject_id: subjectId,
              group_id: <?php echo $id_groupe ?? 'null' ?>
            })
          });

          const data = await response.json();

          if (data.success) {
            showToast("Subject selected successfully!");
            setTimeout(() => {
              location.reload();
            }, 1500);
          } else {
            showToast("Error: " + data.message, true);
          }
        } catch (error) {
          showToast("Network error: " + error.message, true);
        }
      });
    });

    // Toast notification function
    function showToast(message, isError = false) {
      const toast = document.getElementById('toast');
      const toastMessage = document.getElementById('toast-message');

      // Reset classes and content
      toast.className = 'fixed top-4 right-4 z-50 hidden';
      toastMessage.textContent = message;

      // Add appropriate classes
      toast.classList.add(isError ? 'bg-red-500' : 'bg-green-500', 'show');
      toast.classList.remove('hidden');

      // Hide after 3 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hidden');
      }, 3000);
    }
  });
</script>
  </body>
</html>
