<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arbolado Zacatenco</title>
    <link rel="icon" href="../img/tree-fill.svg">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/dropdown.css" />

    <style>
        .input-field input:focus + label {
            color: #33691e !important;
        }
        
        .input-field input:focus {
            border-bottom: 1px solid #33691e !important;
            box-shadow: 0 1px 0 0 #33691e !important;
        }

        .input-field textarea:focus + label {
            color: #1b5e20 !important;
        }
        
        .input-field textarea:focus {
            border-input: 1px solid #1b5e20 !important;
            box-shadow: 0 1px 0 0 #1b5e20 !important;
        }

        .input-field .prefix.active {
            color: #33691e !important;
            }

        .asignarBrigada .dropdown-content, #nueva_brigada .dropdown-content {
            max-height: 9rem;
            overflow-y: auto;
        }

        input[type="radio"]:checked + span:after {
            border: 2px solid #1b5e20 !important; /* Borde cuando está seleccionado */
            background-color: #1b5e20 !important; /* Fondo del punto */
        }

        input[type="radio"] + span:before {
            border: 2px solid #1b5e20 !important; /* Borde cuando no está seleccionado */
        }

        div.dt-layout-end {
            text-align: center !important;
            margin: auto !important;
        }

        div.dt-search label {
            display: inline-block;
            font-size: 0.8rem;
            margin-right: 1rem;
        }

        div.dt-search input {
            border: 0.1em solid rgb(165, 164, 164) !important;
            border-radius: 0.5rem !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            width: 30rem !important; /* Ajusta el ancho */
            height: 1rem !important;
            font-size: 0.8rem !important;
        }

        div.dt-search input:focus {
            border: 0.01 solid black !important;
        }

        div.dt-search input:hover {
            border: 0.12em solid #1b5e20 !important;
        }

        .collection-wrapper {
            height: 30rem; /* Altura fija */
            overflow-y: auto; /* Habilitar scroll vertical */
        }

        .collection-modal {
            height: 15rem; /* Altura fija */
            overflow-y: auto; /* Habilitar scroll vertical */
        }

        /* Ajustar el tamaño de la ventana emergente del picker */
        .datepicker-modal{min-width:42rem;}
        .timepicker-modal{min-width:42rem;}

        .opcionIndicente .dropdown-content {
            max-height: 15rem;
            overflow-y: auto;
        }
    </style>
</head>