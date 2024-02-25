$(function() {
    $('#dataTableRMANut, #dataTableGroupeCrenas, #dataTableCRENAS, #dataTableRegionCreni, #dataTableCreniRegion, #dataTableRMANutRegion, #tableDashboard, #dataTablePvrd, #dataTablePvrdRegion').DataTable({
        fixedColumns: {
            leftColumns: 3, // Fixer les trois premières colonnes
        },
        dom: 'Blfrtip',
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                exportOptions: {
                    columns: ':visible' // Exporter toutes les colonnes visibles
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('tr', sheet).each(function(index, row) {
                        // Modifiez les propriétés de la ligne ici si nécessaire
                        // Cette boucle parcourt toutes les lignes du fichier Excel
                    });
                }
            }
        ],
        initComplete: function() { 
            $('.dataTables_length').after($('.dt-buttons')); // Placer le bouton excel après l'élément de longueur
            $('.dataTables_filter').after($('.dt-buttons')); // Placer le bouton excel après l'élément de recherche
        },
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
});