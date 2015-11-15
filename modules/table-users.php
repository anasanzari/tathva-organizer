<?php

$users = User::selectAll($db);
if(sizeof($users)==0){
    echo "No Users Yet!!!";
}else{
    
?>
    
<?php

    foreach ($users as $user) {
        $u = $user->getName();
        $x = "exec.php?u=$u";
        $v = "<a ".'class="btn btn-default btn-xs"'. "href='$x&a=";
        $v .= ($user->getValidate() == 0) ? "val'>Validate" : "inv'>Invalidate";
        $v .= "</a>";
        $confirm = $user->getValidate()==0?"Not confirmed":"confirmed";
        echo "<tr>
          <td>{$user->getRoll()}</td>
          <td>{$user->getName()}</td>
          <td>{$user->getPassword()}</td>
          <td>{$user->getEventcode()}</td>
          <td>$v</th>
          <td><a ".'class="btn btn-default btn-xs"'. "href='$x&a=del'>Delete</a></td>
          <td>$confirm</td>
        </tr>";

    }
    echo "</tbody></table>";
}
?>