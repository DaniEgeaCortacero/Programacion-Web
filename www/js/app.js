
let mapaModal = null;
let capaGpxModal = null;
let actividadModalActual = null;



/* ####################### FUNCIONES GPX ACTIVIDADES REDUCIDAS ####################### */

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


function inicializarMapasCards() {
    const mapas = document.querySelectorAll(".mapa_gpx");

    mapas.forEach((mapaDiv) => {
        if (mapaDiv.dataset.inicializado === "1") {
            return;
        }

        const rutaGPX = mapaDiv.dataset.gpx;

        if (!rutaGPX) {
            return;
        }

        mapaDiv.dataset.inicializado = "1";

        crearMapaCard(mapaDiv.id, rutaGPX);
    });
}


/* ####################### ACTIVIDAD - MODAL ####################### */


function abrirEvento(idActividad) {
    fetch("../controladores/load_actividad_detalle.php?id=" + encodeURIComponent(idActividad), {
        cache: "no-store"
    })
    .then(res => res.json())
    .then(data => {
        if (!data.ok) {
            alert(data.mensaje || "No se pudo cargar la actividad");
            return;
        }

        pintarModalEvento(data);
    })
    .catch(error => {
        console.error("Error cargando evento:", error);
    });
}

function pintarModalEvento(data) {
    const modal = document.getElementById("modal_evento");
    const modalDescripcion = document.getElementById("modal_descripcion");
    const modalTipo = document.getElementById("modal_tipo");
    const modalTitulo = document.getElementById("modal_titulo");
    const modalImagenes = document.getElementById("modal_imagenes");
    const modalCompaneros = document.getElementById("modal_companeros");
    const modalPublicador = document.getElementById("modal_publicador");
    const modalFecha = document.getElementById("modal_fecha");
    const modalFechaEvento = document.getElementById("modal_fecha_evento");
    const modalUbicacion = document.getElementById("modal_ubicacion");
    const modalFooter = document.getElementById("modal_footer");

    const actividad = data.actividad;

    if (modalFechaEvento) {
        modalFechaEvento.innerHTML = `
            <strong>Fecha del evento:</strong>
            <span>${actividad.fecha_evento || "Sin fecha del evento"}</span>
        `;
    }

    if (modalPublicador) {
        modalPublicador.innerHTML = `
            <div class="modal_usuario_chip">
                <img 
                    src="${actividad.imagen_publicador || "../img/default.png"}"
                    alt="${actividad.usuario_publicador || "Usuario"}">

                <span>
                    Publicado por 
                    <strong>@${actividad.usuario_publicador || "usuario"}</strong>
                </span>
            </div>
        `;
    }

    if (modalFecha) {
        modalFecha.innerHTML = `
            <strong>Fecha de publicación:</strong>
            <span>${actividad.fecha_publicacion || "Sin fecha"}</span>
        `;
    }

    if (modalUbicacion) {
        const partesUbicacion = [
            actividad.localidad,
            actividad.provincia,
            actividad.pais
        ].filter(Boolean);

        modalUbicacion.innerHTML = `
            <strong>Ubicación:</strong>
            <span>${partesUbicacion.length > 0 ? partesUbicacion.join(", ") : "Sin ubicación"}</span>
        `;
    }

    if (modalDescripcion) {
        modalDescripcion.innerHTML = `
            <strong>Descripción:</strong>
            <p>${actividad.descripcion || "Sin descripción"}</p>
        `;
    }

    if (!modal) {
        console.error("No existe #modal_evento");
        return;
    }

    modal.classList.add("activo");

    if (modalTipo) {
        modalTipo.textContent = actividad.tipo_actividad;
    }

    if (modalTitulo) {
        modalTitulo.textContent = actividad.titulo;
    }

    if (modalImagenes) {
        if (!data.imagenes || data.imagenes.length === 0) {
            modalImagenes.innerHTML = "<p>No hay imágenes.</p>";
        } else {
            const rutas = data.imagenes.map(img => img.ruta);

            modalImagenes.innerHTML = data.imagenes.map((img, index) => `
                <img
                    src="${img.ruta}"
                    class="modal_evento_img"
                    alt="${img.nombre || "Imagen"}"
                    onclick='abrirGaleriaActividad(${JSON.stringify(rutas)}, ${index})'
                >
            `).join("");
        }
    }

    if (modalCompaneros) {
        if (!data.companeros || data.companeros.length === 0) {
            modalCompaneros.innerHTML = "<p>No hay compañeros.</p>";
        } else {
            modalCompaneros.innerHTML = data.companeros.map(c => `
                <div class="modal_companero_item">
                    <img 
                        src="${c.imagen_perfil || "../img/default.png"}"
                        class="modal_companero_img"
                        alt="${c.usuario}">

                    <div>
                        <strong>@${c.usuario}</strong>
                        <span>${(c.nombre || "") + " " + (c.apellidos || "")}</span>
                    </div>
                </div>
            `).join("");
        }
    }

    if (modalFooter) {
        let textoUsuariosAplausos = "";

        if (data.usuarios_aplausos && data.usuarios_aplausos.length > 0) {
            const nombres = data.usuarios_aplausos.map(u => "@" + u.usuario);

            textoUsuariosAplausos = `
                <div class="modal_usuarios_aplausos">
                    Aplaudido por ${nombres.join(", ")}
                    ${
                        data.n_aplausos > data.usuarios_aplausos.length
                        ? ` y ${data.n_aplausos - data.usuarios_aplausos.length} más`
                        : ""
                    }
                </div>
            `;
        } else {
            textoUsuariosAplausos = `
                <div class="modal_usuarios_aplausos">
                    Aún no hay aplausos.
                </div>
            `;
        }

        modalFooter.innerHTML = `
            <div class="modal_footer_izq">
                <button 
                    type="button"
                    class="btn_aplauso ${data.mi_aplauso ? "activo" : ""}"
                    data-id-actividad="${actividad.id}"
                    onclick="toggleAplauso(this)">
                    👏 <span>${data.n_aplausos}</span>
                </button>

                ${textoUsuariosAplausos}
            </div>

            <div class="botones_evento">
                ${
                    data.puede_gestionar
                    ? `
                        <button 
                            type="button" 
                            class="btn_evento editar"
                            onclick="editarActividad(${actividad.id})">
                            Editar
                        </button>

                        <button 
                            type="button" 
                            class="btn_evento eliminar"
                            onclick="eliminarActividad(${actividad.id})">
                            Eliminar
                        </button>
                    `
                    : ""
                }
            </div>
        `;
    }

    cargarMapaModal(actividad.archivo_gpx);
}


