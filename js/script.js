// Definición de filtros iniciales
let filtros = {
    categoria: new URLSearchParams(window.location.search).get('title') || "",  // Asegura que se obtiene el valor correcto
    material: "",
    coleccion: ""
};

// Función para cargar productos con los filtros seleccionados
async function cargarProductos() {
    try {
        // Construir URL con los filtros aplicados
        let url = `https://mitogafas.com.ar/php/obtener_productos.php?categoria=${filtros.categoria}`;
        if (filtros.material) url += `&material=${filtros.material}`;
        if (filtros.coleccion) url += `&coleccion=${filtros.coleccion}`;
        
        console.log("Fetching products from URL:", url);

        // Hacer la solicitud al servidor
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Error en la red: ${response.status}`);
        }

        // Parsear el JSON de la respuesta
        const productos = await response.json();

        // Referencia al contenedor de productos
        const contenedorProductos = document.getElementById('container-list');
        contenedorProductos.innerHTML = ''; // Limpia el contenedor antes de agregar nuevos productos

        // Comprobar si hay productos
        if (!Array.isArray(productos) || productos.length === 0) {
            contenedorProductos.innerHTML = '<p>No se encontraron productos.</p>';
            return;
        }

        
            // Ordenar los productos por producto.codigo
            productos.sort((a, b) => {
                if (a.codigo < b.codigo) return -1; // a viene antes que b
                if (a.codigo > b.codigo) return 1;  // b viene antes que a
                return 0; // son iguales
            });


        
        let htmlContent = '';
        productos.forEach(producto => {
            const widthPercentage = (100 / producto.imagenes_secundarias.length).toFixed(2);

            
            // Validar si el producto tiene imagen principal
            if (producto.imagen) {
                  // Limitar las imágenes secundarias a las primeras 4
                  
        const imagenesSecundariasLimitadas = producto.imagenes_secundarias.slice(0, 4);

                htmlContent += `
                    <li class="container-list-item">
                        <a href="detalle.html?id=${producto.id}">
                            <div class="glasses-container">
                                <h4 class="glasses-material">COLECCIÓN ${producto.coleccion}</h4>
                                <h4 class="glasses-title">${producto.codigo || 'Sin título'}</h4>
                                <img class="glasses-img" src="${producto.imagen}" loading="lazy" alt="Anteojo ${producto.codigo || 'sin título'}">
                                <ul class="list-img-colors">
                                    ${imagenesSecundariasLimitadas.map(imagen => `
                                            <img src="${imagen}" alt="Imagen ${producto.codigo || 'sin título'}" class="color-img">
                                    `).join('')}
                                </ul>
                            </div>
                        </a>
                    </li>
                `;
            }
        });
        
        document.getElementById("container-list").innerHTML = htmlContent;
        
        // Insertar los productos en el DOM
        contenedorProductos.innerHTML = htmlContent;

    } catch (error) {
        console.error("Error al cargar los productos:", error);
    }
}
// Configuración de los eventos para filtros dinámicos
document.querySelectorAll('.filter-item').forEach(item => {
    item.addEventListener('click', event => {
        const filterType = event.target.getAttribute('data-filter-type');
        const filterValue = event.target.getAttribute('data-filter-value');

        if (filterType === 'material') {
            filtros.material = filterValue;
        } else if (filterType === 'coleccion') {
            filtros.coleccion = filterValue;
        }

        cargarProductos(); // Recargar productos con los nuevos filtros
    });
});

// Llamada inicial para cargar productos al cargar la página
document.addEventListener("DOMContentLoaded", () => {
    cargarProductos();
});
