// ===== MODO CLARO/ESCURO =====
const themeToggle = document.getElementById("themeToggle")
function updateThemeIcon() {
    if (document.body.classList.contains("light-mode")) {
        themeToggle.textContent = '☀️'
    } else {
        themeToggle.textContent = ' 🌙'
    }
}

if (themeToggle) {
    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("light-mode");
        updateThemeIcon();
});
    updateThemeIcon();}
  
    
// ===== MENU MOBILE =====
const mobileMeniBtn = document.getElementById("mobileMenuBtn");
const navLinks = document.querySelector(".nav-links");

function closeMobileMenu() {
    if (window.innerWidth <= 768) {
        navLinks.classList.remove("active");
        navLinks.style.display = "none";
    }
}

function oppenMobileMenu() {
    
}

