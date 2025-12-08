document.addEventListener("turbo:load", () => { const navBar = document.getElementById("navBar"); });
const openButton = document.getElementById("open-sidebar-button");
const overlay = document.getElementById("overlay");

/* in questa sezione vado a creare una media query per gestire la "scomparsa" 
di alcuni elementi della navbar al tocco di TAB*/ 

/* ------- */

const media = window.matchMedia("(max-width: 700px)");

media.addEventListener('change', updateNavbar);

function updateNavbar(e) {
    const isMobile = e.matches;
    console.log(isMobile);
    if (isMobile) {
        navBar.setAttribute('inert', '');
    } else {
        navBar.removeAttribute('inert');
        navBar.classList.remove('show');
        openButton.setAttribute('aria-expanded', 'false');
    }
}

/* ------- */
function menuClosure() {
    navBar.classList.remove('show');
}

function openSidebar() {
    navBar.classList.add('show');
    openButton.setAttribute('aria-expanded', 'true');
    navBar.removeAttribute('inert');
}

function closeSidebar() {
    navBar.classList.remove('show');
    openButton.setAttribute('aria-expanded', 'false');
    navBar.setAttribute('inert', '');
}

// Attiva una volta all'avvio
updateNavbar(media);