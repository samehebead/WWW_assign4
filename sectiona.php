<?php
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error){
    echo "ERROR CONNECTING";
    die($conn->connect_error);
  }
  if (isset($_POST['fname']) && isset($_POST['lname'])){
    $fname = get_post($conn, 'fname');
    $lname = get_post($conn, 'lname');
    $usercode = get_post($conn, 'ucode');
    $email = get_post($conn, 'email');
    $password = get_post($conn, 'password');
    $insert_query = "INSERT INTO user_profiles (fname, lname, usercode, email, password) VALUES (?, ?, ?, ?, ?);";

    $stmt = $conn->prepare($insert_query);

    $stmt->bind_param("ssiss", $fname, $lname, $usercode, $email, $password);

    if($stmt->execute()) {
        echo "Successfully added $type_name: $fname $lname<br />Email: $email<br /><br />";
    } else {
        echo "INSERT failed: $insert_query<br />" . $conn->error . "<br /><br />";
    }
  }
  $query  = "SELECT * FROM user_codes";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);
  $rows = $result->num_rows;
  echo <<<_END
  <form action="sectiona.php" method="post"><pre>
    First Name: <input type="text" name="fname" required>
    Last Name: <input type="text" name="lname" required>
    User Type: <select name="ucode" required>
_END;
  for ($j = 0 ; $j < $rows ; ++$j){
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo <<<_END
      <option value="$row[0]">$row[1]</option>
_END;
    }
    echo <<<_END
    </select>
    Email: <input type="text" name="email" required>
    Password: <input type="password" name="password" required>
    <input type="submit" value="Submit">
  </pre></form>
_END;
  $result->close();
  $conn->close();

  function get_post($conn, $var){
    return $conn->real_escape_string($_POST[$var]);
  }
?>