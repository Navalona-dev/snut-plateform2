$(function() {
    var table = $('#dataTableCRENAS').DataTable();
    $("#boutonExportExcel").on('click', function() {

        // Initialisation (Workbook , Feuille)
        var workbook = new ExcelJS.Workbook();
        let worksheet = workbook.addWorksheet('Feuille1'); 
        const lstLotSDU = "Lot A, Lot B, Loc C, Loc D";
        const varLotSDUArray = lstLotSDU.split(', ');
        const lstQuantiteDateSDU = "Quantité, Date d'expiration (jj/mm/yyyy)";
        const varQuantiteDateSDUArray = lstQuantiteDateSDU.split(', ');

        /* ------------------ EN-TÊTE ----------------- */ 

        /* ----------------------------------------- Province - Région - District ----------------------------------------- */
        
        addHeader(worksheet, worksheet.getCell('A1'), 'Province', 'A1:A3');
        addHeader(worksheet, worksheet.getCell('B1'), 'Région', 'B1:B3');
        addHeader(worksheet, worksheet.getCell('C1'), 'District (Responsables)', 'C1:C3');

        /* ----------------------------------------- Nombre d'admissions CRENAS enregistré ----------------------------------------- */         
        const lstMoisAdmissionCRENASAnneePrecedent = $("#lstMoisAdmissionCRENASAnneePrecedent").val();
        const moisAdmissionCRENASAnneePrecedentArray = lstMoisAdmissionCRENASAnneePrecedent.split(', ');
        var startColumnAdmissionCrenasEnregistre = 4;
        addDataColumn(worksheet, startColumnAdmissionCrenasEnregistre, 'Nombre d\'admissions CRENAS enregistré', moisAdmissionCRENASAnneePrecedentArray);

        /* ----------------------------------------- Nombre d'admissions qui avait été projeté ----------------------------------------- */
        const lstMoisAdmissionProjeteAnneePrecedent = $("#lstMoisAdmissionProjeteAnneePrecedent").val();
        const moisAdmissionProjeteAnneePrecedentArray = lstMoisAdmissionProjeteAnneePrecedent.split(', ');
        var startColumnAdmissionProjeteAnneePrecedent = startColumnAdmissionCrenasEnregistre + moisAdmissionCRENASAnneePrecedentArray.length + 1;
        addDataColumn(worksheet, startColumnAdmissionProjeteAnneePrecedent, 'Nombre d\'admissions qui avait été projeté', moisAdmissionProjeteAnneePrecedentArray);

        /* ----------------------------------------- Résultat ----------------------------------------- */
        var startColumnResultat01 = startColumnAdmissionProjeteAnneePrecedent + moisAdmissionProjeteAnneePrecedentArray.length + 1;
        const columnLetterResultat01 = numberToColumnLetter(startColumnResultat01);
        addHeader(worksheet, worksheet.getCell(columnLetterResultat01+'1'), 'Resultat', columnLetterResultat01 + '1:' + columnLetterResultat01 + '3');

        /* ----------------------------------------- Projections du nombre d'admissions ----------------------------------------- */
        const lstMoisProjectionAnneePrevisionnelle = $("#lstMoisProjectionAnneePrevisionnelle").val();
        const moisProjectionAnneePrevisionnelleArray = lstMoisProjectionAnneePrevisionnelle.split(', ');
        var startColumnProjectionAnneePrevisionnelle = startColumnAdmissionProjeteAnneePrecedent + moisProjectionAnneePrevisionnelleArray.length + 2;
        if (moisProjectionAnneePrevisionnelleArray.length == 6) {
            startColumnProjectionAnneePrevisionnelle = startColumnProjectionAnneePrevisionnelle - 3;
        }
        
        addDataColumn(worksheet, startColumnProjectionAnneePrevisionnelle, 'Projections du nombre d\'admissions', moisProjectionAnneePrevisionnelleArray);

        /* ----------------------------------------- Résultat ----------------------------------------- */
        var startColumnResultat02 = startColumnProjectionAnneePrevisionnelle + moisProjectionAnneePrevisionnelleArray.length + 1;
        const columnLetterResultat02 = numberToColumnLetter(startColumnResultat02);
        addHeader(worksheet, worksheet.getCell(columnLetterResultat02+'1'), 'Resultat', columnLetterResultat02 + '1:' + columnLetterResultat02 + '3');

        /* ----------------------------------------- Quantité nécessaire en intrants nutrition (Besoins théoriques)  ----------------------------------------- */
        const lstQuantiteNecessaireEnIntrantsNutrition = "ATPE (en carton), AMOX (boite de 100), Fiche patient (unité), Registre (unité), Carnet de Rapport CRENAS (unité)";
        const varQuantiteNecessaireEnIntrantsNutritionArray = lstQuantiteNecessaireEnIntrantsNutrition.split(', ');
        var startColumnQuantiteNecessaireEnIntrantsNutrition = startColumnResultat02 + 1;
        addDataColumn(worksheet, startColumnQuantiteNecessaireEnIntrantsNutrition, 'Quantité nécessaire en intrants nutrition (Besoins théoriques)', varQuantiteNecessaireEnIntrantsNutritionArray, 1);

        /* ---------------------------------------- Stock Disponible et Utilisable à l’inventaire AMOX en Boite de 100 cp (SDU total BSD) (SDU AMOX Boite de 100 cp au niveau BSD)  ----------------------------------------- */
        var startColumnSDUAmox100CpBSD = startColumnQuantiteNecessaireEnIntrantsNutrition + varQuantiteNecessaireEnIntrantsNutritionArray.length ;
        addDataSDUColumn(worksheet, startColumnSDUAmox100CpBSD, 'Stock Disponible et Utilisable à l’inventaire AMOX en Boite de 100 cp (SDU total BSD) (SDU AMOX Boite de 100 cp au niveau BSD)', varLotSDUArray, varQuantiteDateSDUArray);

        /* ---------------------------------------- Stock Disponible et Utilisable AMOX à l’inventaire en Boite de 100 cp (SDU total CSB) (SDU AMOX Boite de 100 cp au niveau CSB) ----------------------------------------- */
        var startColumnSDUAmox100CpCSB = startColumnSDUAmox100CpBSD + varLotSDUArray.length + 5 ;
        addDataSDUColumn(worksheet, startColumnSDUAmox100CpCSB, 'Stock Disponible et Utilisable AMOX à l’inventaire en Boite de 100 cp (SDU total CSB) (SDU AMOX Boite de 100 cp au niveau CSB)', varLotSDUArray, varQuantiteDateSDUArray);
        
        /* ---------------------------------------- TOTAL SDU AMOX Boite de 100 Cp SDSP ------------------------------------------------------- */
        var startColumnResultat03 = startColumnSDUAmox100CpCSB + varLotSDUArray.length + 5;
        const columnLetterResultat03 = numberToColumnLetter(startColumnResultat03);
        addHeader(worksheet, worksheet.getCell(columnLetterResultat03+'1'), 'TOTAL SDU AMOX Boite de 100 Cp SDSP', columnLetterResultat03 + '1:' + columnLetterResultat03 + '3');

        /* ---------------------------------------- Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total BSD) (SDU PN en Carton au niveau BSD) ------------------------------------------------------- */
        var startColumnSDUPnCartonBSD = startColumnResultat03 + 1;
        addDataSDUColumn(worksheet, startColumnSDUPnCartonBSD, 'Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total BSD) (SDU PN en Carton au niveau BSD)', varLotSDUArray, varQuantiteDateSDUArray);

        /* ---------------------------------------- Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total CSB) (SDU PN en Carton au niveau CSB) ------------------------------------------------------- */ 
        var startColumnSDUPnCartonCSB = startColumnSDUPnCartonBSD + varLotSDUArray.length + 5 ;
        addDataSDUColumn(worksheet, startColumnSDUPnCartonCSB, 'Stock Disponible et Utilisable PN à l’inventaire en CARTON (SDU total CSB) (SDU PN en Carton au niveau CSB)', varLotSDUArray, varQuantiteDateSDUArray);
        
        /* ---------------------------------------- TOTAL SDU PN en carton SDSP ------------------------------------------------------- */
        var startColumnResultat04 = startColumnSDUPnCartonCSB + varLotSDUArray.length + 5;
        const columnLetterResultat04 = numberToColumnLetter(startColumnResultat04);
        addHeader(worksheet, worksheet.getCell(columnLetterResultat04+'1'), 'TOTAL SDU PN en carton SDSP', columnLetterResultat04 + '1:' + columnLetterResultat04 + '3');

        /* ---------------------------------------- SDU Fiche ------------------------------------------------------- */
        var startColumnSDUFiche = startColumnResultat04 + 1;
        const columnSDUFiche = numberToColumnLetter(startColumnSDUFiche);
        addHeader(worksheet, worksheet.getCell(columnSDUFiche+'1'), 'SDU Fiche', columnSDUFiche + '1:' + columnSDUFiche + '3');

        /* ---------------------------------------- Nombre Total CSB ------------------------------------------------------- */
        var startColumnNombreTotalCSB = startColumnSDUFiche + 1;
        const columnNombreTotalCSB = numberToColumnLetter(startColumnNombreTotalCSB);
        addHeader(worksheet, worksheet.getCell(columnNombreTotalCSB + '1'), 'Nombre Total CSB', columnNombreTotalCSB + '1:' + columnNombreTotalCSB + '3');

        /* ---------------------------------------- Nombre CSB CRENAS ------------------------------------------------------- */
        var startColumnNombreCSBCrenas = startColumnNombreTotalCSB + 1;
        const columnNombreCSBCrenas = numberToColumnLetter(startColumnNombreCSBCrenas);
        addHeader(worksheet, worksheet.getCell(columnNombreCSBCrenas + '1'), 'Nombre CSB CRENAS', columnNombreCSBCrenas + '1:' + columnNombreCSBCrenas + '3');

        /* ---------------------------------------- Taux de couvertur CRENAS ------------------------------------------------------- */
        var startColumnTauxCouverturCRENAS = startColumnNombreCSBCrenas + 1;
        const columnTauxCouverturCRENAS = numberToColumnLetter(startColumnTauxCouverturCRENAS);
        addHeader(worksheet, worksheet.getCell(columnTauxCouverturCRENAS + '1'), 'Taux de couvertur CRENAS', columnTauxCouverturCRENAS + '1:' + columnTauxCouverturCRENAS + '3');

        /* ---------------------------------------- Nombre CSB CRENAS qui ont soumis leur commandes ------------------------------------------------------- */
        var startColumnNombreCSBCrenasSoumisCommande = startColumnTauxCouverturCRENAS + 1;
        const columnNombreCSBCrenasSoumisCommande = numberToColumnLetter(startColumnNombreCSBCrenasSoumisCommande);
        addHeader(worksheet, worksheet.getCell(columnNombreCSBCrenasSoumisCommande + '1'), 'Nombre CSB CRENAS qui ont soumis leur commandes', columnNombreCSBCrenasSoumisCommande + '1:' + columnNombreCSBCrenasSoumisCommande + '3');

        /* ---------------------------------------- Taux d'envoi de rapport bon de commande des CSB CRENAS ------------------------------------------------------- */
        var startColumnTauxEnvoiRapportCommande = startColumnNombreCSBCrenasSoumisCommande + 1;
        const columnTauxEnvoiRapportCommande = numberToColumnLetter(startColumnTauxEnvoiRapportCommande);
        addHeader(worksheet, worksheet.getCell(columnTauxEnvoiRapportCommande + '1'), 'Taux d\'envoi de rapport bon de commande des CSB CRENAS', columnTauxEnvoiRapportCommande + '1:' + columnTauxEnvoiRapportCommande + '3');
        

        /* ----------------------------------------- Mise en place des données -----------------------------------------  */
 
        // Ajout des données depuis le DataTable
        var data = table.rows().data().toArray();
        for (var i = 0; i < data.length; i++) {
            let rowData = Array.isArray(data[i]) ? data[i].map(cell => {
                if (typeof cell === 'string') {
                    return cell.replace(/<br>/g, "\n");
                }
                return cell;
            }) : data[i];
        
            let row = worksheet.addRow(rowData);
            
            // Appliquer le style de bordure à chaque cellule de la ligne
            row.eachCell({ includeEmpty: true }, cell => {
                cell.alignment = { vertical: 'middle', horizontal: 'right' }; 
                applyBorderStyle(cell);
            });
        }

        /* ------------------- CREATION DU NOM DU FICHIER ET GENEARTION ------------------ */
        var currentDate = new Date();
        var fileNameXls = "ExportCrenas_" + currentDate.toISOString().slice(0, 10).replace(/-/g, "") + "_" +
                  currentDate.getHours().toString().padStart(2, '0') +
                  currentDate.getMinutes().toString().padStart(2, '0') +
                  ".xlsx";

        // Génération du fichier Excel
        workbook.xlsx.writeBuffer().then(function(buffer) {
            // Téléchargement du fichier Excel
            saveAs(new Blob([buffer], { type: 'application/octet-stream' }), fileNameXls);
        });

    });

    // Fonction pour appliquer le style de bordure à une cellule
    function applyBorderStyle(cell) {
        cell.border = {
            top: { style: 'thin' },
            left: { style: 'thin' },
            bottom: { style: 'thin' },
            right: { style: 'thin' }
        };
    }

    // Fonction pour ajouter les en-têtes
    function addHeader(worksheet, cellRef, text, mergeCellsRange) {
        cellRef.value = text; 
        cellRef.font = { bold: true }; // Mettre le texte en gras
        cellRef.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' };
        applyBorderStyle(cellRef);
        worksheet.mergeCells(mergeCellsRange);
    }
    

    // Fonction pour ajouter une colonne de mois
    function addDataColumn(worksheet, startColumn, header, dataArray, withoutTotal = 0) {
        let col = startColumn;
        if (withoutTotal == 1) {
            addHeader(worksheet, worksheet.getCell(numberToColumnLetter(col) + "1"), header, numberToColumnLetter(col) + "1:" + numberToColumnLetter(col + (dataArray.length - 1)) + "1");
        } else {
            addHeader(worksheet, worksheet.getCell(numberToColumnLetter(col) + "1"), header, numberToColumnLetter(col) + "1:" + numberToColumnLetter(col + (dataArray.length)) + "1");
        }
        
        dataArray.forEach((dataValue, index) => {
            const cell = worksheet.getCell(numberToColumnLetter(col) + "2");
            cell.value = dataValue; 
            cell.font = { bold: true }; // Mettre le texte en gras 
            cell.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' }; 
            applyBorderStyle(cell);
            worksheet.mergeCells(numberToColumnLetter(col) + "2:" + numberToColumnLetter(col) + "3");
            col++;
        });

        if (withoutTotal == 0) {
            const cellTotal = worksheet.getCell(numberToColumnLetter(col) + "2");
            cellTotal.value = "Total"; 
            cellTotal.font = { bold: true }; // Mettre le texte en gras
            cellTotal.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' }; 
            applyBorderStyle(cellTotal);
            worksheet.mergeCells(numberToColumnLetter(col) + "2:" + numberToColumnLetter(col) + "3");
        }
    }
    
    function addDataSDUColumn(worksheet, startColumn, header, dataLotSDUArray, dataQuantiteDateSDUArray) {
        let col = startColumn; 
        let colStart = startColumn;
        addHeader(worksheet, worksheet.getCell(numberToColumnLetter(col) + "1"), header, numberToColumnLetter(col) + "1:" + numberToColumnLetter(col + 8)+ "1");

        dataLotSDUArray.forEach((dataValue, index) => {
            const cell = worksheet.getCell(numberToColumnLetter(col) + "2");
            cell.value = dataValue;
            cell.font = { bold: true }; // Mettre le texte en gras 
            cell.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' }; 
            applyBorderStyle(cell);
            worksheet.mergeCells(numberToColumnLetter(col) + "2:" + numberToColumnLetter(col+1) + "2");
            col = col + 2;
        });
        const cellTotal = worksheet.getCell(numberToColumnLetter(col) + "2");
        cellTotal.value = "Total"; 
        cellTotal.font = { bold: true }; // Mettre le texte en gras
        cellTotal.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' };
        applyBorderStyle(cellTotal);
        worksheet.mergeCells(numberToColumnLetter(col) + "2:" + numberToColumnLetter(col) + "3"); 
        let resultDataQuantiteArray = [
            ...dataQuantiteDateSDUArray,
            ...dataQuantiteDateSDUArray,
            ...dataQuantiteDateSDUArray,
            ...dataQuantiteDateSDUArray
        ];
        resultDataQuantiteArray.forEach((dataValue, index) => {
            const cellQte = worksheet.getCell(numberToColumnLetter(colStart) + "3");
            cellQte.value = dataValue; 
            cellQte.font = { bold: true }; // Mettre le texte en gras
            cellQte.alignment = { wrapText: true , vertical: 'middle', horizontal: 'center' };
            applyBorderStyle(cellQte);
            worksheet.mergeCells(numberToColumnLetter(colStart) + "3:" + numberToColumnLetter(colStart) + "3");
            colStart++;
        });
    }

    function numberToColumnLetter(number) {
        let temp,
            letter = '';
        while (number > 0) {
            temp = (number - 1) % 26;
            letter = String.fromCharCode(temp + 65) + letter;
            number = (number - temp - 1) / 26;
        }
        return letter;
    }


})