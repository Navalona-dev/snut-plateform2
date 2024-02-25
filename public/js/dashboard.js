$(function() {
    $('#tableDashboardDistribution, #tableDashboardCrenas, #tableDashboardCrenasDetails, #tableDashboardCrenasRupture, #tableDashboardCrenif75, #tableDashboardCrenif100, #tableDashboardPeremption, #tableDashboardRetroInfo').DataTable({
        dom: 'lfBrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        "language": {
            "decimal": ",",
            "thousands": ".",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "sInfoEmpty": "Affichage de 0 à 0 sur 0 entrée",
            "sInfoFiltered": "(filtré à partir de _MAX_ entrées au total)",
            "sInfoPostFix": "",
            "sInfoThousands": " ",
            "sLengthMenu": "Afficher _MENU_ entrées",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun enregistrement correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            }
        }
    });

    // Messages envoyés
    $('#tableDashboardSentMessage, #tableDashboardReceivedMessage').DataTable({
        dom: 'lfrtip',        
        "language": {
            "decimal": ",",
            "thousands": ".",
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
            "sInfoEmpty": "Affichage de 0 à 0 sur 0 entrée",
            "sInfoFiltered": "(filtré à partir de _MAX_ entrées au total)",
            "sInfoPostFix": "",
            "sInfoThousands": " ",
            "sLengthMenu": "Afficher _MENU_ entrées",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun enregistrement correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            }
        }
    });

    // Écouter les clics sur le bouton "Voir détail"
    $('#collapseDetailsButton').click(function () {
        // Fermer le collapse "Liste district en rupture de stock"
        $('#collapseRupture').collapse('hide');
    });
  
    // Écouter les clics sur le bouton "Liste district en rupture de stock"
    $('#collapseRuptureButton').click(function () {
    // Fermer le collapse "STATUT DE STOCK ACTUEL PAR REGION CRENAS"
        $('#collapseDetails').collapse('hide');
    });

     // Écouter les clics sur le bouton "Message envoyés"
     $('#collapseSentMessageBtn').click(function () {
        // Fermer le collapse "Message recu"
        $('#collapseMessageReceived').collapse('hide');
    });
  
    // Écouter les clics sur le bouton "Message reçu"
    $('#collapseMessageReceivedBtn').click(function () {
    // Fermer le collapse "Message envoyés"
        $('#collapseSentMessage').collapse('hide');
    });

    // Désactiver le selectCentral si le checkbox chkAllCentral est cochée
    $("#chkAllCentral").change(function () {
        $("#selectCentral").prop("disabled", this.checked);
        if (this.checked) {            
            $("#selectCentral").val(null).trigger('change');
        }
    });

    // Désactiver le selectRnr si le checkbox chkAllRnr est cochée
    $("#chkAllRnr").change(function () {
        $("#selectRnr").prop("disabled", this.checked);
        if (this.checked) {            
            $("#selectRnr").val(null).trigger('change');
        }
    });

    // Décocher chkAllRnr si selectRnr n'est pas vide
    $("#selectRnr").change(function () {
        if ($(this).val() != "") {
            $("#chkAllRnr").prop("checked", false);
        }
    });

    // Désactiver le selectRnd si le checkbox chkAllRnd est cochée
    $("#chkAllRnd").change(function () {
        $("#selectRnd").prop("disabled", this.checked);
        if (this.checked) {    
            $("#selectRnd").val(null).trigger('change');
        }
    });

    // Décocher chkAllRnd si selectRnd n'est pas vide
    $("#selectRnd").change(function () {
        $("#chkAllRnd").prop("checked", !$(this).val());
    });
});