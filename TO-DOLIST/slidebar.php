<!-- Sidebar -->
<div class=" col-span-5 lg:col-span-3 flex flex-col bg-white rounded-2xl shadow-lg p-4">
    <div class="flex justify-between items-center">
        <h1 class="text-lg font-semibold text-gray-800">Menu</h1>
        <button class="material-symbols-outlined" onclick="window.location.href='deconnexion.php'">close</button>
    </div>
    <div class="px-2 mt-4 mb-2 items-center">
        <span class="material-symbols-outlined h-6 w-6 inline-block align-middle">
            account_circle
        </span>
        <span class="inline-block align-middle">
            <span class="mr-1"><?php echo htmlspecialchars($prenom); ?></span>
            <span class="ml-1"><?php echo htmlspecialchars($nom); ?></span> </span>
    </div>
    <!-- Search Bar -->
    <form action="/search" class="">
        <div class="relative">
            <input type="text" name="q" placeholder="Search"
                class="w-full h-10 p-4 border rounded-full shadow dark:text-gray-800 dark:border-gray-700 dark:bg-gray-200 focus:outline-none focus:ring-gray-400 focus:border-gray-400">
            <button type="submit" class="absolute top-2 right-4">
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 56.966 56.966">
                    <path
                        d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z">
                    </path>
                </svg>
            </button>
        </div>
    </form>
    <!-- Tasks Section -->
    <div class="mt-4">
        <div class="flex justify-between items-center text-sm font-semibold">
            <button class="task-button" id="T_tache">
                <h2>TACHES</h2>
            </button>
            <button class="task-button" name="btn_ajout">
                <span class="material-symbols-outlined rounded-full shadow-lg" id="add_task">add_task</span>
            </button>
        </div>
        <div class=" ">
            <button class="task-button" id="aujourdhui">
                <div class="flex-none">
                    <span class="material-symbols-outlined ml-2">
                        <span class="material-symbols-outlined pr-2">
                            today
                        </span>
                    </span>
                </div>
                <div class="flex-auto w-32 text-left">
                    Ajourd'hui
                </div>
                <div class="flex-auto pl-8">
                    <?php echo htmlspecialchars($today1); ?>
                </div>
            </button>
            <button class="task-button" id="tout">
                <div class="flex-none">
                    <span class="material-symbols-outlined ml-2">
                        <span class="material-symbols-outlined pr-2">
                            list
                        </span>
                    </span>
                </div>
                <div class="flex-auto w-32 text-left">
                    Mes tâches
                </div>
                <div class="flex-auto pl-8">
                    <?php echo htmlspecialchars($tout1); ?>
                </div>
            </button>
            <button class="task-button" id="complete">
                <div class="flex-none">
                    <span class="material-symbols-outlined ml-2">
                        <span class="material-symbols-outlined pr-2">
                            task_alt
                        </span>
                    </span>
                </div>
                <div class="flex-auto w-32 text-left">
                    Complètes
                </div>
                <div class="flex-auto pl-8">
                    <?php echo htmlspecialchars($complet1); ?>
                </div>
            </button>
        </div>
    </div>

    <!-- Projects Section -->
    <div class="mt-4">
        <div class="flex justify-between items-center text-sm font-semibold ">
            <button class="task-button" id="T_projet">
                <h2>PROJETS</h2>
            </button>
            <button class="task-button" onclick="document.getElementById('b_plus').classList.toggle('hidden')">
                <span class="material-symbols-outlined rounded-full shadow-lg">list_alt_add</span>
            </button>
        </div>
        <div class="min-h-40 max-h-40 overflow-y-auto custom-scrollbar ">
            <?php if ($nbreProjet1 != 0): ?>
                <?php foreach ($stmt_projet as $projet): ?>
                    <div class="flex  p-1 mt-2  rounded-lg">
                        <div class="flex-none">
                            <span class="material-symbols-outlined ml-2">
                                <span class="material-symbols-outlined pr-2">
                                    tag
                                </span>
                            </span>
                        </div>
                        <div class="flex-auto w-32 text-left">
                            <?php echo $projet['nom']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex items-center justify-center h-40 text-lg">
                    <p>AUCUN PROJET EN COURS</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notes Section -->
    <div class="mt-4">
        <div class="flex justify-between items-center text-sm font-semibold">
            <button class="task-button" id="T_note">
                <h2>NOTES</h2>
            </button>
            <button class="task-button" onclick="document.getElementById('ajout_n').classList.toggle('hidden')">
                <span class="material-symbols-outlined rounded-full shadow-lg">add_notes</span>
            </button>
        </div>
        <div class="min-h-40 max-h-44 overflow-y-auto custom-scrollbar">
            <?php if ($nbreNote1 != 0): ?>
                <?php foreach ($stmt_note as $note): ?>
                    <div class="flex  p-1 mt-2  rounded-lg">
                        <div class="flex-none">
                            <span class="material-symbols-outlined ml-2">
                                <span class="material-symbols-outlined pr-2">
                                    notes
                                </span>
                            </span>
                        </div>
                        <div class="flex-auto w-32 text-left">
                            <?php echo $note['titre']; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="flex items-center justify-center h-40 text-lg">
                    <p>AUCUNE NOTE </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>