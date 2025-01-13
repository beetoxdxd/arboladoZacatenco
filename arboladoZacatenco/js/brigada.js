document.addEventListener('DOMContentLoaded', function () {
    const selects = document.querySelectorAll('select');
    M.FormSelect.init(selects);
});

function showAddMember() {
    toggleForms('addMemberForm');
}

function showConsultMember() {
    toggleForms('consultMemberForm');
}

function showConsultBrigade() {
    toggleForms('consultBrigadeForm');
}

function toggleForms(formId) {
    const forms = document.querySelectorAll('.action-card');
    forms.forEach(form => form.style.display = 'none');
    document.getElementById(formId).style.display = 'block';
}

function validateAddMember() {
    const nombre = document.getElementById('nombre').value.trim();
    const apellidos = document.getElementById('apellidos').value.trim();
    const correo = document.getElementById('correo').value.trim();
    const seccion = document.getElementById('seccion').value;

    if (!nombre || !apellidos || !correo || !seccion) {
        alert('Por favor, llena todos los campos.');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo)) {
        alert('Por favor, introduce un correo válido.');
        return;
    }

    alert(`Miembro registrado:\nNombre: ${nombre}\nApellidos: ${apellidos}\nCorreo: ${correo}\nSección: ${seccion}`);
    fetch('./php/register_brigadista.php', {method:'POST'});
}

function consultMember() {
    const correo = document.getElementById('consultaCorreo').value.trim();
    if (!correo) {
        alert('Por favor, introduce un correo.');
        return;
    }

    alert(`Buscando brigadista con correo: ${correo}`);
}

function consultBrigade() {
    const seccion = document.getElementById('consultaSeccion').value;
    if (!seccion) {
        alert('Por favor, selecciona una sección.');
        return;
    }

    alert(`Mostrando brigadistas de la sección: ${seccion}`);
}
document.addEventListener("DOMContentLoaded", function () {
    const selects = document.querySelectorAll("select");
    M.FormSelect.init(selects);
});

function showAddMember() {
    toggleForms("addMemberForm");
}

function showConsultMember() {
    toggleForms("consultMemberForm");
}

function showConsultBrigade() {
    toggleForms("consultBrigadeForm");
}

function toggleForms(formId) {
    const forms = document.querySelectorAll(".action-card");
    forms.forEach((form) => form.style.display = "none");
    document.getElementById(formId).style.display = "block";
}
document.addEventListener("DOMContentLoaded", function () {
    // Inicializar dropdowns
    const dropdowns = document.querySelectorAll(".dropdown-trigger");
    M.Dropdown.init(dropdowns, {
        coverTrigger: false, // Permite que el dropdown se muestre fuera del botón
        constrainWidth: false // Evita que el dropdown esté limitado al ancho del trigger
    });

    // Inicializar selects de Materialize
    const selects = document.querySelectorAll("select");
    M.FormSelect.init(selects);
});

function showAddMember() {
    toggleForms("addMemberForm");
}

function showConsultMember() {
    toggleForms("consultMemberForm");
}

function showConsultBrigade() {
    toggleForms("consultBrigadeForm");
}

function toggleForms(formId) {
    const forms = document.querySelectorAll(".action-card");
    forms.forEach((form) => form.style.display = "none");
    document.getElementById(formId).style.display = "block";
}
