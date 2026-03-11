document.addEventListener("DOMContentLoaded", () => {

  const pruebaBtn = document.getElementById("prueba_btn");
  const out = document.getElementById("out");

  if (pruebaBtn && out) {
    pruebaBtn.addEventListener("click", () => {
      out.textContent = "JS funcionando ✅";
    });
  }

  // Sidebar abrir/cerrar
  const sidebar = document.getElementById("sidebar");
  const toggle = document.getElementById("toggleSidebar");

  if (sidebar && toggle) {
    toggle.addEventListener("click", () => {
      sidebar.classList.toggle("open");
    });
  }

  document.addEventListener("click", (e) => {

    const clickDentroSidebar = sidebar.contains(e.target);
    const clickEnBoton = toggle.contains(e.target);

    if (!clickDentroSidebar && !clickEnBoton) {
      sidebar.classList.remove("open");
    }

  });
  

  // Submenús
  document.querySelectorAll(".submenu_item .submenu-btn").forEach((b) => {
    b.addEventListener("click", () => {
      b.parentElement.classList.toggle("open");
    });
  });

  // Submenu perfil
  const btnProfile = document.getElementById("toggleProfileMenu");
  const dropdown = document.getElementById("profileDropdown");

  btnProfile.addEventListener("click", () => {
      dropdown.style.display =
          dropdown.style.display === "block" ? "none" : "block";
  });

  window.addEventListener("click", function(e){

    if(!btnProfile.contains(e.target) && !dropdown.contains(e.target)){
        dropdown.style.display = "none";
    }

  });

  

});
