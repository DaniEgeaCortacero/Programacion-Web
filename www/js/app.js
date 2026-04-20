
let mapaModal = null;
let capaGpxModal = null;

function crearMapaCard(idMapa, rutaGPX){

    const map = L.map(idMapa, {
    zoomControl: false,
    attributionControl: false,
    dragging: false,
    scrollWheelZoom: false,
    doubleClickZoom: false,
    boxZoom: false,
    keyboard: false,
    tap: false,
    touchZoom: false
    });

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    new L.GPX(rutaGPX,{
        async:true,
        marker_options: {
            startIconUrl: null,
            endIconUrl: null,
            shadowUrl: null
        },
        polyline_options: {
            color: "darkorange",
            opacity: 0.75,
            weight: 4,
            lineCap: "round"
        }
    }).on("loaded", function(e){
        map.fitBounds(e.target.getBounds());
    }).addTo(map);
}


/* ####################### ACTIVIDAD - MODAL ####################### */

function abrirEvento(rutaGPX) {
  const modal = document.getElementById("modal_evento");
  if (modal) {
    modal.classList.add("activo");
  }

  if (!mapaModal) {
    mapaModal = L.map("mapa_modal");

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "© OpenStreetMap"
    }).addTo(mapaModal);

    mapaModal.setView([40.4168, -3.7038], 6);
  }

  if (capaGpxModal) {
    mapaModal.removeLayer(capaGpxModal);
  }

  capaGpxModal = new L.GPX(rutaGPX, {
    async: true,
    marker_options: {
            startIconUrl: null,
            endIconUrl: null,
            shadowUrl: null
    },
    polyline_options: {
      color: "red",
      opacity: 0.75,
      weight: 4,
      lineCap: "round"
    }
  }).on("loaded", function(e) {
    mapaModal.fitBounds(e.target.getBounds(), {
      padding: [20, 20]
    });
  }).addTo(mapaModal);

  setTimeout(() => {
    mapaModal.invalidateSize();
  }, 150);
}

/* ####################### USUARIOS - IMAGENES - MODAL ####################### */

function abrirModalImagenes(userId) {
    document.getElementById("modalImagenes").style.display = "flex";

    // aquí luego meterás AJAX
    document.getElementById("contenidoImagenes").innerHTML =
        "<p>Cargando imágenes del usuario " + userId + "...</p>";
}

function cerrarModal() {
    document.getElementById("modalImagenes").style.display = "none";
}




/* ####################### DOCUMENTO ####################### */

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


  

  // Cerrar modal
  const cerrarModal = document.getElementById("cerrar_modal");
  const modalEvento = document.getElementById("modal_evento");

  if (cerrarModal && modalEvento) {
      cerrarModal.addEventListener("click", () => {
          modalEvento.classList.remove("activo");
      });

      modalEvento.addEventListener("click", (e) => {
          if (e.target === modalEvento) {
              modalEvento.classList.remove("activo");
          }
      });
  }



  // GPX (abrir evento)
  const inputRuta = document.getElementById("ruta");
  const mensajePreview = document.getElementById("mensaje_preview");

  let mapaPreview = null;
  let capaRuta = null;

  if (inputRuta) {
    mapaPreview = L.map("mapa_preview");

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "© OpenStreetMap"
    }).addTo(mapaPreview);

    mapaPreview.setView([40.4168, -3.7038], 6);

    inputRuta.addEventListener("change", (e) => {
      const archivo = e.target.files[0];

      if (!archivo) {
        mensajePreview.textContent = "Selecciona un archivo GPX para ver la ruta en el mapa.";
        return;
      }

      const lector = new FileReader();

      lector.onload = function(evento) {
        try {
          const textoXML = evento.target.result;
          const parser = new DOMParser();
          const xml = parser.parseFromString(textoXML, "application/xml");

          const geojson = toGeoJSON.gpx(xml);

          if (capaRuta) {
            mapaPreview.removeLayer(capaRuta);
          }

          capaRuta = L.geoJSON(geojson, {
            style: {
              color: "#ff9800",
              weight: 4,
              opacity: 0.9
            },
            pointToLayer: function(feature, latlng) {
              return L.circleMarker(latlng, {
                radius: 5,
                color: "#ff9800",
                fillColor: "#ff9800",
                fillOpacity: 1
              });
            }
          }).addTo(mapaPreview);

          const bounds = capaRuta.getBounds();

          if (bounds.isValid()) {
            mapaPreview.fitBounds(bounds, { padding: [20, 20] });
            mensajePreview.textContent = "Ruta GPX cargada correctamente.";
          } else {
            mensajePreview.textContent = "El archivo GPX no contiene una ruta válida.";
          }

        } catch (error) {
          mensajePreview.textContent = "No se pudo leer el archivo GPX.";
          console.error(error);
        }
      };

      lector.readAsText(archivo);
    });
  }

  crearMapaCard("map_card_1", "../Prueba.gpx");
  crearMapaCard("map_card_2", "../Prueba2.gpx");

  setTimeout(() => {      /* Reajusta el mapa */
    map.invalidateSize();
  }, 50);

});
