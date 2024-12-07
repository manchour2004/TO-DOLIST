<?php
// Démarrer une session
session_start();

// Afficher les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['utilisateur'])) {
    header('Location:index.php');
    exit(); // Assurer l'arrêt du script après la redirection
}

// Fonction pour se connecter à la base de données
function connect()
{
    $host = '	sql100.infinityfree.com';
    $db = 'if0_37871220_todolist';
    $user = 'if0_37871220';
    $password = '2T44LYDSs9F1xT';

    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    return $conn;
}

// Connexion à la base de données
global $conn;
$conn = connect();


// Récupérer les informations de l'utilisateur connecté
$stmt = $conn->prepare('SELECT id, nom, prenom FROM Users where email=?');
$stmt->bind_param('s', $_SESSION['utilisateur']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($id, $nom, $prenom);
$stmt->fetch();

// Récupérer le nombre de tâches pour aujourd'hui
$today = $conn->prepare('SELECT COUNT(*) FROM Tache where date=CURRENT_DATE() AND statut=0 AND id_user=?');
$today->execute([$id]);
$today->store_result();
$today->bind_result($today1);
$today->fetch();

// Récupérer le nombre de toutes les tâches en cours (non complétées)
$tout = $conn->prepare('SELECT COUNT(*) FROM Tache where statut=0 AND id_user=?');
$tout->execute([$id]);
$tout->store_result();
$tout->bind_result($tout1);
$tout->fetch();

// Récupérer le nombre de tâches complétées
$complet = $conn->prepare('SELECT COUNT(*) FROM Tache where statut=1 AND id_user=?');
$complet->execute([$id]);
$complet->store_result();
$complet->bind_result($complet1);
$complet->fetch();
// Récupérer le nombre de projet
$nbreProjet = $conn->prepare('SELECT COUNT(*) FROM Projet WHERE id_user=?');
$nbreProjet->execute([$id]);
$nbreProjet->store_result();
$nbreProjet->bind_result($nbreProjet1);
$nbreProjet->fetch();
// Récupérer le nombre de tâches complétées
$nbreNote = $conn->prepare('SELECT COUNT(*) FROM Note where id_user=?');
$nbreNote->execute([$id]);
$nbreNote->store_result();
$nbreNote->bind_result($nbreNote1);
$nbreNote->fetch();

// Ajouter une nouvelle tâche si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ajout_t'])) {
        $titre_tache = $_POST['titre_tache'];
        $description_tache = $_POST['description_tache'];
        $date_tache = $_POST['date_tache'];
        $id_projet = $_POST['ch_projet'];
        if ($id_projet === 'NULL') {
            $sqlTache = $conn->prepare('INSERT INTO tache (nom,date,description,id_user) VALUES (?,?,?,?)');
            $sqlTache->bind_param('sssi', $titre_tache, $date_tache, $description_tache, $id);
            $sqlTache->execute();
        } else {
            $sqlTache = $conn->prepare('INSERT INTO tache (nom,date,description,id_user,id_projet) VALUES (?,?,?,?,?)');
            $sqlTache->bind_param('ssssi', $titre_tache, $date_tache, $description_tache, $id, $id_projet);
            $sqlTache->execute();
        }
    }
    elseif(isset($_POST['ajout_p'])) {
        $titre_p = $_POST['titre_p'];
        $description_p = $_POST['description_p'];
        $sqlProjet = $conn->prepare('INSERT INTO projet (nom,description,id_user) value(?,?,?)');
        $sqlProjet->execute([$titre_p, $description_p, $id]);
    }
    elseif(isset($_POST['ajout_note'])) {
        $titre_note = $_POST['titre_note'];
        $contenu_note = $_POST['contenu_note'];
        $sqlNote = $conn->prepare('INSERT INTO note (titre,contenu,id_user) value(?,?,?)');
        $sqlNote->execute([$titre_note, $contenu_note, $id]);
    }
    elseif(isset($_POST['btn_modif'])){
        $id_modif = $_POST['id_modif'];
        $contenu_modif=$_POST['contenu_modif'];
        $modif=("UPDATE note SET contenu='$contenu_modif' WHERE id=$id_modif");
        $sql_mod=$conn->query($modif);
    }
    
    elseif(isset($_POST['note_sup'])){
        $id_modif = $_POST['id_modif'];
        $supprime=("DELETE FROM note WHERE id=$id_modif");
        $sql_supp=$conn->query($supprime);
    }
    
}

