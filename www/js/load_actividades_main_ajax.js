let paginaFeed = 0;
let cargandoFeed = false;
let finFeed = false;

document.addEventListener("DOMContentLoaded", () => {
    cargarMasEventos();

    window.addEventListener("scroll", () => {
        const cercaDelFinal =
            window.innerHeight + window.scrollY >= document.body.offsetHeight - 250;

        if (cercaDelFinal) {
            cargarMasEventos();
        }
    });
});

function cargarMasEventos() {
    if (cargandoFeed || finFeed) {
        return;
    }

    const feed = document.getElementById("feed_actividades");
    const loader = document.getElementById("loader_feed");
    const fin = document.getElementById("fin_feed");

    if (!feed) {
        return;
    }

    cargandoFeed = true;

    if (loader) {
        loader.style.display = "block";
    }

    fetch("../controladores/load_actividades_main_ajax.php?pagina=" + paginaFeed, {
        cache: "no-store"
    })
        .then(res => res.text())
        .then(html => {
            const limpio = html.trim();

            if (limpio === "") {
                finFeed = true;

                if (fin) {
                    fin.style.display = "block";
                }

                return;
            }

            feed.insertAdjacentHTML("beforeend", limpio);
            paginaFeed++;

            if (typeof inicializarMapasCards === "function") {
                inicializarMapasCards();
            }
        })
        .catch(error => {
            console.error("Error cargando actividades:", error);
        })
        .finally(() => {
            cargandoFeed = false;

            if (loader) {
                loader.style.display = "none";
            }
        });
}