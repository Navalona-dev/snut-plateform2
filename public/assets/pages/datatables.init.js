
$(document).ready(function() {
        $('table.display').DataTable({
            lengthChange: true,
            "dom": "<'card-body pl-0 pr-0 pt-0'<'row align-items-center'<'col-12 col-md-6'l><'col-6'f>>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5 pb-5'iB><'col-sm-12 col-md-7'p>>",

            
            buttons:[
                { extend: 'print', text: 'Imprimer',className: 'btn-warning' },
                { extend: 'pdf', text: 'Exporter en PDF', className: 'btn-primary'  }],
            columnDefs: [
                {
                    targets: [ 0, 1, 2 ],
                    className: 'dt-center'
                }
                ],
            language:{
                        search:         "Rechercher&nbsp;:",
                        lengthMenu:    "Afficher _MENU_ &eacute;l&eacute;ments",
                        info:           "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                        sZeroRecords:    "Aucun élément correspondant trouvé",
                        sInfoEmpty:      "Affichage de l'élément 0 à 0 sur 0 élément",
                        paginate:{
                            first:      "Premier",
                            previous:"<i class='mdi mdi-chevron-left'>",
                            next:"<i class='mdi mdi-chevron-right'>",
                            last:       "Dernier"
                                }
                    }
            }


        );
        
    } );
    
    
    
    
    