// Récupérer les tâches d'aujourd'hui pour l'utilisateur connecté
$sql = "SELECT * FROM Tache WHERE statut=0 AND id_user=$id AND date=CURRENT_DATE() ORDER BY date DESC";
$stmt1 = $conn->query($sql);
$stmt1->fetch_all();



    
// Récupérer toutes les tâches en cours
$sql_tout = "SELECT * FROM Tache WHERE statut=0 AND id_user=$id  ORDER BY date DESC";
$stmt_tout = $conn->query($sql_tout);
$stmt_tout->fetch_all();

// Récupérer toutes les tâches complétées
$sql_complet = "SELECT * FROM Tache WHERE statut=1 AND id_user=$id  ORDER BY date DESC";
$stmt_complet = $conn->query($sql_complet);
$stmt_complet->fetch_all();

// Récupérer la date actuelle
$day = $conn->query("SELECT DAY(CURDATE()) ;");
$date = $day->fetch_row();
$date = (string) $date[0];

//recuperer tout les projets de l'utilisateur
$sql_projet = "SELECT * FROM projet WHERE id_user=$id";
$stmt_projet = $conn->query($sql_projet);
$stmt_projet->fetch_all();

//recupérer les note de l'utilisateur
$sql_note = "SELECT * FROM note WHERE id_user=$id";
$stmt_note = $conn->query($sql_note);
$stmt_note->fetch_all();

//tache de chaque projet
function tache_projet($id, $conn)
{
    $task = "SELECT * FROM tache WHERE id_projet = ? ORDER BY statut";
    $stmt_task = $conn->prepare($task);

    if ($stmt_task) {
        $stmt_task->bind_param('i', $id);
        $stmt_task->execute();
        $result = $stmt_task->get_result();

        // Parcours des résultats
        while ($tp = $result->fetch_assoc()) {
            if($tp['statut']==0){
                echo '<div class="task-item bg-gray-100 p-2 rounded-lg">
                <span class="material-symbols-outlined mr-6">circle</span>
                <div class="flex-grow text-left">
                    <span class="font-bold">' . htmlspecialchars($tp["nom"]) . '</span>
                    <br>
                </div>
                <button class="flex items-center mt-2">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <span class="ml-2">' . htmlspecialchars($tp["date"]) . '</span>
                </button>
              </div>';
            }
            else{
                echo '<div class="task-item bg-gray-100 p-2 rounded-lg">
                <span class="material-symbols-outlined mr-6">task_alt</span>
                <div class="flex-grow text-left">
                    <span class="font-bold">' . htmlspecialchars($tp["nom"]) . '</span>
                    <br>
                </div>
                <button class="flex items-center mt-2">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <span class="ml-2">' . htmlspecialchars($tp["date"]) . '</span>
                </button>
              </div>';
            }
           
        }

        $stmt_task->close();
    } else {
        echo "<div class='text-red-500'>Erreur dans la récupération des tâches.</div>";
    }
}
//fraction projet

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Métadonnées de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tâches</title>
    <!-- Liens vers les polices et le style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@200..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="js/style.js"></script>
    <style>
        /* Styles généraux pour la police et les éléments */
        * {
            font-family: 'Oxanium', sans-serif;
        }

        body {
            background: linear-gradient(-45deg, #f3cbbf, #e9b0c6, #c1e9f8, #c8f5ea);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100%;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #c0c0c0;
        }

        .task-button {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            margin-top: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s, transform 0.2s;
        }

        .task-button:hover {
            background-color: #e0e0e0;
        }

        .task-button:focus {
            background-color: #e0e0e0;
        }

        .task-item {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            margin-top: 1rem;
            border-bottom: 1px solid #ddd;
        }
        .clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;  /* Limite à 2 lignes */
    -webkit-box-orient: vertical;
    overflow: hidden;  /* Cache le texte qui dépasse */
}
        .clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;  /* Limite à 2 lignes */
    -webkit-box-orient: vertical;
    overflow: hidden;  /* Cache le texte qui dépasse */
}

    </style>
</head>

<body class="grid grid-cols-12 gap-4 bg-gray-300 p-4 ">
    <?php
    include "slidebar.php";
    ?>
    <?php
    include "principal.php";
    ?>
    <script src="js/nav.js"></script>
</body>

</html>