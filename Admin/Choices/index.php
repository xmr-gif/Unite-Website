<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$host ='localhost';
$db = 'unite_db';
$user='root';
$pass ='';
try {
    $pdo = new PDO ("mysql:host=$host;port=3306;dbname=$db",$user,$pass);
    //echo "Connexion reussite";

} catch (PDOException $e) {
    echo "La connexion n'est pas reussie ".$e->getMessage() ;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['sujet_id']) && isset($data['status'])) {
            $updateQuery = "UPDATE Sujet SET Est_Valide = :status WHERE ID_Sujet = :sujet_id";
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([
                ':status' => $data['status'],
                ':sujet_id' => $data['sujet_id']
            ]);

            // Add these 3 lines
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    } catch (PDOException $e) {
        // Add these 3 lines
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Modify your initial query to include ID_Sujet
$query = "SELECT
            e.Nom,
            e.Prenom,
            e.Avatar,
            s.Titre,
            s.Est_Valide,
            s.ID_Sujet,
            g.ID_Groupe,
            (SELECT COUNT(*) FROM Etudiant WHERE ID_Groupe = g.ID_Groupe) AS TotalMembers
          FROM Etudiant e
          JOIN Groupe g ON e.ID_Groupe = g.ID_Groupe
          JOIN Sujet s ON g.ID_Sujet = s.ID_Sujet
          WHERE e.Est_Chef = 1
          ORDER BY
            CASE s.Est_Valide
                WHEN 'Pending' THEN 1
                WHEN 'Accepted' THEN 2
                WHEN 'Rejected' THEN 3
            END";
$stmt = $pdo->prepare($query);
$stmt->execute();
$leaders = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" integrity="sha512-kJlvECunwXftkPwyvHbclArO8wszgBGisiLeuDFwNM8ws+wKIw0sv1os3ClWZOcrEB2eRXULYUsm8OVRGJKwGA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@0.462.0/dist/umd/lucide.min.js"></script>
    <title>Choices</title>
    <style>
        .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
        /* Add flex centering */
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 1rem;
        width: 50%;
        max-width: 600px;
        /* Remove transform positioning */
        position: relative;
        /* Add max height and scroll if needed */
        max-height: 80vh;
        overflow-y: auto;
    }
        .row-checkbox:checked {
            accent-color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="flex" >
        <div class="py-5 px-6 w-1/4 h-screen border-r border-gray-200 bg-white">
            <img src="../../images/black-logo.png" class="w-20 mb-10 ml-4" alt="Logo" />

            <div class="space-y-2">
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Dashboard/index.php" >
                  <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                  Dashboard
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../Users/index.php" >
                  <i data-lucide="user" class="w-4 h-4"></i>
                  Users
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2">
                  <i data-lucide="lightbulb" class="w-4 h-4"></i>
                  Subjects
                </a>
              </div>
              <div class="px-4 py-2 bg-slate-200 rounded-md cursor-pointer">
                <a class="flex items-center gap-2">
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
                <a class="flex items-center gap-2" href="../Groups/index.php">
                  <i data-lucide="users" class="w-4 h-4"></i>
                  Groups
                </a>
              </div>
            </div>

            <div class="absolute bottom-8 space-y-2 w-[calc(25%-48px)]">
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2">
                  <i data-lucide="settings" class="w-4 h-4"></i>
                  Settings
                </a>
              </div>
              <div class="px-4 py-2 text-zinc-500 rounded-md hover:bg-slate-100 cursor-pointer">
                <a class="flex items-center gap-2" href="../../logout/logout.php" >
                  <i data-lucide="log-out" class="w-4 h-4"></i>
                  Log Out
                </a>
              </div>
            </div>
          </div>
        <div class="h-screen bg-gray-100 py-5 w-4/5 px-7 " >
            <div >
                <div class="flex justify-end w-full mb-5" >
                    <div class="border border-zinc-400 text-zinc-600 p-5 w-10 h-10 flex justify-center items-center rounded-xl cursor-pointer">
                        +
                    </div>
                    <img src="../../images/PP.webp" alt="" class="w-10 h-10 rounded-full ml-3">

                </div>


                <div class="bg-white px-9 py-4 rounded-3xl h-[72vh] " >
                <div class="flex justify-between border-b-1 pt-4 pb-5 border-zinc-400">
                <p class="text-2xl font-medium">Choices</p>
                <div class="flex items-center gap-4">
                    <div class="flex gap-2">
                        <button id="acceptAll" class="border text-zinc-500 px-2 text-sm rounded-md border-zinc-400 cursor-pointer hover:border-blue-600 hover:text-blue-600 transition-colors">
                            Accept
                        </button>
                        <button id="rejectAll" class="border text-zinc-500 px-2 text-sm rounded-md border-zinc-400 cursor-pointer hover:border-orange-600 hover:text-orange-600 transition-colors">
                            Reject
                        </button>
                    </div>
                    <div class="rounded-md bg-gray-100 p-1 text-zinc-500 border-b-2 flex items-center">
                        <i class="ri-search-line"></i>
                        <input type="search" placeholder="Search" class="bg-transparent outline-none ml-1">
                    </div>
                </div>
            </div>

                    <div class="flex text-zinc-500 py-2 border-zinc-200 border-b " >
                        <p class="w-1/4" >Leader</p>
                        <p class="w-1/4" >Subject</p>
                        <p class="w-1/5" >Status</p>
                        <p class="w-1/5" >Total Members</p>

                    </div>

                    <div class="snap-y">
                <?php foreach ($leaders as $leader):
                    $status = $leader['Est_Valide'];
                    $statusClass = '';
                    switch ($status) {
                        case 'Pending':
                            $statusClass = 'bg-orange-100 text-orange-300';
                            break;
                        case 'Accepted':
                            $statusClass = 'bg-green-100 text-green-400';
                            break;
                        case 'Rejected':
                            $statusClass = 'bg-red-100 text-red-400';
                            break;
                    }
                ?>

                <div class="flex items-center text-sm font-medium border-zinc-200 border-b py-2">
                    <!-- Leader Column -->
                    <div class="flex items-center w-1/4">
                               <input type="checkbox"
                               class="row-checkbox mr-1"
                               data-sujet-id="<?= $leader['ID_Sujet'] ?>">
                        <img src="../../ChooseAvatar/Avatars/<?= $leader['Avatar'] ?>.png"
                             alt="Leader Avatar"
                             class="w-8 h-8 rounded-md mr-1">
                        <p><?= $leader['Prenom'] ?> <?= $leader['Nom'] ?></p>
                    </div>

                    <!-- Subject Column -->
                    <p class="w-1/4"><?= $leader['Titre'] ?></p>

                    <!-- Status Column -->
                    <div class="w-1/5 status-display">
                        <div class="<?= $statusClass ?> px-2 rounded-xl text-center w-1/2">
                            <p><?= $status ?></p>
                        </div>
                    </div>

                    <!-- Total Members Column -->
                    <p class="w-1/5"><?= $leader['TotalMembers'] ?></p>

                    <!-- Details Button -->
                    <button class="details-btn border text-zinc-500 px-2 rounded-md border-zinc-400 cursor-pointer"
                            data-group-id="<?= $leader['ID_Groupe'] ?>">
                        Details
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ... (pagination remains the same) -->
    </div>



            </div>

        </div>
    </div>


    <div id="groupModal" class="modal ml-6">
        <div class="modal-content">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Group Members</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div id="memberList" class="space-y-2">
                <!-- Member items will be inserted here -->
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>

    <script>
        // Modal functions
        function showModal() {
            document.getElementById('groupModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('groupModal').style.display = 'none';
        }

        // Click handler for Details buttons
        document.querySelectorAll('.details-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const groupId = this.dataset.groupId;

                try {
                    const response = await fetch(`get_group_members.php?group_id=${groupId}`);
                    const members = await response.json();

                    const memberList = document.getElementById('memberList');
                    memberList.innerHTML = '';

                    members.forEach(member => {
                        memberList.innerHTML += `
                            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                <span>${member.Prenom} ${member.Nom}</span>
                                <span class="text-gray-600 text-sm">${member.Filiere_Precedente}</span>
                            </div>
                        `;
                    });

                    showModal();
                } catch (error) {
                    console.error('Error fetching group members:', error);
                    alert('Error loading group members');
                }
            });
        });
    </script>