function cargarMapaModal(rutaGPX) {
    if (!rutaGPX) {
        return;
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

function editarActividad(idActividad) {
    window.location.href = "/html/prototipo_main.php?vista=editarEvento&id=" + encodeURIComponent(idActividad);
}

function eliminarActividad(idActividad) {
    if (!confirm("¿Seguro que quieres eliminar esta actividad?")) {
        return;
    }

    fetch("../controladores/eliminar_actividad.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "id_actividad=" + encodeURIComponent(idActividad)
    })
    .then(res => res.text())
    .then(mensaje => {
        alert(mensaje);
        location.reload();
    })
    .catch(error => {
        console.error("Error eliminando actividad:", error);
    });
}



/* ####################### EVENTOS - IMAGENES - MODAL ####################### */

let galeriaImagenes = [];
let galeriaIndice = 0;

function abrirGaleriaActividad(imagenes, indiceInicial = 0) {
    galeriaImagenes = imagenes;
    galeriaIndice = indiceInicial;

    const modal = document.getElementById("modal_galeria");

    if (!modal || galeriaImagenes.length === 0) {
        return;
    }

    actualizarGaleriaActividad();

    modal.classList.add("activo");
}

function actualizarGaleriaActividad() {
    const img = document.getElementById("modal_galeria_img");
    const contador = document.getElementById("modal_galeria_contador");

    if (!img || galeriaImagenes.length === 0) {
        return;
    }

    if (galeriaIndice < 0) {
        galeriaIndice = galeriaImagenes.length - 1;
    }

    if (galeriaIndice >= galeriaImagenes.length) {
        galeriaIndice = 0;
    }

    img.src = galeriaImagenes[galeriaIndice];

    if (contador) {
        contador.textContent = `${galeriaIndice + 1} / ${galeriaImagenes.length}`;
    }
}

function moverGaleriaActividad(direccion) {
    galeriaIndice += direccion;
    actualizarGaleriaActividad();
}

function cerrarGaleriaActividad() {
    const modal = document.getElementById("modal_galeria");

    if (modal) {
        modal.classList.remove("activo");
    }
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



  // Abrir modal EVENTO - IMAGENES

  const modalGaleria = document.getElementById("modal_galeria");

  if (modalGaleria) {
      modalGaleria.addEventListener("click", (e) => {
          if (e.target === modalGaleria) {
              cerrarGaleriaActividad();
          }
      });
  }


  

  // Cerrar modal Evento
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

  if (typeof inicializarMapasCards === "function") {
    inicializarMapasCards();
  }

});
