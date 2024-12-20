// sweetalerts.js
function confirmDeletion(event, formId) {
    event.preventDefault(); // Impede o envio imediato do formulário

    Swal.fire({
        title: 'Tem certeza?',
        text: 'Você não poderá reverter essa ação!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit(); // Submete o formulário
        }
    });
}