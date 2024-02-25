$(function() {
    /* --------------------------- DEBUT CONTROLE DATA VALIDATION CRENAS --------------------------- */
    var totalEstimatedDataMonthCentral = 0;
    var totalValidatedDataMonth = 0;
    var totalEstimatedDataMonthDistrict = $("#TotalEstimatedDataMonthDistrict").val();
    var valNbrCSBCRENAS = $("#nbrCSBCRENASDistrict").val(); 

    var valeurCalculTheoriqueATPE = $("#valeurCalculTheoriqueATPE").val();
    var valeurCalculTheoriqueAMOX = $("#valeurCalculTheoriqueAMOX").val();
    var valeurCalculTheoriqueFichePatient = $("#valeurCalculTheoriqueFichePatient").val(); 

    var valeurCalculTheoriqueRegistre = $("#valeurCalculTheoriqueRegistre").val();
    var valeurCalculTheoriqueCarnetRapport = $("#valeurCalculTheoriqueCarnetRapport").val();
    var valBesoinRegistre = valNbrCSBCRENAS * valeurCalculTheoriqueRegistre;
    var valBesoinCarnetRapportCRENAS = valNbrCSBCRENAS * valeurCalculTheoriqueCarnetRapport;

    $("#besoinRegistreDistrict, #besoinRegistrePrevision, #besoinRegistreValidated").val(valBesoinRegistre);
    $("#besoinCarnetRapportCRENASDistrict, #besoinCarnetRapportCRENASPrevision, #besoinCarnetRapportCRENASValidated").val(valBesoinCarnetRapportCRENAS);

    $(".EstimatedDataMonthCentral").each(function() {
        var newVal = $(this).val();
        if (!isNaN(parseFloat(newVal))) {
            totalEstimatedDataMonthCentral += parseFloat(newVal);
        }
    });

    $(".ValidatedDataMonth").each(function() {
        var newVal = $(this).val();
        if (!isNaN(parseFloat(newVal))) {
            totalValidatedDataMonth += parseFloat(newVal);
        }
    });

    $("#TotalEstimatedDataMonthCentral").val(totalEstimatedDataMonthCentral);
    $("#TotalValidatedDataMonth").val(totalValidatedDataMonth);
    
    var besoinAPTEDistrict = (parseFloat(totalEstimatedDataMonthDistrict) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2); 
    var besoinAMOXDistrict = (parseFloat(totalEstimatedDataMonthDistrict) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);  
    var besoinFichePatientDistrict = (parseFloat(totalEstimatedDataMonthDistrict) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2); 
    
    $("#besoinAPTEDistrict").val(besoinAPTEDistrict);
    $("#besoinAMOXDistrict").val(besoinAMOXDistrict);
    $("#besoinFichePatientDistrict").val(besoinFichePatientDistrict);

    var besoinAPTEPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2);
    var besoinAMOXPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);
    var besoinFichePatientPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2);
    
    $("#besoinAPTEPrevision").val(besoinAPTEPrevision);
    $("#besoinAMOXPrevision").val(besoinAMOXPrevision);
    $("#besoinFichePatientPrevision").val(besoinFichePatientPrevision);

    var besoinAPTEValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2);
    var besoinAMOXValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);
    var besoinFichePatientValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2);

    $("#besoinAPTEValidated").val(besoinAPTEValidated);
    $("#besoinAMOXValidated").val(besoinAMOXValidated);
    $("#besoinFichePatientValidated").val(besoinFichePatientValidated);


    // Somme des valeur sur l'Admission CRENAS pour l'année précédente
    $(".EstimatedDataMonthCentral").change(function() {
        var totalEstimatedDataMonthCentral = 0;
        $(".EstimatedDataMonthCentral").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                totalEstimatedDataMonthCentral += parseFloat(newVal);
            }
        });
        $("#TotalEstimatedDataMonthCentral").val(totalEstimatedDataMonthCentral); 

        var besoinAPTEPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2);
        var besoinAMOXPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);
        var besoinFichePatientPrevision = (parseFloat(totalEstimatedDataMonthCentral) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2); 
        $("#besoinAPTEPrevision").val(besoinAPTEPrevision);
        $("#besoinAMOXPrevision").val(besoinAMOXPrevision);
        $("#besoinFichePatientPrevision").val(besoinFichePatientPrevision);
    });

    $(".ValidatedDataMonth").change(function(){
        var totalValidatedDataMonth = 0;
        $(".ValidatedDataMonth").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                totalValidatedDataMonth += parseFloat(newVal);
            }
        });
        $("#TotalValidatedDataMonth").val(totalValidatedDataMonth);

        var besoinAPTEValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueATPE)).toFixed(2);
        var besoinAMOXValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueAMOX)).toFixed(2);
        var besoinFichePatientValidated = (parseFloat(totalValidatedDataMonth) * parseFloat(valeurCalculTheoriqueFichePatient)).toFixed(2); 
        $("#besoinAPTEValidated").val(besoinAPTEValidated);
        $("#besoinAMOXValidated").val(besoinAMOXValidated);
        $("#besoinFichePatientValidated").val(besoinFichePatientValidated);
    });

    /* --------------------------- DEBUT CONTROLE DATA VALIDATION CRENI --------------------------- */
    var totalDataMoisProjectionEstimatedDistrict = $("#DataMoisTotalProjectionEstimatedDistrict").val();
    var totalDataMoisProjectionEstimatedCentral = 0;
    var totalDataMoisProjectionValidated = 0;

    $(".DataMoisProjectionEstimatedCentral").each(function() {
        var newVal = $(this).val();
        if (!isNaN(parseFloat(newVal))) {
            totalDataMoisProjectionEstimatedCentral += parseFloat(newVal);
        }
    });

    $(".DataMoisProjectionValidated").each(function() {
        var newVal = $(this).val();
        if (!isNaN(parseFloat(newVal))) {
            totalDataMoisProjectionValidated += parseFloat(newVal);
        }
    });

    $("#DataMoisTotalProjectionEstimatedCentral").val(totalDataMoisProjectionEstimatedCentral);
    $("#DataMoisTotalProjectionValidated").val(totalDataMoisProjectionValidated);

    // Somme des valeur sur l'Admission CRENAS pour l'année précédente
    $(".DataMoisProjectionEstimatedCentral").change(function() {
        var newTotalDataMoisProjectionEstimatedCentral = 0;
        $(".DataMoisProjectionEstimatedCentral").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                newTotalDataMoisProjectionEstimatedCentral += parseFloat(newVal);
            }
        });
        $("#DataMoisTotalProjectionEstimatedCentral").val(newTotalDataMoisProjectionEstimatedCentral);  
    });

    $(".DataMoisProjectionValidated").change(function(){
        var newTotalDataMoisProjectionValidated = 0;
        $(".DataMoisProjectionValidated").each(function() {
            var newVal = $(this).val();
            if (!isNaN(parseFloat(newVal))) {
                newTotalDataMoisProjectionValidated += parseFloat(newVal);
            }
        });
        $("#DataMoisTotalProjectionValidated").val(newTotalDataMoisProjectionValidated); 
    });

});