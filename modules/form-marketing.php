<?php
require_once("initdb.php");
if (isset($_POST["submit"])) 
{
  $in="INSERT INTO  `marketing_contacts` (tel ,tel2 , offer, cmpname ,contactper , status, email, notes, submitted_by) VALUES ('$_POST[tel]',  '$_POST[tel2]', '$_POST[offer]',  '$_POST[cmpname]',  '$_POST[contactper]',  'Check',  '$_POST[email]', '$_POST[notes]', '$_SESSION[uname]');";
  $mysqli->query($in);
}
?>
<table class=\"well form-inline\" summary="" >ADD A NEW CONTACT
  <form  method="post" action="#">
    <tr>
      <td><label  for="cmpname"> Name of the Company  </label></td>
      <td><input type="text" name="cmpname" placeholder="Eg. Bajaj Industries, Calicut" value="" autofocus="autofocus" /></td>     
    </tr>       
    <tr>
      <td><label for="contactper">Contact Person </label></td><td><input type="text" name="contactper" placeholder="Eg. John Pereira" value="" /></td>
    </tr>
    <tr>
      <td><label  for="email" >Email</label></td>
      <td><input type="email" name="email" placeholder="Eg. jpereira@bajaj.com" value=""/></td>
    </tr>
    <tr>
      <td><label for="tel" >Telephone</label></td>
      <td><input type="number" min="0" max="9999999999" name="tel" placeholder="Eg. 987654321" value="" /></td>
    </tr>
    <tr>
      <td><label for="tel2" >Mobile </label></td>
      <td><input type="number" min="0" max="9999999999" name="tel2" placeholder="Eg. 987654321" value="" /></td>
    </tr> 
    <tr>
      <td><label for="offer" >Offered</label></td>
      <td><input type="text" name="offer" placeholder="Eg. 40k" value="" /> 
      </td>
    </tr> 
    <tr>
      <td><label for="nego" >Notes </label></td>
      <td><textarea   rows="5" cols="15" style="display:block;" tabindex="1" dir="ltr" name="notes" placeholder="Any details to be noted"></textarea></td>
    </tr> 
    <tr>
      <td><br /> <br />
        <input type="submit" name="submit" value="Submit" />
      </td>       
    </tr>
  </form>   
</table>