<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Require an authenticated session to access this page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // send the user to the login page
    header('Location: login.php');
    exit();
}
// LOGOUT
if (isset($_POST["Logout"])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
   
    <meta charset="UTF-8">
    <title>Admin Panel – kolonnawatravels</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0; padding: 0;
        }
        nav {
            background: #333;
            padding: 0 20px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 20px;
            display: inline-block;
            line-height: 50px;
        }
        nav a:hover {
            text-decoration: underline;
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin: 8px 0 4px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            height: 80px;
        }
        .buttons input {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 16px;
            margin-right: 8px;
            border-radius: 4px;
            cursor: pointer;
        }
        .buttons input:hover {
            background: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #f4b6b6;
        }
    </style>
    <script>
    // If page is restored from browser history (bfcache), force a reload so session guard runs
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation && window.performance.navigation.type === 2)) {
            // reload from server to ensure authentication is checked
            window.location.reload();
        }
    });
    </script>
</head>
<body>

<nav>
    <a href="registeradminpannel.php">Home</a>
    <a href="adminpannel.php">Gallery Admin</a>
    <!-- use POST form for logout so it triggers server-side clear -->
    <form method="post" style="display:inline;margin-left:12px">
        <button type="submit" name="Logout" style="background:#e74c3c;color:#fff;border:none;padding:8px 12px;border-radius:4px;cursor:pointer">Logout</button>
    </form>
</nav>

<div class="container">
    <h2>User Registration</h2>
    <form method="POST" action="registeradminpannel.php">
        <label for="Name">Name</label>
        <input type="text" name="Name" id="Name" required>

        <label for="Telephone">Telephone</label>
        <input type="text" name="Telephone" id="Telephone" required>

        <label for="Email">Email</label>
        <input type="email" name="Email" id="Email" required>

        <label for="Address">Address</label>
        <input type="text" name="Address" id="Address" required>

        <label for="Age">Age</label>
        <input type="number" name="Age" id="Age" min="1" required>

        <label for="Message">Message</label>
        <textarea name="Message" id="Message"></textarea>

        <label for="TourType">Tour Type</label>
        <select name="TourType" id="TourType" required>
            <option value="">-- Select --</option>
            <option>Thailand tour</option>
            <option>Malaysia tour</option>
            <option>Singapore tour</option>
            <option>India tour</option>
            <option>Dubai tour</option>
            <option>Indonesia tour</option>
        </select>

        <div class="buttons">
            <input type="submit" name="submit" value="Register">
            <input type="submit" name="Update" value="Update">
            <!-- removed duplicate Logout from inside the form to avoid accidental logout -->
        </div>
    </form>

    <h2>Search / Delete / Report Members</h2>
    <form method="POST" action="registeradminpannel.php">
        <label for="id">Member ID</label>
        <input type="number" name="id" id="id" >

        <div class="buttons">
            <input type="submit" name="search" value="Search">
            <input type="submit" name="Delete" value="Delete">
            <input type="submit" name="Report" value="Report">
        </div>
    </form>
</div>

<?php
// REGISTER
if (isset($_POST["submit"])) {
    $Name      = $_POST["Name"];
    $Telephone = $_POST["Telephone"];
    $Email     = $_POST["Email"];
    $Address   = $_POST["Address"];
    $Age       = (int) $_POST["Age"];
    $Message   = $_POST["Message"];
    $TourType  = $_POST["TourType"];
    $status    = 'Pending';

    $con = mysqli_connect("localhost", "root", "", "kolonnawatravels")
        or die("Couldn't connect to server");

    $sql = "
      INSERT INTO registration
        (Name, Telephone, Email, Address, Age, Message, TourType, status)
      VALUES
        ('$Name','$Telephone','$Email','$Address',$Age,'$Message','$TourType','$status')
    ";
    $query = mysqli_query($con, $sql);

    if ($query) {
        echo "<h1 align='center'>Successfully Registered</h1><br>";
        echo "Name: $Name<br>";
        echo "Telephone: $Telephone<br>";
        echo "Email: $Email<br>";
        echo "Address: $Address<br>";
        echo "Age: $Age<br>";
        echo "Message: $Message<br>";
        echo "Tour Type: $TourType<br>";
    } else {
        echo "<p align='center'>Insert Error: " . mysqli_error($con) . "</p>";
    }
    mysqli_close($con);
}

