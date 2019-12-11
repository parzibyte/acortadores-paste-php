(function () {
    if (window.ParzibyteHttp) {
        return;
    }
    window.ParzibyteHttp = {
        "post": (ruta, datos) =>
            fetch(window.URL_RAIZ + ruta, {
                credentials: 'include',
                method: "POST",
                body: JSON.stringify(datos)
            })
                .then(r => r.json()),
        "put": (ruta, datos) =>
            fetch(window.URL_RAIZ + ruta, {
                credentials: 'include',
                method: "PUT",
                body: JSON.stringify(datos)
            })
                .then(r => r.json()),
        "get": (ruta) =>
            fetch(window.URL_RAIZ + ruta, {
                credentials: 'include',
            })
                .then(r => r.json()),
        "delete": (ruta) =>
            fetch(window.URL_RAIZ + ruta, {
                credentials: 'include',
                method: "DELETE",
            })
                .then(r => r.json())
    };
})();