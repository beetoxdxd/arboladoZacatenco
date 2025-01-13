<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="../js/mapax.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.materialboxed');
            var instances = M.Materialbox.init(elems);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems, {
                preventScrolling: true, 
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.fixed-action-btn');
            var instances = M.FloatingActionButton.init(elems);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.collapsible');
            var instances = M.Collapsible.init(elems);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.datepicker');
            var today = new Date();
            
            var instances = M.Datepicker.init(elems, {
                autoClose: true,
                format: "yyyy-mm-dd",
                maxDate: today,
                i18n: {
                    cancel: 'Cancelar',
                    clear: 'Limpiar',
                    done: 'Aceptar',
                    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    weekdays: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                    weekdaysAbbrev: ['D', 'L', 'M', 'M', 'J', 'V', 'S']
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.timepicker');
            var instances = M.Timepicker.init(elems, {
                twelveHour: false, // Formato de 24 horas
                i18n: {
                    cancel: 'Cancelar',
                    clear: 'Limpiar',
                    done: 'Aceptar'
                }
            });
        });
    </script>

    <script>
    $(document).ready(function() {
        $('.dropdown-trigger').dropdown({
        coverTrigger: false,   // Evita que el dropdown cubra el botón al abrirse
        hover: true,           // Abre el dropdown al pasar el mouse (opcional)
        constrainWidth: false  // Permite que el ancho del dropdown se ajuste al contenido
        });

        $('.modal').modal();
        $('select').formSelect();

        $('#tabla_reportes').DataTable({
            order: [[0, "asc"]], // Orden inicial por la primera columna (ID)
            info: false,
            paging: false,
            scrollCollapse: true,
            scrollY: '17rem',
            "columns": [
                null,               // ID (ordenable)
                null,               // Tipo de reporte (ordenable)
                { "orderable": false }, // Latitud (no ordenable)
                { "orderable": false }, // Longitud (no ordenable)
                { "orderable": false }, // Descripción (no ordenable)
                { "orderable": false }, // Nombre (no ordenable)
                { "orderable": false }, // Correo (no ordenable)
                { "orderable": false }, // Imagen (no ordenable)
                { "orderable": true, "type": "date" }, // Fecha (ordenable)
                null,                // Estado del reporte (ordenable)
                null, // Brigadas asignadas
                null // Historial de cambios
            ],
            "language": {
                search: "Introduce el tipo de reporte o la información de éste (para buscar IDs el formato es 'id=1'): ",
                zeroRecords: "No se encontraron resultados para tu búsqueda.", 
                infoEmpty: "No hay reportes disponibles.",
            },
        });

        $('#tabla_monitoreo_reportes').DataTable({
            order: [[0, "asc"]], // Orden inicial por la primera columna (ID)
            info: false,
            paging: false,
            scrollCollapse: true,
            scrollY: '30rem',
            "columns": [
                null,               // ID (ordenable)
                null,               // Tipo de reporte (ordenable)
                { "orderable": false }, // Descripción (no ordenable)
                { "orderable": false }, // Imagen (no ordenable)
                { "orderable": true, "type": "date" }, // Fecha (ordenable)
                { "orderable": false },                // Botones
            ],
            "language": {
                search: "Introduce el tipo de reporte o la información de éste (para buscar IDs el formato es 'id=1'): ",
                zeroRecords: "No se encontraron resultados para tu búsqueda.", 
                infoEmpty: "No hay reportes disponibles.",
            },
        });

        $('#tabla_censos').DataTable({
            order: [[0, "asc"]], // Orden inicial por la primera columna (ID)
            info: false,
            paging: false,
            scrollCollapse: true,
            scrollY: '17rem',
            "columns": [
                null,               // ID (ordenable)
                null,               // Nombre (ordenable)
                {"orderable": false}, //{ "orderable": true, "type": "date" }, // Fecha inicio (ordenable)
                {"orderable": false}, // Fecha fin (ordenable)
                null,                  // Brigadas
                null,                // Estado
            ],
            "language": {
                search: "Introduce el nombre del censo o la información de éste: ",
                zeroRecords: "No se encontraron resultados para tu búsqueda.", 
                infoEmpty: "No hay censos disponibles.",
            },
        });
    });
    </script>