// SEARCH
if (isset($_POST["search"])) {
    $id = (int) $_POST['id'];
    $con = mysqli_connect("localhost", "root", "", "kolonnawatravels")
        or die("Couldn't connect to server");

    $query = mysqli_query($con, "SELECT * FROM registration WHERE id=$id");
    $nor   = mysqli_num_rows($query);

    if ($nor < 1) {
        echo "<p align='center'>No Member Found with ID $id</p>";
    } else {
        $rec = mysqli_fetch_assoc($query);
        echo "<h1 align='center'>Search Result</h1><br>";
        echo "ID        = " . $rec['id']        . "<br>";
        echo "Name      = " . $rec['Name']      . "<br>";
        echo "Telephone = " . $rec['Telephone'] . "<br>";
        echo "Email     = " . $rec['Email']     . "<br>";
        echo "Address   = " . $rec['Address']   . "<br>";
        echo "Age       = " . $rec['Age']       . "<br>";
        echo "Message   = " . $rec['Message']   . "<br>";
        echo "TourType  = " . $rec['TourType']  . "<br>";
        echo "Status    = " . $rec['status']    . "<br>";
    }
    mysqli_close($con);
}

// UPDATE
if (isset($_POST["Update"])) {
    $id        = (int) $_POST["id"];
    $Name      = $_POST["Name"];
    $Telephone = $_POST["Telephone"];
    $Email     = $_POST["Email"];
    $Address   = $_POST["Address"];
    $Age       = (int) $_POST["Age"];
    $Message   = $_POST["Message"];
    $TourType  = $_POST["TourType"];

    $con = mysqli_connect("localhost", "root", "", "kolonnawatravels")
        or die("Couldn't connect to server");

    $sql = "
      UPDATE registration
      SET
        Name='$Name',
        Telephone='$Telephone',
        Email='$Email',
        Address='$Address',
        Age=$Age,
        Message='$Message',
        TourType='$TourType'
      WHERE id=$id
    ";
    $query = mysqli_query($con, $sql);

    if ($query) {
        echo "<h1 align='center'>Record Updated</h1><br>";
        echo "ID: $id<br>";
        echo "Name: $Name<br>";
        echo "Telephone: $Telephone<br>";
        echo "Email: $Email<br>";
        echo "Address: $Address<br>";
        echo "Age: $Age<br>";
        echo "Message: $Message<br>";
        echo "TourType: $TourType<br>";
    } else {
        echo "<p align='center'>Update Error: " . mysqli_error($con) . "</p>";
    }
    mysqli_close($con);
}

// DELETE
if (isset($_POST["Delete"])) {
    $id = (int) $_POST['id'];
    $con = mysqli_connect("localhost", "root", "", "kolonnawatravels")
        or die("Couldn't connect to server");

    $query = mysqli_query($con, "DELETE FROM registration WHERE id=$id");
    if ($query && mysqli_affected_rows($con) > 0) {
        echo "<h1 align='center'>Record Deleted</h1><br>";
        echo "Deleted ID: $id";
    } else {
        echo "<p align='center'>No record found to delete with ID $id</p>";
    }
    mysqli_close($con);
}

// REPORT
if (isset($_POST["Report"])) {
    $con = mysqli_connect("localhost", "root", "", "kolonnawatravels")
        or die("Couldn't connect to server");

    $query = mysqli_query($con, "SELECT * FROM registration");
    $nor   = mysqli_num_rows($query);

    if ($nor < 1) {
        echo "<p align='center'>No registrations found.</p>";
    } else {
        echo "<table border='2' align='center' width='80%'>";
        echo "<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Telephone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Age</th>
                <th>Message</th>
                <th>Tour Type</th>
                <th>Status</th>
              </tr>";
        while ($rec = mysqli_fetch_assoc($query)) {
            echo "<tr>";
            echo "<td>{$rec['id']}</td>";
            echo "<td>{$rec['Name']}</td>";
            echo "<td>{$rec['Telephone']}</td>";
            echo "<td>{$rec['Email']}</td>";
            echo "<td>{$rec['Address']}</td>";
            echo "<td>{$rec['Age']}</td>";
            echo "<td>{$rec['Message']}</td>";
            echo "<td>{$rec['TourType']}</td>";
            echo "<td>{$rec['status']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    mysqli_close($con);
}


?>
</body>
</html>