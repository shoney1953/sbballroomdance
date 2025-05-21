 <?php
      $actLink
           = "<a href='https://calendar.google.com/calendar/u/2?cid=c2JiZGNzY2hlZHVsZUBnbWFpbC5jb20'>
       Click to view Activities Calendar</a><br>";
       $webLink
           = "<a href='https://www.sbballroomdance.com'>Click to go to the SBDC Website.</a>";
       $mailAttachment = "../img/Member Guide to Website Version 2.pdf"; 
 
       if ($formerUser === 'yes') {
        $emailBody = "<br>Welcome back <b> $toName </b> as a returning member 
        to the SaddleBrooke Ballroom Dance Club.<br><br>";
        $toCC4 = '';
       } else {
        $emailBody = "<br>Welcome <b> $toName </b>to the SaddleBrooke Ballroom Dance Club.<br><br>";
       }
 
       $emailBody .= "Thanks for signing up and paying your dues online! We hope you will participate in and enjoy the following club activities: <br><ul>";
       $emailBody .= "<li><strong>Novice Events</strong> - For anyone that has little dance experience, we have a new 
       program of Novice events: Novice classes twice a month, and then a Novice review session. These are designed to
       help you learn a few steps of the most common dances, and help you practice with other Novices. 
       You will also receive emails about these actvities. You can register for these on the website.</li>";
       $emailBody .= "<li><strong>Dance Classes</strong> - Classes are normally held at the MountainView Ballroom
       at the MountainView clubhouse on all the Sundays from 3pm to 5pm and Tuesdays from 4pm to 6pm of each month.
       Emails will go out regarding the content, format, instructors and dates; registration is also available on the website.</li>";
       $emailBody .= "<li><strong>Dance Parties</strong> - These are provided on most months during the year.
       They include a dance with a no host bar in the MountainView Ballroom, and may or not have some food served. 
       If there is a charge for attendance, there will be a form associated with the event and you may print it and
       send in meal selections with your payment to the treasurer. You may click on the VIEW tab of the event to get the form,
       but it will also be sent out by email and available at events and classes prior to the Dance Party.</li>";     
       $emailBody .= "<li><strong>Open Practice</strong> - These are slots open for practice dancing. They are not 
       exclusively for members, so you can bring friends. Often a DJ provides requested music. If no DJ is
       specified, you may bring your own music. Currently we have 3 different slots:
         <ul>
         <li>Available Mondays 4 - 6pm in the HOA1 Vermilion Room</li>
         <li>Fridays 4 - 6pm in the HOA1 Vermilion Room </li>
         <li>Wednesdays from 5pm to 6pm in the Mariposa Room at DesertView</li>
         </ul></li><br>";
       
       $emailBody .= "<li>Your name, email, phone and address will be listed in our directory by default unless
       you indicated otherwise on your membership form. But if you
       wish not to list your information in the directory, you can go to your profile on the website
       and set the option off or contact us and we will set it off.</li>";

     $emailBody .= "</ul><strong>At Times we have have room changes or cancellations, so it is important to check the Activities
     Calendar on the website to verify the schedule.<br>$actLink<br></strong>";

     $emailBody .= "
   We have attached a PDF that is an guide to the website.
   The website shows Classes, Dances, and other events. Your login credentials will be either: 
    <b>your email
     <em>or</em>  
     your firstname and last initial with the first letter of your first name capitalized and your last initial capitalized</b>.
   The initial password is <b>test1234</b>. You should change your password when you first logon from your profile.
   Once you logon to the website, you can register for most classes and events from there. 
   <br>
   $webLink<br>";

   $emailBody .= "We hope to see you soon!<br>";
   ?>