<?php
session_start();
include 'database/connect.php';

if ($_SESSION['log'] == '') {
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamUp</title>
    <link rel="website icon" type="png" href="pic/web-logo2.png">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/homestyle.css" />
    <link rel="stylesheet" href="css/projectstyle.css" />

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />

    <!-- <script src="js/homejs.js"></script> -->
    <script src="js/projectjs.js"></script>

</head>

<body>
    <header class="header" style="padding-left: 0px; padding-right: 0px;">
        <nav class="nav">
            <a href="home.php" class="nav_logo"><img src="pic/logo2.png" alt="TeamUp's Logo" style="height: 50px; width: auto;" /></a>

            <ul class="nav_items">
                <li class="nav_item">
                    <a href="home.php" class="nav_link">Home</a>
                    <a href="project.php" class="nav_link">Project</a>
                    <a href="activity.php" class="nav_link">Activity Tracking</a>
                    <a href="dashboard.php" class="nav_link">DashBoard</a>
                    <a href="group_chat.php" class="nav_link">Chat</a>
                </li>
            </ul>

            <div class="dropdown">
                <button id="dropdownButton" class="button">
                    <html><?php echo $_SESSION['pname'] . " ID: " . $_SESSION['id'] . "" ?>

                    </html>
                </button>
                <div id="myDropdown" class="dropdown-content">
                <a href="#" id="profile" class="nav_link">My Profile</a>
                    <!-- <a href="temp1.php" class="nav_link">Project Details</a>
                    <a href="#" id="changepass" class="button nav_link" style="border-color: white;">Change Password</a> -->
                    <a href="database/logout.php" class="nav_link">Log Out</a>
                </div>
            </div>

        </nav>
    </header>
    <section class="home">
        <!-- Home -->
        <section class="hero" style="padding-bottom: 0px; padding-top:50px">
            <div style="color: black;padding-top:50px">
                <table style="padding: 20px;">
                    <tr>
                        <td style="text-align: left;"><strong>Project Details:</strong></td>
                        <td style="text-align: left;"><strong>Name:</strong> <?php echo $_SESSION['pname']; ?></td>

                    </tr>
                    <tr></tr>
                    <tr>
                        <td></td>
                        <td style="text-align: left;"><strong>ID:</strong> <?php echo $_SESSION['id']; ?></td>
                    </tr>
                    <tr></tr>
                    <tr>
                        <td>
                            <h4>Members of Project:</h4>
                        </td>
                        <?php
                        include 'database/connect.php';

                        $p_id = $_SESSION['id'];

                        $sql = "SELECT m_email FROM member WHERE p_id = ?";

                        $stmt = $connect->prepare($sql);
                        $stmt->bind_param("s", $p_id);
                        $stmt->execute();
                        $stmt->bind_result($m_email);
                        $flag = false;

                        if ($stmt) {
                            while ($stmt->fetch()) {
                                $flag = true;
                                
                                echo '<td><span class="email">'. $m_email . '</span>   &nbsp;';
                                $check=$_SESSION["leader"];
                                
                                if($check!=0)
                                echo '<span class="delete" id="delete">&times;</span>';
                                
                                echo '</td></tr>';
                                echo '<tr></tr><tr><td></td>';
                            }
                        }
                        if (!$flag) {
                            echo '<td>  No Project-Member Yet!!</td>';
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <?php
            include 'database/connect.php';

            $email=$_SESSION['email'];
            $sqlq = "SELECT id FROM data WHERE email = ?";

            $stmt = $connect->prepare($sqlq);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($u_id);
            
            if($stmt->fetch()) {
                $stmt->close(); 
            $_SESSION['u_id']=$u_id;
            $p_id = $_SESSION['id'];

            $sql = "SELECT IsLeader FROM projects WHERE id = ? AND u_id = ?";
          
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $p_id, $u_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $isLeader);
            mysqli_stmt_fetch($stmt);

            if ($isLeader == 1) {

                echo '<div id="create-project-form">';
                echo '<h2 style="color: black;">Add New Member</h2>';
                echo '<input type="text" id="member-id" placeholder="Enter Member email" required>';
                echo ' <input id="add-button" type="submit" value="Add"></input>';
                echo '</div>';
            } else {
                echo '<div id="create-project-form">';
                echo '<h3 style="color: black;padding-left:100px;">You Can not add new Member!!</h3>';
                echo '</div>';
            }

        }
            ?>


            <br /><br /><br /><br /><br /><br />
            <footer class="footer">
        <div class="container">
            <p>&copy; 2024 TeamUp. All rights reserved.</p>
        </div>
    </footer>
    </section>
        <div class="form_container">
            <i class="uil uil-times form_close"></i>
            <div class="form profile_form">
                <h2>Your Profile</h2>

                <div class="input_box">
                    <h5 align="left">Name: </h5>
                    <input type="text" name="name" style="border-bottom: 1.5px solid #aaaaaa;" value="<?php echo "" . $_SESSION['name'] . "" ?>" readonly />
                    <i class="uil uil-text text"></i>
                </div>
                <div class="input_box">
                    <h5 align="left">Email: </h5>
                    <input type="email" name="email" value="<?php echo "" . $_SESSION['email'] . "" ?>" readonly />
                    <i class="uil uil-envelope-alt email"></i>
                </div>

                <div class="input_box">
                    <h5 align="left">Password: </h5>
                    <input type="password" name="password" value="Password: <?php echo "" . $_SESSION['password'] . ""; ?>" readonly />
                    <i class="uil uil-lock password"></i>
                </div>
                <h5 align="right"><a href="#" id="changepass"><br>Change Password?</a></h5>

            </div>
            <!-- change password -->
            <div class="form reset_form">
                <form action="database/update.php" method="post">
                    <h2>Change Password</h2>
                    <div class="input_box">
                        <input type="password" name="currentpassword" onchange="checkpassword()" id="currentpassword" placeholder="Current password" required />
                        <i class="uil uil-lock password" style="top: 50%;"></i>
                        <i class="uil uil-eye-slash pw_hide" style="top: 50%;"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="password" id="password" placeholder="Create password" required />
                        <i class="uil uil-lock password" style="top: 50%;"></i>
                        <i class="uil uil-eye-slash pw_hide" style="top: 50%;"></i>
                    </div>
                    <div class="input_box">
                        <input type="password" name="repassword" id="repassword" placeholder="Confirm password" required />
                        <i class="uil uil-lock password" style="top: 50%;"></i>
                        <i class="uil uil-eye-slash pw_hide" style="top: 50%;"></i>
                    </div>

                    <button class="button" id="change">Change</button>
                </form>
            </div>

        </div>
       
    </section>

    
</body>

</html>