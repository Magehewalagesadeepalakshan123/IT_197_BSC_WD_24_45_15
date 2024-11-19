<?php

// Include database connection
include_once("db_conn.php");


// Create user registration function
  function userRegistration($username, $useremail, $userpass, $usertele, $userNic) {
   


  //create database  connection
  $db_conn= Connection();


    // Data insert query
    $insertSql = "INSERT INTO user_tbl(user_name, user_email, user_tele, user_Nic, login_status) VALUES ('$username', '$useremail', '$usertele', '$userNic', 1);";
    $sqlResult = mysqli_query($db_conn, $insertSql);

    // Check for database errors
    if (mysqli_connect_errno()) {
        echo (mysqli_connect_error());
        return "Try again!";
    }

    // If registration is successful, insert data into login table
    if ($sqlResult > 0) {
        $newPassword = md5($userpass);
        $insertLogin = "INSERT INTO login_tabl(login_email, login_pass, login_role, login_status) VALUES ('$useremail', '$newPassword', 'user', 1);";
        $loginResult = mysqli_query($db_conn, $insertLogin);

        // Check for errors in login table insertion
        if (mysqli_connect_errno()) {
            echo (mysqli_connect_error());
            return "Try again!";
        }
        return "Your registration was successful!";
    } else {
        return "Try again!";
    }
}

// Login function
function Authentication($useremail, $userpass) {
   
 // Fetch user details
    $db_conn =connection();
    $sqlFechUser = "SELECT * FROM login_tabl WHERE login_email = '$useremail';";
    $sqlResult = mysqli_query($db_conn, $sqlFechUser);

    if (mysqli_connect_errno()) {
        echo (mysqli_connect_error());
        return;
    }

    $newpass = md5($userpass);
    $norows = mysqli_num_rows($sqlResult);

    // Validate if any records were found
    if ($norows > 0) {
        $rec = mysqli_fetch_assoc($sqlResult);

        // Validate the password
        if ($rec['login_pass'] == $newpass) {

            // Check if the user is active
            if ($rec['login_status'] == 1) {

                if ($rec['login_role'] == "admin") {
                    // Redirect admin user to the admin dashboard
                    header('location: ../lib/views/dashboards/admin.php');
                    
                }else{
                  //redirct  to the user dashboard
                  header('location:lib/views/dashboards/user.php');
                }
                
        }else{
          return("tour  account  has  been deactivated");
        }
    } else{
      return("ypur  password  is  not  correct");
    }
  }else{
    return("No record  found");
  }
}
?>
