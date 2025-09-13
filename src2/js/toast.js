/*
    file : toast.js
    desc : Gestione js delle notifiche toast
    auth : Alberto Magrini 
    date : 2024-06-10

*/

document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toast");
    if (!toast) return;

    const closeBtn = toast.querySelector(".toast-close");

    // Chiudi manualmente
    closeBtn.addEventListener("click", () => {
        toast.style.animation = "fadeOut 0.5s forwards";
        setTimeout(() => toast.remove(), 500);
    });

    // Auto-dismiss dopo 5 secondi
    setTimeout(() => {
        if (toast) {
            toast.style.animation = "fadeOut 0.5s forwards";
            setTimeout(() => toast.remove(), 500);
        }
    }, 5000);
});

