$(function() {
    // Somme des valeur sur l'Admission CRENI pour l'année précédente
    $(".DataMoisAdmissionCreniPrecedent").change(function() {
        var totalAdmissionCreniSemestrePrecedent = 0;
        $(".DataMoisAdmissionCreniPrecedent").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                totalAdmissionCreniSemestrePrecedent += parseFloat(newVal);
            }
        });
        $("#totalAdmissionCreniSemestrePrecedent").val(totalAdmissionCreniSemestrePrecedent);

        var totalAdmissionCreniSemestrePrecedent = $("#totalAdmissionCreniSemestrePrecedent").val();
        var totalAdmissionCreniProjetePrecedent = $("#totalAdmissionCreniProjetePrecedent").val();
        var totalAdmissionCreniProjeterProchain = $("#totalAdmissionCreniProjeterProchain").val();
        var ResultatDifferenceAdmissionPrecedent = parseFloat(totalAdmissionCreniProjetePrecedent) - parseFloat(totalAdmissionCreniSemestrePrecedent); 
        var ResultatDifferenceAdmissionProchainPrecedent = parseFloat(totalAdmissionCreniProjeterProchain) - parseFloat(ResultatDifferenceAdmissionPrecedent);
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionPrecedent))) {
            $("#ResultatDifferenceAdmissionPrecedent").val(ResultatDifferenceAdmissionPrecedent);
        }
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionProchainPrecedent))) {
            $("#ResultatDifferenceAdmissionProchainPrecedent").val(ResultatDifferenceAdmissionProchainPrecedent);
        }

        var valF75Boite = ResultatDifferenceAdmissionProchainPrecedent * 3;
        if (!isNaN(parseFloat(valF75Boite))) { $("#f75Boites").val(valF75Boite); }

        var valF100Boite = ResultatDifferenceAdmissionProchainPrecedent * 1;
        if (!isNaN(parseFloat(valF100Boite))) { $("#f100Boites").val(valF100Boite); }

        var valReSoMal = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valReSoMal))) { $("#ReSoMalSachet").val(valReSoMal); }

        var valPn = ResultatDifferenceAdmissionProchainPrecedent * 10;
        if (!isNaN(parseFloat(valPn))) { $("#pnSachet").val(valPn); }

        var valFicheSuiviCreni = ResultatDifferenceAdmissionProchainPrecedent ;
        if (!isNaN(parseFloat(valFicheSuiviCreni))) { $("#ficheSuiviCreni").val(valFicheSuiviCreni); }

        var valFicheSuiviIntensif = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valFicheSuiviIntensif))) { $("#ficheSuiviIntensif").val(valFicheSuiviIntensif); }
        
        var valKitMedicament = ResultatDifferenceAdmissionProchainPrecedent / 10;
        if (!isNaN(parseFloat(valKitMedicament))) { $("#kitMedicamentsCreni10Patients").val(valKitMedicament); }

        calculValueKit();
    });
 
    // Somme des valeur sur l'Admission qui a été projeté pour l'année projeté
    $(".DataMoisAdmissionProjeteAnneePrecedent").change(function() {
        var totalAdmissionCreniProjetePrecedent = 0;
        $(".DataMoisAdmissionProjeteAnneePrecedent").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                totalAdmissionCreniProjetePrecedent += parseFloat(newVal);
            }
        });
        $("#totalAdmissionCreniProjetePrecedent").val(totalAdmissionCreniProjetePrecedent);

        var totalAdmissionCreniSemestrePrecedent = $("#totalAdmissionCreniSemestrePrecedent").val();
        var totalAdmissionCreniProjetePrecedent = $("#totalAdmissionCreniProjetePrecedent").val();
        var totalAdmissionCreniProjeterProchain = $("#totalAdmissionCreniProjeterProchain").val();
        var ResultatDifferenceAdmissionPrecedent = parseFloat(totalAdmissionCreniProjetePrecedent) - parseFloat(totalAdmissionCreniSemestrePrecedent); 
        var ResultatDifferenceAdmissionProchainPrecedent = parseFloat(totalAdmissionCreniProjeterProchain) - parseFloat(ResultatDifferenceAdmissionPrecedent);
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionPrecedent))) {
            $("#ResultatDifferenceAdmissionPrecedent").val(ResultatDifferenceAdmissionPrecedent);
        }
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionProchainPrecedent))) {
            $("#ResultatDifferenceAdmissionProchainPrecedent").val(ResultatDifferenceAdmissionProchainPrecedent);
        }

        var valF75Boite = ResultatDifferenceAdmissionProchainPrecedent * 3;
        if (!isNaN(parseFloat(valF75Boite))) { $("#f75Boites").val(valF75Boite); }

        var valF100Boite = ResultatDifferenceAdmissionProchainPrecedent * 1;
        if (!isNaN(parseFloat(valF100Boite))) { $("#f100Boites").val(valF100Boite); }

        var valReSoMal = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valReSoMal))) { $("#ReSoMalSachet").val(valReSoMal); }

        var valPn = ResultatDifferenceAdmissionProchainPrecedent * 10;
        if (!isNaN(parseFloat(valPn))) { $("#pnSachet").val(valPn); }

        var valFicheSuiviCreni = ResultatDifferenceAdmissionProchainPrecedent ;
        if (!isNaN(parseFloat(valFicheSuiviCreni))) { $("#ficheSuiviCreni").val(valFicheSuiviCreni); }

        var valFicheSuiviIntensif = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valFicheSuiviIntensif))) { $("#ficheSuiviIntensif").val(valFicheSuiviIntensif); }
        
        var valKitMedicament = ResultatDifferenceAdmissionProchainPrecedent / 10;
        if (!isNaN(parseFloat(valKitMedicament))) { $("#kitMedicamentsCreni10Patients").val(valKitMedicament); }

        calculValueKit();
    });

    // Somme des valeur des projections du nombre d'admission
    $(".DataMoisProjectionAnneePrevisionnelle").change(function() {
        var totalAdmissionCreniProjeterProchain = 0;
        $(".DataMoisProjectionAnneePrevisionnelle").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                totalAdmissionCreniProjeterProchain += parseFloat(newVal);
            }
        });

        $("#totalAdmissionCreniProjeterProchain").val(totalAdmissionCreniProjeterProchain);

        var totalAdmissionCreniSemestrePrecedent = $("#totalAdmissionCreniSemestrePrecedent").val();
        var totalAdmissionCreniProjetePrecedent = $("#totalAdmissionCreniProjetePrecedent").val();
        var totalAdmissionCreniProjeterProchain = $("#totalAdmissionCreniProjeterProchain").val();
        var ResultatDifferenceAdmissionPrecedent = parseFloat(totalAdmissionCreniProjetePrecedent) - parseFloat(totalAdmissionCreniSemestrePrecedent); 
        var ResultatDifferenceAdmissionProchainPrecedent = parseFloat(totalAdmissionCreniProjeterProchain) - parseFloat(ResultatDifferenceAdmissionPrecedent);
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionPrecedent))) {
            $("#ResultatDifferenceAdmissionPrecedent").val(ResultatDifferenceAdmissionPrecedent);
        }
        if (!isNaN(parseFloat(ResultatDifferenceAdmissionProchainPrecedent))) {
            $("#ResultatDifferenceAdmissionProchainPrecedent").val(ResultatDifferenceAdmissionProchainPrecedent);
        }

        var valF75Boite = ResultatDifferenceAdmissionProchainPrecedent * 3;
        if (!isNaN(parseFloat(valF75Boite))) { $("#f75Boites").val(valF75Boite); }

        var valF100Boite = ResultatDifferenceAdmissionProchainPrecedent * 1;
        if (!isNaN(parseFloat(valF100Boite))) { $("#f100Boites").val(valF100Boite); }

        var valReSoMal = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valReSoMal))) { $("#ReSoMalSachet").val(valReSoMal); }

        var valPn = ResultatDifferenceAdmissionProchainPrecedent * 10;
        if (!isNaN(parseFloat(valPn))) { $("#pnSachet").val(valPn); }

        var valFicheSuiviCreni = ResultatDifferenceAdmissionProchainPrecedent ;
        if (!isNaN(parseFloat(valFicheSuiviCreni))) { $("#ficheSuiviCreni").val(valFicheSuiviCreni); }

        var valFicheSuiviIntensif = ResultatDifferenceAdmissionProchainPrecedent * 0.5;
        if (!isNaN(parseFloat(valFicheSuiviIntensif))) { $("#ficheSuiviIntensif").val(valFicheSuiviIntensif); }
        
        var valKitMedicament = parseFloat(ResultatDifferenceAdmissionProchainPrecedent / 10);
        if (!isNaN(parseFloat(valKitMedicament))) { $("#kitMedicamentsCreni10Patients").val(valKitMedicament); }

        calculValueKit();
        
    });

    $('#formCreni').validate({
        // Définissez les règles de validation pour chaque champ
        rules: {
            DataMois01AdmissionCreniPrecedent: "required",
            DataMois02AdmissionCreniPrecedent: "required",
            DataMois03AdmissionCreniPrecedent: "required",
            DataMois04AdmissionCreniPrecedent: "required",
            DataMois05AdmissionCreniPrecedent: "required",
            DataMois06AdmissionCreniPrecedent: "required",
            DataMois01AdmissionProjeteAnneePrecedent: "required",
            DataMois02AdmissionProjeteAnneePrecedent: "required",
            DataMois03AdmissionProjeteAnneePrecedent: "required",
            DataMois04AdmissionProjeteAnneePrecedent: "required",
            DataMois05AdmissionProjeteAnneePrecedent: "required",
            DataMois06AdmissionProjeteAnneePrecedent: "required",
            DataMois01ProjectionAnneePrevisionnelle: "required",
            DataMois02ProjectionAnneePrevisionnelle: "required",
            DataMois03ProjectionAnneePrevisionnelle: "required",
            DataMois04ProjectionAnneePrevisionnelle: "required",
            DataMois05ProjectionAnneePrevisionnelle: "required",
            DataMois06ProjectionAnneePrevisionnelle: "required",
            registreCreni: "required",
            carnetRapportMensuelCreni: "required",
            sduF75Boites: "required",
            sduF100Boites: "required",
            sduReSoMal: "required",
            sduPnSachet: "required",
            sduFicheSuiviCreni: "required",
            sduFicheSuiviIntensif: "required",
            sduAmoxiciPdr: "required",
            sduNystatinOral: "required",
            sduFluconazole50mg: "required",
            sduCiprofloxacin250mg: "required",
            sduAmpicillinpdrInj500mg: "required",
            sduGentamicininj40mg: "required",
            sduSodLactatInj500ml: "required",
            sduGlucoseInj500ml: "required",
            sduGlucoseHyperton50ml: "required",
            sduFurosemideinj10mg: "required",
            sduChlorhexidineConSol: "required",
            sduMiconazoleNitrate: "required",
            sduTetracyclineeyeointment: "required",
            sduTubeFeedingCH08: "required",
            sduTubeFeedingCH05: "required",
            sduSyringeDisp2ml: "required",
            sduSyringeDisp10ml: "required",
            sduSyringeDisp20ml: "required",
            sduSyringeFeeding50ml: "required",
            // Ajoutez d'autres champs et leurs règles de validation si nécessaire
        },
        messages: {
            DataMois01AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois02AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois03AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois04AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois05AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois06AdmissionCreniPrecedent: "Ce champ est obligatoire",
            DataMois01AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois02AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois03AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois04AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois05AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois06AdmissionProjeteAnneePrecedent: "Ce champ est obligatoire",
            DataMois01ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            DataMois02ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            DataMois03ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            DataMois04ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            DataMois05ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            DataMois06ProjectionAnneePrevisionnelle: "Ce champ est obligatoire",
            registreCreni: "Ce champ est obligatoire",
            carnetRapportMensuelCreni: "Ce champ est obligatoire",
            sduF75Boites: "Ce champ est obligatoire",
            sduF100Boites: "Ce champ est obligatoire",
            sduReSoMal: "Ce champ est obligatoire",
            sduPnSachet: "Ce champ est obligatoire",
            sduFicheSuiviCreni: "Ce champ est obligatoire",
            sduFicheSuiviIntensif: "Ce champ est obligatoire",
            sduAmoxiciPdr: "Ce champ est obligatoire",
            sduNystatinOral: "Ce champ est obligatoire",
            sduFluconazole50mg: "Ce champ est obligatoire",
            sduCiprofloxacin250mg: "Ce champ est obligatoire",  
            sduAmpicillinpdrInj500mg: "Ce champ est obligatoire",
            sduGentamicininj40mg: "Ce champ est obligatoire",
            sduSodLactatInj500ml: "Ce champ est obligatoire",
            sduGlucoseInj500ml: "Ce champ est obligatoire",
            sduGlucoseHyperton50ml: "Ce champ est obligatoire",
            sduFurosemideinj10mg: "Ce champ est obligatoire",
            sduChlorhexidineConSol: "Ce champ est obligatoire",
            sduMiconazoleNitrate: "Ce champ est obligatoire",
            sduTetracyclineeyeointment: "Ce champ est obligatoire",
            sduTubeFeedingCH08: "Ce champ est obligatoire",
            sduTubeFeedingCH05: "Ce champ est obligatoire",
            sduSyringeDisp2ml: "Ce champ est obligatoire",
            sduSyringeDisp10ml: "Ce champ est obligatoire",
            sduSyringeDisp20ml: "Ce champ est obligatoire",
            sduSyringeFeeding50ml: "Ce champ est obligatoire", 
            // Messages personnalisés pour chaque champ si nécessaire
        },
        submitHandler: function(form) {
            // Code à exécuter si la validation est réussie
            var resume = ''; // Initialisez la variable du résumé
            // Ajoutez ici votre logique pour récupérer et formater les données du formulaire
            // Par exemple :
            resume += "Total nombre d'admissions CRENI  enregistré : " + $('#totalAdmissionCreniSemestrePrecedent').val() + '\n';
            resume += "Total nombre d'admissions qui avait été projeté la semèstre précédent : " + $('#totalAdmissionCreniProjetePrecedent').val() + '\n';
            resume += "Total projections du nombre d'admissions pour le prochain semestre : " + $('#totalAdmissionCreniProjeterProchain').val() + '\n';
            resume += "Registre CRENI Unité : " + $('#registreCreni').val() + '\n';
            resume += "Carnet de rapports mensuels CRENI Unité : " + $('#carnetRapportMensuelCreni').val() + '\n';

            resume += "F75 Boites : " + $('#sduF75Boites').val() + '\n';
            resume += "F100 Boites : " + $('#sduF100Boites').val() + '\n'; 
            resume += "ReSoMal sachet: " + $('#sduReSoMal').val() + '\n'; 
            resume += "PN sachet: " + $('#sduPnSachet').val() + '\n'; 
            resume += "Fiche de suivi CRENI unité: " + $('#sduFicheSuiviCreni').val() + '\n'; 
            resume += "Fiche de suivi intensif Unité: " + $('#sduFicheSuiviIntensif').val() + '\n'; 
            resume += "Amoxici.pdr/oral sus 125mg/5ml/BOT-100ml: " + $('#sduAmoxiciPdr').val() + '\n'; 
            resume += "Nystatin oral sus 100,000IU/ml/BOT-30ml : " + $('#sduNystatinOral').val() + '\n'; 
            resume += "Fluconazole 50mg caps/PAC-7 (non pas injection) : " + $('#sduFluconazole50mg').val() + '\n'; 
            resume += "Ciprofloxacin 250mg tab/PAC-10 : " + $('#sduCiprofloxacin250mg').val() + '\n'; 
            resume += "Ampicillinpdr/inj 500mg vial/BOX-100 : " + $('#sduAmpicillinpdrInj500mg').val() + '\n'; 
            resume += "Gentamicininj 40mg/ml 2ml amp/BOX-50 : " + $('#sduGentamicininj40mg').val() + '\n'; 
            resume += "Sod.lactat.comp.inj 500ml w/g.set/BOX-20 : " + $('#sduSodLactatInj500ml').val() + '\n'; 

            resume += "Glucose inj 5% 500ml w/giv.set/BOX-20 : " + $('#sduGlucoseInj500ml').val() + '\n'; 
            resume += "Glucose hyperton.inj 50% 50mL vl/BOX-25 : " + $('#sduGlucoseHyperton50ml').val() + '\n'; 
            resume += "Furosemideinj 10mg/ml 2ml amp/BOX-10 : " + $('#sduFurosemideinj10mg').val() + '\n'; 
            resume += "Chlorhexidine conc. sol. 5%/BOT-100ml  : " + $('#sduChlorhexidineConSol').val() + '\n'; 
            resume += "Miconazole nitrate cream 2%/TBE-30g : " + $('#sduMiconazoleNitrate').val() + '\n'; 
            resume += "Tetracyclineeyeointment 1%/TBE-5g : " + $('#sduTetracyclineeyeointment').val() + '\n'; 
            resume += "Tube,feeding,CH08, L40cm,ster,disp: " + $('#sduTubeFeedingCH08').val() + '\n'; 
            resume += "Tube,feeding,CH05, L40cm,ster,disp : " + $('#sduTubeFeedingCH05').val() + '\n'; 
            resume += "Syringe,disp,2ml, w/ndl,21G/BOX-100 : " + $('#sduSyringeDisp2ml').val() + '\n'; 
            resume += "Syringe,disp,10ml, ster/BOX-100 : " + $('#sduSyringeDisp10ml').val() + '\n'; 
            resume += "Syringe,disp,20ml, ster/BOX-100 : " + $('#sduSyringeDisp20ml').val() + '\n'; 
            resume += "Syringe,feeding,50ml, luer tip,ster : " + $('#sduSyringeFeeding50ml').val() + '\n'; 
            
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
        $('#formCreni').submit();
    }); 

    function calculValueKit() {
        var valKitMedicament = $("#kitMedicamentsCreni10Patients").val();
        if (!isNaN(parseFloat(valKitMedicament))) {
            var kitCreniAmoxici = valKitMedicament * 17;
            var kitCreniNystatin = valKitMedicament * 4;
            var kitCreniFluconazole = valKitMedicament * 2;
            var kitCreniCiprofloxacin = valKitMedicament * 10;
            var kitCreniAmpicillinpdr = valKitMedicament * 2;
            var kitCreniGentamicininj = valKitMedicament * 1;
            var kitCreniSod = valKitMedicament * 1;
            var kitCreniGlucoseInj = valKitMedicament * 1;
            var kitCreniGlucoseHypertonInj = valKitMedicament * 1;
            var kitCreniFurosemideinj = valKitMedicament * 1;
            var kitCreniChlorhexidine = valKitMedicament * 4;
            var kitCreniMiconazole = valKitMedicament * 1;
            var kitCreniTetracyclineeyeointment = valKitMedicament * 7;
            var kitCreniTubeFeeding = valKitMedicament * 7;
            var kitCreniTubeFeedingCH05 = valKitMedicament * 7;
            var kitCreniSyringeDisp2ml = valKitMedicament * 1;
            var kitCreniSyringeDisp10ml = valKitMedicament * 1;
            var kitCreniSyringeDisp20ml = valKitMedicament * 1;
            var kitCreniSyringeDisp50ml = valKitMedicament * 7;

            $("#kitCreniAmoxici").val(kitCreniAmoxici);
            $("#kitCreniNystatin").val(kitCreniNystatin);
            $("#kitCreniFluconazole").val(kitCreniFluconazole);
            $("#kitCreniCiprofloxacin").val(kitCreniCiprofloxacin);
            $("#kitCreniAmpicillinpdr").val(kitCreniAmpicillinpdr);
            $("#kitCreniGentamicininj").val(kitCreniGentamicininj);
            $("#kitCreniSod").val(kitCreniSod);
            $("#kitCreniGlucoseInj").val(kitCreniGlucoseInj);
            $("#kitCreniGlucoseHypertonInj").val(kitCreniGlucoseHypertonInj);
            $("#kitCreniFurosemideinj").val(kitCreniFurosemideinj);
            $("#kitCreniChlorhexidine").val(kitCreniChlorhexidine);
            $("#kitCreniMiconazole").val(kitCreniMiconazole);
            $("#kitCreniTetracyclineeyeointment").val(kitCreniTetracyclineeyeointment);
            $("#kitCreniTubeFeeding").val(kitCreniTubeFeeding);
            $("#kitCreniTubeFeedingCH05").val(kitCreniTubeFeedingCH05);
            $("#kitCreniSyringeDisp2ml").val(kitCreniSyringeDisp2ml);
            $("#kitCreniSyringeDisp10ml").val(kitCreniSyringeDisp10ml);
            $("#kitCreniSyringeDisp20ml").val(kitCreniSyringeDisp20ml);
            $("#kitCreniSyringeDisp50ml").val(kitCreniSyringeDisp50ml); 
        }
    }

});