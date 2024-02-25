    $(function() {
        // Somme des valeur sur l'Admission CRENAS pour l'année précédente
        $(".moisAdmissionCRENASAnneePrecedent").change(function() {
            var totalAdmissionCRENASAnneePrecedent = 0;
            $(".moisAdmissionCRENASAnneePrecedent").each(function() {
                var newVal = $(this).val();
                if (!isNaN(parseFloat(newVal))) {
                    totalAdmissionCRENASAnneePrecedent += parseFloat(newVal);
                }
            });
            $("#totalCRENASAnneePrecedent").val(totalAdmissionCRENASAnneePrecedent);

            var totalCRENASAnneePrecedent = $("#totalCRENASAnneePrecedent").val();
            var totalProjeteAnneePrecedent = $("#totalProjeteAnneePrecedent").val();
            var totalAnneeProjection = $("#totalAnneeProjection").val();
            var resultatAnneePrecedent = parseFloat(totalProjeteAnneePrecedent) - parseFloat(totalCRENASAnneePrecedent); 
            var resultatAnneeProjection = parseFloat(totalAnneeProjection) - parseFloat(resultatAnneePrecedent);
            if (!isNaN(parseFloat(resultatAnneePrecedent))) {
                $("#resultatAnneePrecedent").val(resultatAnneePrecedent);
            }
            if (!isNaN(parseFloat(resultatAnneeProjection))) {
                $("#resultatAnneeProjection").val(resultatAnneeProjection);
            }

            var valeurCalculTheoriqueATPE = $("#valeurCalculTheoriqueATPE").val();
            var valeurCalculTheoriqueAMOX = $("#valeurCalculTheoriqueAMOX").val();
            var valeurCalculTheoriqueFichePatient = $("#valeurCalculTheoriqueFichePatient").val();  
            var besoinAPTE = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueATPE);
            var besoinAMOX = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueAMOX);
            var besoinFichePatient = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueFichePatient); 
            $("#besoinAPTE").val(besoinAPTE);
            $("#besoinAMOX").val(besoinAMOX);
            $("#besoinFichePatient").val(besoinFichePatient);
        });

        // Somme des valeur sur l'Admission qui a été projeté pour l'année projeté
        $(".moisAdmissionProjeteAnneePrecedent").change(function() {
            var totalAdmissionProjeteAnneePrecedent = 0;
            $(".moisAdmissionProjeteAnneePrecedent").each(function() {
                var newVal = $(this).val();
                if (!isNaN(parseFloat(newVal))) {
                    totalAdmissionProjeteAnneePrecedent += parseFloat(newVal);
                }
            });
            $("#totalProjeteAnneePrecedent").val(totalAdmissionProjeteAnneePrecedent);

            var totalCRENASAnneePrecedent = $("#totalCRENASAnneePrecedent").val();
            var totalProjeteAnneePrecedent = $("#totalProjeteAnneePrecedent").val();
            var totalAnneeProjection = $("#totalAnneeProjection").val();
            var resultatAnneePrecedent = parseFloat(totalProjeteAnneePrecedent) - parseFloat(totalCRENASAnneePrecedent); 
            var resultatAnneeProjection = parseFloat(totalAnneeProjection) - parseFloat(resultatAnneePrecedent);  
            if (!isNaN(parseFloat(resultatAnneePrecedent))) {
                $("#resultatAnneePrecedent").val(resultatAnneePrecedent);
            }
            if (!isNaN(parseFloat(resultatAnneeProjection))) {
                $("#resultatAnneeProjection").val(resultatAnneeProjection);
            }

            var valeurCalculTheoriqueATPE = $("#valeurCalculTheoriqueATPE").val();
            var valeurCalculTheoriqueAMOX = $("#valeurCalculTheoriqueAMOX").val();
            var valeurCalculTheoriqueFichePatient = $("#valeurCalculTheoriqueFichePatient").val();  
            var besoinAPTE = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueATPE);
            var besoinAMOX = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueAMOX);
            var besoinFichePatient = parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueFichePatient); 
            $("#besoinAPTE").val(besoinAPTE);
            $("#besoinAMOX").val(besoinAMOX);
            $("#besoinFichePatient").val(besoinFichePatient); 
        });

        // Somme des valeur des projections du nombre d'admission
        $(".moisProjectionAnneePrevisionnelle").change(function() {
            var totalProjectionAnneePrevisionnelle = 0;
            $(".moisProjectionAnneePrevisionnelle").each(function(){
                var newVal = $(this).val();
                if (!isNaN(parseFloat(newVal))) {
                    totalProjectionAnneePrevisionnelle += parseFloat(newVal);
                }
            });
            $("#totalAnneeProjection").val(totalProjectionAnneePrevisionnelle);
 
            var totalCRENASAnneePrecedent = $("#totalCRENASAnneePrecedent").val();
            var totalProjeteAnneePrecedent = $("#totalProjeteAnneePrecedent").val();
            var totalAnneeProjection = $("#totalAnneeProjection").val(); 
            var resultatAnneePrecedent = parseFloat(totalProjeteAnneePrecedent) - parseFloat(totalCRENASAnneePrecedent); 
            var resultatAnneeProjection = parseFloat(totalAnneeProjection) - parseFloat(resultatAnneePrecedent); 
             if (!isNaN(parseFloat(resultatAnneePrecedent))) {
                $("#resultatAnneePrecedent").val(resultatAnneePrecedent);
            }
            if (!isNaN(parseFloat(resultatAnneeProjection))) {
                $("#resultatAnneeProjection").val(resultatAnneeProjection);
            } 

            var valeurCalculTheoriqueATPE = $("#valeurCalculTheoriqueATPE").val();
            var valeurCalculTheoriqueAMOX = $("#valeurCalculTheoriqueAMOX").val();
            var valeurCalculTheoriqueFichePatient = $("#valeurCalculTheoriqueFichePatient").val();  
            var besoinAPTE = (parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2);
            var besoinAMOX = (parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);
            var besoinFichePatient = (parseFloat(resultatAnneeProjection) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2); 
            $("#besoinAPTE").val(besoinAPTE);
            $("#besoinAMOX").val(besoinAMOX);
            $("#besoinFichePatient").val(besoinFichePatient);
        });

        $("#nbrCSBCRENAS").blur(function(){
            valNbrCSBCRENAS = $(this).val(); 
            valeurCalculTheoriqueRegistre = $("#valeurCalculTheoriqueRegistre").val();
            valeurCalculTheoriqueCarnetRapport = $("#valeurCalculTheoriqueCarnetRapport").val();
            valBesoinRegistre = valNbrCSBCRENAS * valeurCalculTheoriqueRegistre;
            valBesoinCarnetRapportCRENAS = valNbrCSBCRENAS * valeurCalculTheoriqueCarnetRapport; 
            $("#besoinRegistre").val(valBesoinRegistre);
            $("#besoinCarnetRapportCRENAS").val(valBesoinCarnetRapportCRENAS);
        });

        $(".quantitePnSDUCartonBSD").blur(function(){
            var totalPnSDUCartonBSD = 0;
            var totalPnSDUCartonCSB = $("#totalPnSDUCartonCSB").val();
            $(".quantitePnSDUCartonBSD").each(function(){
                var newVal = $(this).val();
                if (!isNaN(parseFloat(newVal))) {
                    totalPnSDUCartonBSD += parseFloat(newVal);
                }
            });
            $("#totalPnSDUCartonBSD").val(totalPnSDUCartonBSD);
            var totalPnSDUCartonSDSP = parseFloat(totalPnSDUCartonBSD) + parseFloat(totalPnSDUCartonCSB);
            $("#totalPnSDUCartonSDSP").val(totalPnSDUCartonSDSP);
        }); 

        $(".quantitePnSDUCartonCSB").blur(function(){
            var totalPnSDUCartonCSB = 0;
            var totalPnSDUCartonBSD = $("#totalPnSDUCartonBSD").val();
            $(".quantitePnSDUCartonCSB").each(function(){
                var newVal = $(this).val();
                console.log(newVal);
                if (!isNaN(parseFloat(newVal))) {
                    totalPnSDUCartonCSB += parseFloat(newVal);
                }
            });
            $("#totalPnSDUCartonCSB").val(totalPnSDUCartonCSB);
            var totalPnSDUCartonSDSP = parseFloat(totalPnSDUCartonBSD) + parseFloat(totalPnSDUCartonCSB);
            $("#totalPnSDUCartonSDSP").val(totalPnSDUCartonSDSP);
        });

        $(".quantiteAmoxSDUCartonBSD").blur(function(){
            var totalAmoxSDUCartonBSD = 0;
            var totalAmoxSDUCartonCSB = $("#totalAmoxSDUCartonCSB").val();
            $(".quantiteAmoxSDUCartonBSD").each(function(){
                var newVal = $(this).val();
                if (!isNaN(parseFloat(newVal))) {
                    totalAmoxSDUCartonBSD += parseFloat(newVal);
                }
            });
            $("#totalAmoxSDUCartonBSD").val(totalAmoxSDUCartonBSD);
            var totalAmoxSDUCartonSDSP = parseFloat(totalAmoxSDUCartonBSD) + parseFloat(totalAmoxSDUCartonCSB);
            $("#totalAmoxSDUCartonSDSP").val(totalAmoxSDUCartonSDSP);
        });

        $(".quantiteAmoxSDUCartonCSB").blur(function(){
            var totalAmoxSDUCartonCSB = 0;
            var totalAmoxSDUCartonBSD = $("#totalAmoxSDUCartonBSD").val();
            $(".quantiteAmoxSDUCartonCSB").each(function(){
                var newVal = $(this).val(); 
                if (!isNaN(parseFloat(newVal))) {
                    totalAmoxSDUCartonCSB += parseFloat(newVal);
                }
            });
            $("#totalAmoxSDUCartonCSB").val(totalAmoxSDUCartonCSB);
            var totalAmoxSDUCartonSDSP = parseFloat(totalAmoxSDUCartonBSD) + parseFloat(totalAmoxSDUCartonCSB);
            $("#totalAmoxSDUCartonSDSP").val(totalAmoxSDUCartonSDSP);
        });

        // GESTION DES DATE POUR LES PN BSD
        $("#quantite01PnSDUCartonBSD").blur(function() {
            var valQuantite01PnSDUCartonBSD = $("#quantite01PnSDUCartonBSD").val();
            if (parseInt(valQuantite01PnSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration01PnSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite02PnSDUCartonBSD").blur(function() {
            var valQuantite02PnSDUCartonBSD = $("#quantite02PnSDUCartonBSD").val();
            if (parseInt(valQuantite02PnSDUCartonBSD) === 0) { // Utilisez parseInt pour obtenir un nombre entier
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration02PnSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite03PnSDUCartonBSD").blur(function() {
            var valQuantite03PnSDUCartonBSD = $("#quantite03PnSDUCartonBSD").val();
            if (parseInt(valQuantite03PnSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration03PnSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite04PnSDUCartonBSD").blur(function() { 
            var valQuantite04PnSDUCartonBSD = $("#quantite04PnSDUCartonBSD").val();
            if (parseInt(valQuantite04PnSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration04PnSDUCartonBSD").val(formattedDate);
            }
        });

        // GESTION DES DATE POUR LES PN CSB
        $("#quantite01PnSDUCartonCSB").blur(function() {
            var valQuantite01PnSDUCartonCSB = $("#quantite01PnSDUCartonCSB").val();
            if (parseInt(valQuantite01PnSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration01PnSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite02PnSDUCartonCSB").blur(function() {
            var valQuantite02PnSDUCartonCSB = $("#quantite02PnSDUCartonCSB").val();
            if (parseInt(valQuantite02PnSDUCartonCSB) === 0) { // Utilisez parseInt pour obtenir un nombre entier
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration02PnSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite03PnSDUCartonCSB").blur(function() {
            var valQuantite03PnSDUCartonCSB = $("#quantite03PnSDUCartonCSB").val();
            if (parseInt(valQuantite03PnSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration03PnSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite04PnSDUCartonCSB").blur(function() {
            var valQuantite04PnSDUCartonCSB = $("#quantite04PnSDUCartonCSB").val();
            if (parseInt(valQuantite04PnSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration04PnSDUCartonCSB").val(formattedDate);
            }
        });

        // GESTION DES DATE POUR LES AMOX BSD
        $("#quantite01AmoxSDUCartonBSD").blur(function() {
            var valQuantite01AmoxSDUCartonBSD = $("#quantite01AmoxSDUCartonBSD").val();
            if (parseInt(valQuantite01AmoxSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration01AmoxSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite02AmoxSDUCartonBSD").blur(function() {
            var valQuantite02AmoxSDUCartonBSD = $("#quantite02AmoxSDUCartonBSD").val();
            if (parseInt(valQuantite02AmoxSDUCartonBSD) === 0) { // Utilisez parseInt pour obtenir un nombre entier
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration02AmoxSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite03AmoxSDUCartonBSD").blur(function() {
            var valQuantite03AmoxSDUCartonBSD = $("#quantite03AmoxSDUCartonBSD").val();
            if (parseInt(valQuantite03AmoxSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration03AmoxSDUCartonBSD").val(formattedDate);
            }
        }); 
        $("#quantite04AmoxSDUCartonBSD").blur(function() {
            var valQuantite04AmoxSDUCartonBSD = $("#quantite04AmoxSDUCartonBSD").val();
            if (parseInt(valQuantite04AmoxSDUCartonBSD) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration04AmoxSDUCartonBSD").val(formattedDate);
            }
        });

        // GESTION DES DATE POUR LES AMOX CSB
        $("#quantite01AmoxSDUCartonCSB").blur(function() {
            var valQuantite01AmoxSDUCartonCSB = $("#quantite01AmoxSDUCartonCSB").val();
            if (parseInt(valQuantite01AmoxSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration01AmoxSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite02AmoxSDUCartonCSB").blur(function() {
            var valQuantite02AmoxSDUCartonCSB = $("#quantite02AmoxSDUCartonCSB").val();
            if (parseInt(valQuantite02AmoxSDUCartonCSB) === 0) { // Utilisez parseInt pour obtenir un nombre entier
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration02AmoxSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite03AmoxSDUCartonCSB").blur(function() {
            var valQuantite03AmoxSDUCartonCSB = $("#quantite03AmoxSDUCartonCSB").val();
            if (parseInt(valQuantite03AmoxSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration03AmoxSDUCartonCSB").val(formattedDate);
            }
        }); 
        $("#quantite04AmoxSDUCartonCSB").blur(function() {
            var valQuantite04AmoxSDUCartonCSB = $("#quantite04AmoxSDUCartonCSB").val();
            if (parseInt(valQuantite04AmoxSDUCartonCSB) === 0) {
                var formattedDate = moment().format("YYYY-MM-DD"); 
                $("#dateExpiration04AmoxSDUCartonCSB").val(formattedDate);
            }
        });

        $("#nbrCSBCRENAS").blur(function(){
            var valNbrCsbCRENAS = $("#nbrCSBCRENAS").val();
            if (parseInt(valNbrCsbCRENAS) > 0) {
                var valNbrTotalCsb = $("#nbrTotalCsb").val();
                if (!isNaN(parseInt(valNbrTotalCsb))) {
                    var valTauxCouvertureCRENAS = parseFloat((valNbrCsbCRENAS / valNbrTotalCsb) * 100);
                    $("#tauxCouvertureCRENAS").val(valTauxCouvertureCRENAS.toFixed(2));
                }
            }
        });

        $("#nbrTotalCsb").blur(function(){
            var valNbrTotalCsb = $("#nbrTotalCsb").val(); 
            if (parseInt(valNbrTotalCsb) > 0) {
                var valNbrCsbCRENAS = $("#nbrCSBCRENAS").val();
                if (!isNaN(parseInt(valNbrCsbCRENAS))) {
                    var valTauxCouvertureCRENAS = parseFloat((valNbrCsbCRENAS / valNbrTotalCsb) * 100);
                    $("#tauxCouvertureCRENAS").val(valTauxCouvertureCRENAS.toFixed(2));
                }
            }
        });

        

        $("#nbrCSBCRENASCommande").blur(function() {
            var valNbrCSBCRENASCommande = $("#nbrCSBCRENASCommande").val();
            if (parseInt(valNbrCSBCRENASCommande) > 0) {
                var valNbrCsbCRENAS = $("#nbrCSBCRENAS").val();
                if (!isNaN(parseInt(valNbrCsbCRENAS))) {
                    var valTauxEnvoiRapportCommandeCSBCrenas = (valNbrCSBCRENASCommande / valNbrCsbCRENAS) * 100;
                    valTauxEnvoiRapportCommandeCSBCrenas = parseFloat(valTauxEnvoiRapportCommandeCSBCrenas.toFixed(2));
                    $("#tauxEnvoiCommandeCSBCRENAS").val(valTauxEnvoiRapportCommandeCSBCrenas);
                    
                }
            }
        });

        $('#formCrenas').validate({
            // Définissez les règles de validation pour chaque champ
            rules: {
                moisAdmissionCRENASAnneePrecedent1: "required",
                moisAdmissionCRENASAnneePrecedent2: "required",
                sduFiche: "required",
                nbrTotalCsb: "required",
                nbrCSBCRENAS: "required",
                nbrCSBCRENASCommande: "required",
                // Ajoutez d'autres champs et leurs règles de validation si nécessaire
            },
            messages: {
                moisAdmissionCRENASAnneePrecedent1: "Ce champ est obligatoire",
                moisAdmissionCRENASAnneePrecedent2: "Ce champ est obligatoire",
                sduFiche: "Ce champ est obligatoire",
                nbrTotalCsb: "Ce champ est obligatoire",
                nbrCSBCRENAS: "Ce champ est obligatoire",
                nbrCSBCRENASCommande: "Ce champ est obligatoire",
                // Messages personnalisés pour chaque champ si nécessaire
            },
            submitHandler: function(form) {
                // Code à exécuter si la validation est réussie
                var resume = ''; // Initialisez la variable du résumé
                // Ajoutez ici votre logique pour récupérer et formater les données du formulaire
                // Par exemple :
                resume += "Total nombre d'admissions CRENAS enregistré : " + $('#totalCRENASAnneePrecedent').val() + '\n';
                resume += "Total nombre d'admissions qui avait été projeté : " + $('#totalProjeteAnneePrecedent').val() + '\n';
                resume += "Total projections du nombre d'admissions : " + $('#totalAnneeProjection').val() + '\n';
                resume += "ATPE (en carton) : " + $('#besoinAPTE').val() + '\n';
                resume += "AMOX (boite de 100) : " + $('#besoinAMOX').val() + '\n';
                resume += "Fiche patient (unité) : " + $('#besoinFichePatient').val() + '\n';
                resume += "Registre (unité) : " + $('#besoinRegistre').val() + '\n';
                resume += "Carnet de Rapport CRENAS (unité) : " + $('#besoinCarnetRapportCRENAS').val() + '\n';
                resume += "TOTAL SDU PN en CARTON SDSP : " + $('#totalPnSDUCartonSDSP').val() + '\n'; 
                resume += "TOTAL SDU AMOX Boite de 100 Cp SDSP : " + $('#totalAmoxSDUCartonSDSP').val() + '\n';
                resume += "SDU Fiche : " + $('#sduFiche').val() + '\n';
                resume += "Nombre Total CSB : " + $('#nbrTotalCsb').val() + '\n';
                resume += "Nombre CSB CRENAS : " + $('#nbrCSBCRENAS').val() + '\n';
                resume += "Nombre CSB CRENAS qui ont soumis leurs Commandes  : " + $('#nbrCSBCRENASCommande').val() + '\n';
                
                $('#summary').text(resume); 

                // Affichage de la modale de confirmation
                $('#confirmationModal').modal('show');  
                $('#confirmSubmit').click(function() {
                    form.submit(); // Soumission du formulaire après confirmation
                });
            }
        }); 

        $('#submitBtn').click(function() {
            // Soumettez le formulaire si l'utilisateur confirme
            $('#formCrenas').submit();
        });

        $('#dataTableCRENAS').DataTable({
            dom: 'Blfrtip', 
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Tout"]],
            /* Enlever pour le moment le botton excport Excel par défaut de dataTable puisque il ne gère pas l'en-tête multiple
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export Excel',
                    exportOptions: {
                        columns: ':visible' // Exporter toutes les colonnes visibles
                    }
                }
            ],*/
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