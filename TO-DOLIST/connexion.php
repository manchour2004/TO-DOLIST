<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function connect()
{
    $host = 'sql100.infinityfree.com';
    $db = 'if0_37871220_todolist';
    $user = 'if0_37871220';
    $password = '2T44LYDSs9F1xT';

    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }
    return $conn;
}
$conn = connect();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion / Création de compte</title>
    <script src="js/style.js"></script>
    <style>
        .hidden {
            display: none;
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
    </style>
</head>

<body class="min-h-screen bg-gray-100 font-mono px-4 py-4 sm:px-6 lg:px-8">
    <!--HEADER-->
    <header class="flex justify-self-end">
        <div class="shrink-0">
            <img class="h-10 w-10 object-cover rounded-lg" src="img/liste-de-choses-a-faire.png" />
        </div>
        <h1 class="flex justify-self-end text-3xl font-mono font-black italic ">
            TODO-LIST
        </h1>
    </header>
    <div class="flex items-center justify-center">
        <!--INSCRPTION ET CONNEXION-->
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 id="formTitle" class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Connexion
                </h2>
            </div>
            <div class="mt-8 bg-white py-8 px-4 rounded-2xl shadow  sm:px-10">
                <div class="sm:mx-auto sm:w-full sm:max-w-md">
                    <div class="flex justify-center  mb-6">
                        <button id="loginTab"
                            class="px-4 py-2 text-sm w-full font-medium rounded-l-lg bg-black text-white">
                            Connexion
                        </button>
                        <button id="registerTab" class="px-4 py-2 text-sm w-full 
                            font-medium  bg-gray-200 text-gray-700 rounded-r-lg hover:bg-gray-300">
                            Créer un compte
                        </button>
                    </div>
                </div>

                <form id="loginForm" class="space-y-6 " action="connexion.php" method="POST">
                    <div>
                        <label for="loginEmail" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <div class="mt-1">
                            <input id="loginEmail" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm"
                                placeholder="exemple@gmail.com">
                        </div>
                    </div>

                    <div>
                        <label for="loginPassword" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <div class="mt-1 relative">
                            <input id="loginPassword" name="password" type="password" autocomplete="current-password"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm"
                                placeholder="••••••••">
                            <button type="button"
                                class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="connexion"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black"
                            >
                            Se connecter
                        </button>

                    </div>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        if (isset($_POST['inscription'])) {
                            $nom = $_POST['nom'];
                            $prenom = $_POST['prenom'];
                            $email = $_POST['email'];
                            $mdp = $_POST['password'];
                            $Cmdp = $_POST['confirm-password'];
                            if ($mdp === $Cmdp) {
                                $stmt = $conn->prepare('SELECT * FROM Users WHERE email = ?');
                                $stmt->bind_param('s', $email);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                if ($result->num_rows > 0) {
                                    echo '<br> <div id="confirmationMessage" class="max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                        <p class="font-bold">OUPS!</p>
                                        <p id="confirmationText">Cet email existe déjà</p>
                                        </div>';
                                    echo '<script>
                                                setTimeout(function() {
                                                    document.getElementById("confirmationMessage").remove();
                                                }, 5000);
                                                </script>';
                                } else {
                                    $mdp_crypt = password_hash($mdp, PASSWORD_BCRYPT);
                                    $stmt = $conn->prepare('INSERT INTO Users (nom,prenom,email,mdp) VALUES (?,?,?,?)');
                                    $stmt->bind_param('ssss', $nom, $prenom, $email, $mdp_crypt);
                                    $stmt->execute();
                                    if ($stmt->affected_rows > 0) {
                                        $_SESSION['utilisateur'] = $email;
                                        $stmt->close();
                                        $conn->close();
                                        header('LOCATION: main.php');
                                    } else {
                                        echo '<br> <div id="confirmationMessage" class="max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                                    <p class="font-bold">OUPS!</p>
                                                    <p id="confirmationText">Échec de l\'inscription pour ' . htmlspecialchars($email) . '</p>
                                                    </div>';
                                        echo '<script>
                                                    setTimeout(function() {
                                                        document.getElementById("confirmationMessage").remove();
                                                    }, 5000);
                                                    </script>';
                                    }

                                }
                            } else {
                                echo '<br> <div id="confirmationMessage" class="max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                    <p class="font-bold">OUPS!</p>
                                    <p id="confirmationText">Les mots de passe  ne correspondent pas</p>

                                    </div>';
                                echo '<script>
                                    setTimeout(function() {
                                        document.getElementById("confirmationMessage").remove();
                                    }, 5000);
                                    </script>';
                            }
                        } elseif (isset($_POST['connexion'])) {
                            $email = $_POST['email'];
                            $mdp = $_POST['password'];
                            $stmt1 = $conn->prepare('SELECT * FROM  Users WHERE email=?');
                            $stmt1->bind_param('s', $email);
                            $stmt1->execute();
                            $result = $stmt1->get_result();
                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                $mdp_crypt = $row['mdp'];
                                if (password_verify($mdp, $mdp_crypt)) {
                                    $_SESSION['utilisateur'] = $email;
                                    $stmt1->close();
                                    $conn->close();
                                    header('LOCATION: main.php');
                                }
                                else{
                                    echo '<br> <div id="confirmationMessage" class="max-w-md mx-auto mt-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                    <p class="font-bold">OUPS!</p>
                                    <p id="confirmationText">Mot de passe incorrect </p>

                                    </div>';
                                echo '<script>
                                    setTimeout(function() {
                                        document.getElementById("confirmationMessage").remove();
                                    }, 5000);
                                    </script>';
                                }
                            }

                        }
                    }
                    ?>
                </form>

                <form id="registerForm" class="space-y-2 hidden" action="connexion.php" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="registerName" class="block text-sm font-medium text-gray-700">
                                Nom
                            </label>
                            <div class="mt-1">
                                <input id="registeeName" name="nom" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="registerFirstname" class="block text-sm font-medium text-gray-700">
                                Prenom
                            </label>
                            <div class="mt-1">
                                <input id="registerFirstname" name="prenom" required
                                    class="appearance-none px-3 block py-2 border w-full border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="registerEmail" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <div class="mt-1">
                            <input id="registerEmail" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm"
                                placeholder="exemple@gmail.com">
                        </div>
                    </div>

                    <div>
                        <label for="registerPassword" class="block text-sm font-medium text-gray-700">
                            Mot de passe
                        </label>
                        <div class="mt-1 relative">
                            <input id="registerPassword" name="password" type="password" autocomplete="new-password"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-black focus:border-black sm:text-sm"
                                placeholder="••••••••">
                            <button type="button"
                                class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700">
                            Confirmer le mot de passe
                        </label>
                        <div class="mt-1">
                            <input id="confirmPassword" name="confirm-password" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" name="inscription"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            S'inscrire
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const formTitle = document.getElementById('formTitle');
            const togglePasswordButtons = document.querySelectorAll('.toggle-password');

            function showLogin() {
                loginTab.classList.add('bg-black', 'text-white');
                loginTab.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                registerTab.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                registerTab.classList.remove('bg-black', 'text-white');
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                formTitle.textContent = 'Connexion';
            }

            function showRegister() {
                registerTab.classList.add('bg-black', 'text-white');
                registerTab.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                loginTab.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                loginTab.classList.remove('bg-black', 'text-white');
                registerForm.classList.remove('hidden');
                loginForm.classList.add('hidden');
                formTitle.textContent = 'Créer un compte';
            }

            loginTab.addEventListener('click', showLogin);
            registerTab.addEventListener('click', showRegister);

            togglePasswordButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);

                    // Change the eye icon
                    if (type === 'password') {
                        this.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        `;
                    } else {
                        this.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        `;
                    }
                });
            });
        });
    </script>
</body>

</html>