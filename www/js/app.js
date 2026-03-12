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

  
  // Editar Perfil

  const editPerfilBtn = document.getElementById("editPerfilBtn");
  const cancelEditBtn = document.getElementById("cancelEditBtn");
  const perfilCard = document.querySelector(".perfil_card");

  if (editPerfilBtn && perfilCard) {
      editPerfilBtn.addEventListener("click", () => {
          perfilCard.classList.add("editando");
      });
  }

  if (cancelEditBtn && perfilCard) {
      cancelEditBtn.addEventListener("click", () => {
          perfilCard.classList.remove("editando");
      });
  }

});
