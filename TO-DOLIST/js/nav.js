
document.addEventListener('DOMContentLoaded', function () {
    const ajout_tache_btn = document.getElementById("add_task");
    const ajout_tache_form = document.getElementById("ajout_t");
    const today = document.getElementById('e_aujourdhui');
    const tout = document.getElementById('e_tout');
    const complet = document.getElementById('e_complet');
    const btn_today = document.getElementById('aujourdhui');
    const btn_tout = document.getElementById('tout');
    const btn_complet = document.getElementById('complete');
    function afficher_ajout_tache() {
        ajout_tache_form.classList.toggle("hidden");
    };
    ajout_tache_btn.addEventListener('click', afficher_ajout_tache);
    function showToday() {
        tout.classList.add('hidden');
        complet.classList.add('hidden');
        today.classList.remove('hidden');
    }
    function showTout() {
        tout.classList.remove('hidden');
        complet.classList.add('hidden');
        today.classList.add('hidden');
    }
    function showComplet() {
        tout.classList.add('hidden');
        complet.classList.remove('hidden');
        today.classList.add('hidden');
    }
    btn_today.addEventListener('click', showToday);
    btn_tout.addEventListener('click', showTout);
    btn_complet.addEventListener('click', showComplet);
    const e_tache = document.getElementById('e_tache');
    const e_projet = document.getElementById('e_projet');
    const e_note = document.getElementById('e_note');
    const T_tache = document.getElementById('T_tache');
    const T_projet = document.getElementById('T_projet');
    const T_note = document.getElementById('T_note');
    function showTache() {
        e_tache.classList.remove('hidden');
        e_projet.classList.add('hidden');
        e_note.classList.add('hidden');
    }
    function showProjet() {
        e_projet.classList.remove('hidden');
        e_note.classList.add('hidden');
        e_tache.classList.add('hidden');
    }
    function showNote() {
        e_note.classList.remove('hidden');
        e_projet.classList.add('hidden');
        e_tache.classList.add('hidden');
    }
    T_note.addEventListener('click', showNote);
    T_tache.addEventListener('click', showTache);
    T_projet.addEventListener('click', showProjet);
   


})