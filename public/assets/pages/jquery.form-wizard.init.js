/**
 * Theme: Amezia - Responsive Bootstrap 4 Admin Dashboard
 * Author: Themesbrand
 * Form Wizard
 */


$(function ()
{
    $("#form-horizontal").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slide"
    });
   
    $("#form-vertical").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex) {
            $('#form-alignement').parsley();
            return true;
            
          }, 
         /*  onStepChanged: function (event, currentIndex) {
            alert('testtt changé');
            
          }, */
        onFinished: function (event, currentIndex) {
            alert('testtt');
            
          }
    });
    $("#formAlign").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            numblock = "block"+b;
            var aa01 = $("#formAlign").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
          }, 
           
         
          onFinishing: function (event, currentIndex) {
            b = currentIndex + 1;
            numblock = "block"+b;
            var aa01 = $("#formAlign").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
            
          },
        onFinished: function (event, currentIndex) {
            alert('vita');
            
          }
    });
    $("#formExo01").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            numblock = "block"+b;
            var aa01 = $("#formExo01").parsley().validate(numblock);
               if (aa01 ==true){
                return true;
            } else {
                return false;
            }
          }, 
        onFinishing: function (event, currentIndex)
        {
            b = currentIndex + 1;
            numblock = "block"+b;
            var aa01 = $("#formExo01").parsley().validate(numblock);
               if (aa01 ==true){
                return true;
            } else {
                return false;
            }
        },            
        onFinished: function (event, currentIndex) {
            alert('testtt');
            
          }
    });
    $("#infogenerale").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            nblock = "blockA"+b;
            var aa01 = $("#infogenerale").parsley().validate(nblock);
            
            
               if (aa01 ==true){
                
                return true;
            } else {
                
                
                
               
                return false; 
            }
          }, 
        onFinished: function (event, currentIndex) {
            // $('#btnNext-etape1').prop('disabled', false);
            $('#exampleModal01').modal('show');
          }
    }); 
    $("#info-ouvrage").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            numblock = "blockB"+b;
            var aa01 = $("#info-ouvrage").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
          }, 
           
         
          onFinishing: function (event, currentIndex) {
            b = currentIndex + 1;
            numblock = "blockB"+b;
            var aa01 = $("#info-ouvrage").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
            
          },
        onFinished: function (event, currentIndex) {
            
            vaffectationAutre = $('#affectationAutre').html();
            
            if (vaffectationAutre == 'ok') {    
                
                $('#affectAutreMsg').html("Vous passez directement à Etape 4 car vous avez choisi comme Principale Affectation de l'ouvrage : Clôture ou Tombeau.");
                $('#nextStepTitle, #btnNext-etape2').html('ETAPE SUIVANTE : DOCUMENTS A FOURNIR');
                
            } else {
                $('#affectAutreMsg').html('');
                $('#nextStepTitle,#btnNext-etape2').html('ETAPE SUIVANTE : EVALUATION TECHNIQUE');
               
            }
            $('#exampleModal02').modal('show'); 
          }
    });
    $("#evaluation-technique").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            numblock = "blockC"+b;
            var aa01 = $("#evaluation-technique").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
          }, 
           
         
          onFinishing: function (event, currentIndex) {
            b = currentIndex + 1;
            numblock = "blockC"+b;
            var aa01 = $("#evaluation-technique").parsley().validate(numblock);
            if (aa01 ==true){
                return true;
            } else {
                return false;
            }
            
          },
        onFinished: function (event, currentIndex) {
            // $('#btnNext-etape3').prop('disabled', false);
            $('#exampleModal03').modal('show'); 
          }
    });
    $("#dossier-fourni").steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical",
        labels: {
            current: "current step:",
            pagination: "Pagination",
            finish: "Terminer",
            next: "Suivant",
            previous: "Precedent",
            loading: "Loading ..."
        },
        onStepChanging: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }

            b = currentIndex + 1;
            numblock = "blockD"+b;
            
            var aa01 = $("#dossier-fourni").parsley().validate(numblock);
            
            if (aa01 ==true){
                
                return true;
            } else {
                
                return false;
            }
          }, 
          onFinishing: function (event, currentIndex,newIndex) {
            if (currentIndex > newIndex)
        {
            return true;
        }
            b = currentIndex + 1;
            numblock = "blockD"+b;
            var aa01 = $("#dossier-fourni").parsley().validate(numblock);
            
            if (aa01 ==true){
               
                return true;
            } else {
               
                return false;
            }
          },
        
        onFinished: function (event, currentIndex) {
            // $('#btnNext-finale').prop('disabled', false);
            $('#exampleModal04').modal('show'); 
          }
    });
    $("#example-basic").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    });
});

