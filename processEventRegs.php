<?php

    
 require '../addEventReg.php';
 require '../updateEventReg.php';
 require '../deleteEventReg.php';

       
     
     ?>
     <script>
       function v1Meals() { 
     
            var element1 = document.getElementById('visitor1meals');
          
        // Select the element
        if (document.getElementById('attdin1').checked) {

           element1.classList.remove('hidden');
        }
        else {
 
            element1.classList.add('hidden');

            }
        }
        function v2Meals() { 
 
        var element1 = document.getElementById('visitor2meals');
  
        // Select the element
        if (document.getElementById('attdin2').checked) {
    
       
        element1.classList.remove('hidden');
        }
        else {
        
      
            element1.classList.add('hidden');

            }
         }

        function displayMeals1(userid) {
        var register =  "us"+userid; 
        var formcontainer =  "fc"+userid;

        // Select the element
        if (dfunctionocument.getElementById(register).checked) {
    
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
         
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');

            }
        }

        function displayMeals2(userid) {

        var attenddinner = 'datt'+userid;
        var formcontainer =  "fc"+userid;

        // Select the element
        if (document.getElementById(attenddinner).checked) {
    
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
        
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');

            }
        }

        function displayBBQ(userid) {

             
        var attenddinner = 'datt'+userid;

        var formcontainer =  "bq"+userid;

         var element1 = document.getElementById(formcontainer);
   
        // Select the element
        if (document.getElementById(attenddinner).checked) {
            element1.classList.remove('hidden');
            }
        else {
            element1.classList.add('hidden');
            }
        }
         function displayBBQV1() {

             
        var attenddinner = 'attdin1';

        var formcontainer =  'displayV1';

         var element1 = document.getElementById(formcontainer);
   
        // Select the element
        if (document.getElementById(attenddinner).checked) {
            element1.classList.remove('hidden');
            }
        else {
            element1.classList.add('hidden');
            }
        }

        function displayBBQV2() {

           console.log('inside bbqv1') ; 
        var attenddinner = 'attdin2';

        var formcontainer =  'displayV2';

         var element1 = document.getElementById(formcontainer);
   
        // Select the element
        if (document.getElementById(attenddinner).checked) {
            element1.classList.remove('hidden');
            }
        else {
            element1.classList.add('hidden');
            }
        }

        function displayMeals2U(regid) {
       
        var attenddinner = 'dddin'+regid;
        var formcontainer =  "fcu"+regid;

        // Select the element
        if (document.getElementById(attenddinner).checked) {
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
          
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');
            }
        }

        function displayMeals3U(regid) {
 
        var update =  "upd"+regid; 
        var formcontainer =  "fcu2"+regid;

        // Select the element
        if (document.getElementById(update).checked) {
        
  
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
      
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');

            }
        }
        
         
    </script>