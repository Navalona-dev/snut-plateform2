
$(document).ready(function() {
        $('#listdoc').DataTable({
            lengthChange: true,
            "dom": "<'card-body pl-0 pr-0 pt-0'<'row align-items-center'<'col-12 col-md-6'l><'col-6'f>>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            
            
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
    
    
    
    
    