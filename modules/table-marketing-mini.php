<?php
if(isset($_SESSION['uname']))
{
  $query="SELECT cmpname,status FROM marketing_contacts";
  $result=$mysqli->query($query);
  $row=$result->fetch_assoc();
  $index=0;
  if (!$row)
    echo "Waiting for first contact!";
  else 
  {
    echo "<table>";
    echo " <thead>";
    echo "   <tr>";
    echo "     <th>Sl.</th>";
    echo "     <th>Company Name</th>";
    echo "     <th>Status</th>";
    echo "   </tr>";
    echo " </thead>";
    do 
    {
      $index = $index+1;
      echo "<tr>";
      echo "<td>".$index."</td>";
      echo "<td>".$row['cmpname']."</td>";
      echo "<td>".$row['status']."</td>";
      echo "</tr>";
    } while($row=$result->fetch_array());
  }
}
echo "</table><br/>";
?>