<script>
    // Handle status updates
    function updateStatus(status) {
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkboxes.length === 0) {
            alert('Please select at least one row');
            return;
        }

        const sujetIds = Array.from(checkboxes).map(checkbox =>
            parseInt(checkbox.dataset.sujetId)
        );

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                sujet_id: sujetIds[0],
                status: status
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            return response.text().then(text => text ? JSON.parse(text) : {});
        })
        .then(data => {
            if (data.success) {
                checkboxes.forEach(checkbox => {
                    // Corrected line
                    const row = checkbox.closest('.flex.items-center.text-sm.font-medium');
                    const statusDiv = row.querySelector('.status-display');
                    statusDiv.innerHTML = `
                        <div class="${getStatusClass(status)} px-2 rounded-xl text-center w-1/2">
                            <p>${status}</p>
                        </div>
                    `;
                    checkbox.checked = false;
                });
            } else {
                alert('Error: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating status: ' + error.message);
        });
    }

    // Button click handlers
    document.getElementById('acceptAll').addEventListener('click', () => updateStatus('Accepted'));
    document.getElementById('rejectAll').addEventListener('click', () => updateStatus('Rejected'));

    function getStatusClass(status) {
        switch (status) {
            case 'Pending': return 'bg-orange-100 text-orange-300';
            case 'Accepted': return 'bg-green-100 text-green-400';
            case 'Rejected': return 'bg-red-100 text-red-400';
        }
    }
</script>


</body>
</html>
