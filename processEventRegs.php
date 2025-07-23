<?php

    
 require '../addEventReg.php';
 require '../updateEventReg.php';
 require '../deleteEventReg.php';

       
     
     ?>
     <script>
        function displayMeals1(userid) {

        var register =  "us"+userid; 
        var formcontainer =  "fc"+userid;

        // Select the element
        if (document.getElementById(register).checked) {
    
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
            console.log('notchecked');
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
            console.log('notchecked');
            var element1 = document.getElementById(formcontainer);
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
            console.log('notchecked');
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');
            }
        }

        function displayMeals3U(regid) {
 
        var update =  "upd"+regid; 
        var formcontainer =  "fcu2"+regid;

        // Select the element
        if (document.getElementById(update).checked) {
        console.log(formcontainer);
        var element1 = document.getElementById(formcontainer);
        element1.classList.remove('hidden');
        }
        else {
            console.log('notchecked');
            var element1 = document.getElementById(formcontainer);
            element1.classList.add('hidden');

            }
        }
        
         
    </script>