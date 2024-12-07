<!-- Main Content -->


<div class="bg-white col-span-7 lg:col-span-9 rounded-2xl shadow-lg p-8">
    <div id="e_tache" class="">
        <div id="e_aujourdhui" class="flex flex-col">
            <div class="flex justify-between items-center text-4xl mb-6">
                <h1>Aujourd'hui</h1>
                <span><?php echo htmlspecialchars($date); ?></span>
            </div>
            <div class=" p-4 mb-2 hidden " id="ajout_t">
                <div class="flex  justify-center rounded-lg border  border-gray-400 p-4">
                    <form action="main.php" method="POST">
                        <div>
                            <input type="text" name="titre_tache" placeholder="Titre de la tâche"
                                class="border rounded-lg p-2 w-96 mt-2">
                        </div>
                        <div>
                            <input type="text" name="description_tache" placeholder="Description"
                                class="border rounded-lg p-2 w-96 mt-2">
                        </div>
                        <div>
                            <input type="date" name="date_tache" class="border rounded-lg p-2 w-96 mt-2">
                        </div>
                        <div>
                            <select name="ch_projet" class="border rounded-lg p-2 w-96 mt-2">
                                <option>Choix de projet</option>
                                <option value="NULL">Aucun</option>
                                <?php foreach ($stmt_projet as $projet): ?>
                                    <option value="<?php echo $projet['id']; ?>"><?php echo $projet['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" name="ajout_t"
                                class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="pr-2 max-h-[650px] overflow-y-auto   [&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:bg-gray-300
dark:[&::-webkit-scrollbar-track]:bg-neutral-700
dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <?php if ($today1 != 0): ?>
                    <?php foreach ($stmt1 as $tache): ?>
                        <div class="task-item custom-scrollbar flex items-center p-4 border-b">
                            <!-- Formulaire pour marquer une tâche comme complétée -->
                            <form action="main.php" method="POST" class="mr-4">
                                <input type="hidden" name="id_completer" value="<?php echo htmlspecialchars($tache['id']); ?>">
                                <button type="submit" name="completer" class="material-symbols-outlined">circle</button>
                            </form>

                            <!-- Détails de la tâche -->
                            <div class="flex-grow text-left pl-4">
                                <span class="font-bold">
                                    <?php echo htmlspecialchars($tache['nom']); ?>
                                </span>
                                <br>
                                <span class="text-sm italic pl-2">
                                    <?php echo htmlspecialchars($tache['description']); ?>
                                </span>
                            </div>

                            <!-- Date de la tâche -->
                            <div class="flex items-center">
                                <span class="material-symbols-outlined">calendar_today</span>
                                <span class="ml-2"><?php echo htmlspecialchars($tache['date']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php
                    // Vérification et traitement du formulaire
                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completer'])) {
                        if (isset($_POST['id_completer']) && is_numeric($_POST['id_completer'])) {
                            $id_completer = intval($_POST['id_completer']);
                            $completer = "UPDATE tache SET statut = 1 WHERE id = ?";
                            $stmt_completer = $conn->prepare($completer);

                            if ($stmt_completer) {
                                $stmt_completer->bind_param('i', $id_completer);
                                $stmt_completer->execute();
                                $stmt_completer->close();
                            }
                        }
                        // Redirection pour éviter la soumission multiple
                        header("Location: main.php");
                        exit();
                    }
                    ?>
                <?php else: ?>
                    <div class="flex items-center justify-center h-96 text-5xl">
                        <p>AUCUNE TACHE EN COURS</p>
                    </div>
                <?php endif; ?>
            </div>

        </div>
        <div id="e_tout" class="flex flex-col hidden">
            <div class="flex justify-between items-center text-4xl mb-6">
                <h1>Mes tâches</h1>
            </div>
            <div class="pr-2 max-h-[650px] overflow-y-auto   [&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:bg-gray-300
dark:[&::-webkit-scrollbar-track]:bg-neutral-700
dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
                <?php if ($tout1 != 0): ?>
                    <?php foreach ($stmt_tout as $tache): ?>
                        <?php
                        $dateActuelle = (new DateTime())->format('Y-m-d');
                        if ($dateActuelle > $tache['date']):
                            ?>

                            <div class="task-item custom-scrollbar text-red-400">
                                <form action="main.php" method="POST">
                                    <input type="hidden" name="id_completer" value="<?php echo $tache['id']; ?>">
                                    <button class="material-symbols-outlined mr-6" name="completer">circle</button>
                                </form>
                                <?php
                                if (isset($_POST['completer'])) {
                                    $id_completer = $_POST['id_completer'];
                                    $completer = "UPDATE tache SET statut= 1 WHERE id = ?";
                                    $stmt_completer = $conn->prepare($completer);
                                    $stmt_completer->bind_param('i', $id_completer);
                                    $stmt_completer->execute();
                                    header("LOCATION: main.php");
                                }
                                ?>
                                <div class="flex-grow text-left pl-16">
                                    <span class="font-bold">
                                        <?php echo $tache['nom']; ?>
                                    </span>
                                    <br>
                                    <span class="text-sm italic pl-2">
                                        <?php echo $tache['description']; ?>
                                    </span>
                                </div>
                                <button class=" flex">
                                    <div>
                                        <span class="material-symbols-outlined  inline-block align-middle ">calendar_today</span>
                                        <span class="ml-2  inline-block align-middle "><?php echo $tache['date']; ?></span>
                                    </div>
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="task-item custom-scrollbar">
                                <form action="main.php" method="POST">
                                    <input type="hidden" name="id_completer" value="<?php echo $tache['id']; ?>">
                                    <button class="material-symbols-outlined mr-6" name="completer">circle</button>
                                </form>
                                <?php
                                if (isset($_POST['completer'])) {
                                    $id_completer = $_POST['id_completer'];
                                    $completer = "UPDATE tache SET statut= 1 WHERE id = ?";
                                    $stmt_completer = $conn->prepare($completer);
                                    $stmt_completer->bind_param('i', $id_completer);
                                    $stmt_completer->execute();
                                    header("LOCATION: main.php");
                                }
                                ?>
                                <div class="flex-grow text-left pl-16">
                                    <span class="font-bold">
                                        <?php echo $tache['nom']; ?>
                                    </span>
                                    <br>
                                    <span class="text-sm italic pl-2">
                                        <?php echo $tache['description']; ?>
                                    </span>
                                </div>
                                <button class=" flex">
                                    <div>
                                        <span class="material-symbols-outlined  inline-block align-middle ">calendar_today</span>
                                        <span class="ml-2  inline-block align-middle "><?php echo $tache['date']; ?></span>
                                    </div>
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex items-center justify-center h-96 text-5xl">
                        <p>AUCUNE TACHE EN COURS</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div id="e_complet" class="flex flex-col hidden">
            <div class="flex justify-between items-center text-4xl mb-6">
                <h1>Tâche complete</h1>

            </div>
            <div class="pr-2 max-h-[650px] overflow-y-auto   [&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:bg-gray-300
dark:[&::-webkit-scrollbar-track]:bg-neutral-700
dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">

                <?php if ($complet1 != 0): ?>
                    <?php foreach ($stmt_complet as $tache): ?>
                        <div class="task-item custom-scrollbar">
                            <span class="material-symbols-outlined mr-6">task_alt</span>
                            <div class="flex-grow text-left pl-16">
                                <span class="font-bold">
                                    <?php echo $tache['nom']; ?>
                                </span>
                                <br>
                                <span class="text-sm italic pl-2">
                                    <?php echo $tache['description']; ?>
                                </span>
                            </div>
                            <button class=" flex">
                                <div>
                                    <span class="material-symbols-outlined  inline-block align-middle ">calendar_today</span>
                                    <span class="ml-2  inline-block align-middle "><?php echo $tache['date']; ?></span>
                                </div>
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex items-center justify-center h-96 text-5xl">
                        <p>AUCUNE TACHE COMPLETEE</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="e_projet" class="hidden">
        <div class="flex justify-between items-center text-4xl mb-6">
            <h1>Mes projets</h1>
            <span>
                <?php echo $nbreProjet1 ?>
            </span>
        </div>
        <!-- Conteneur pour ajouter un projet -->
        <div id="b_plus" class="mb-2 hidden">
            <div class="justify-center rounded-lg border border-gray-400 p-4 w-full">
                <form action="main.php" method="POST" class="flex flex-col space-y-4">
                    <!-- Champ pour le titre du projet -->
                    <div>
                        <input type="text" name="titre_p" placeholder="Titre du projet"
                            class="border rounded-lg p-2 w-full mt-2">
                    </div>
                    <!-- Champ pour la description du projet -->
                    <div>
                        <input type="text" name="description_p" placeholder="Description"
                            class="border rounded-lg p-2 w-full mt-2">
                    </div>
                    <!-- Bouton pour ajouter le projet -->
                    <div>
                        <button type="submit" name="ajout_p"
                            class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:scale-110 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Liste des projets -->
        <div class="mt-2  max-h-[650px] overflow-y-auto   [&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:bg-gray-300
dark:[&::-webkit-scrollbar-track]:bg-neutral-700
dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <div>
                <!-- Boucle pour afficher les projets -->

                <?php if ($nbreProjet1 != 0): ?>
                    <?php foreach ($stmt_projet as $projet): ?>
                        <div class="task-item flex items-center border-b border-gray-300 py-2">

                            <div class="flex-grow text-left">
                                <div class="grid grid-cols-12">
                                    <div class="col-span-1">
                                        <span class="material-symbols-outlined mr-6">tag</span>
                                    </div>
                                    <div class="font-bold col-span-9">
                                        <?php echo $projet['nom']; ?>
                                        <br>
                                        <span class="text-sm italic pl-2"><?php echo $projet['description']; ?></span>
                                    </div>

                                    <div class="col-span-1 ml-32">
                                        <button class="flex items-center"
                                            onclick="document.getElementById('plus-<?php echo $projet['id']; ?>').classList.toggle('hidden')">
                                            <span class="material-symbols-outlined">keyboard_arrow_down</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Sous-détails du projet (caché par défaut) -->
                                <div id="plus-<?php echo $projet['id']; ?>" class="mt-2 ml-12 hidden">
                                    <?php
                                    tache_projet($projet['id'], $conn)
                                        ?>
                                </div>
                            </div>
                            <!-- Progrès et bouton d'expansion -->

                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex items-center justify-center h-96 text-5xl">
                        <p>AUCUN PROJET EN COURS</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="e_note" class="hidden max-h-[750px] overflow-y-auto   [&::-webkit-scrollbar]:w-2
[&::-webkit-scrollbar-track]:bg-gray-100
[&::-webkit-scrollbar-thumb]:bg-gray-300
dark:[&::-webkit-scrollbar-track]:bg-neutral-700
dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
        <div class="flex justify-between items-center text-4xl mb-6">
            <h1 id="note1">Mes notes</h1>
            <span>
                <?php echo $nbreNote1 ?>
            </span>
        </div>
        <div class=" p-4 mb-2 hidden " id="ajout_n">
            <form action="main.php" method="POST">
                <div class="flex flex-col justify-center rounded-lg border  border-gray-400 p-4">
                    <div>
                        <input type="text" name="titre_note" placeholder="Titre de la notes"
                            class="border rounded-lg p-2 w-full mt-2">
                    </div>
                    <div>
                        <textarea id="message" rows="4"
                            class="block p-2.5 w-full mt-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Écrivez vos pensées ici..." name="contenu_note"></textarea>

                    </div>
                    <div>
                        <button type="submit" name="ajout_note"
                            class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                            Ajouter
                        </button>

                    </div>
                </div>
            </form>
        </div>
        <div class="flex flex-col rounded-lg border  border-gray-400 p-4 min-h-max-96 m-2 hidden " id="note">
            <form action="main.php" method="post">

                <input type="hidden" id="hide" name="id_modif" value="">
                <div>
                    <span class="w-full mt-2" id="titre">
                    </span>
                </div>
                <div>
                    <textarea id="label" name="contenu_modif"
                        class="block overflow-hidden 
                break-words
                 p-2.5 w-full mt-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </textarea>

                </div>
                <div class="flex flex-row space-x-4">
                    <button name="btn_modif" type="submit"
                        class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        ENREGISTRER
                    </button>
                    <a type="" onclick="document.getElementById('note').classList.add('hidden')"
                        class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        FERMER
                    </a>
                    <button name="note_sup" type="submit"
                        class="w-full flex justify-center py-2 px-4 mt-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                        Supprimer
                    </button>
                </div>
            </form>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 p-4">

            <!-- Bouton pour ajouter une note -->
            <button onclick="document.getElementById('ajout_n').classList.toggle('hidden')"
                class="h-32 w-full text-gray-400 hover:text-white bg-gray-200 hover:bg-black transition duration-700 ease-in-out rounded-lg flex items-center justify-center shadow-lg cursor-pointer">
                <span class="text-3xl">+</span>
            </button>

            <!-- Inclusion du fichier PHP -->
            <?php include "note.php"; ?>

            <!-- Boucle pour afficher les notes -->
            <?php foreach ($stmt_note as $note): ?>
                <button id="note-<?php echo $note['id']; ?>"
                    class="h-32 w-full p-4 rounded-lg shadow-lg <?php echo choixCouleur($couleurs); ?> flex flex-col justify-between overflow-hidden"
                    data-title="<?php echo htmlspecialchars($note['titre']); ?>"
                    data-id="<?php echo htmlspecialchars($note['id']); ?>"
                    data-content="<?php echo htmlspecialchars($note['contenu']); ?>" onclick="showNoteDetails(this)">
                    <!-- Titre de la note -->
                    <h2 class="text-lg font-semibold text-gray-800  ">
                        <?php echo $note['titre']; ?>
                    </h2>
                    <!-- Contenu de la note -->
                    <label class="text-gray-600 text-sm   overflow-hidden">
                        <?php echo $note['contenu']; ?>
                    </label>
                </button>
            <?php endforeach; ?>

        </div>

        <!-- Script pour gérer les détails de la note -->
        <script>
            function showNoteDetails(button) {
                document.getElementById('note').classList.toggle('hidden');
                // Récupération des données
                var title = button.getAttribute('data-title');
                var content = button.getAttribute('data-content');
                var id = button.getAttribute('data-id');
                // Mise à jour des éléments
                document.getElementById("titre").innerHTML = title;
                document.getElementById("label").innerHTML = content;
                document.getElementById("hide").value = id;
            }
        </script>


    </div>

</div>
</div>