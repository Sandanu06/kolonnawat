<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "kolonnawatravels");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// HANDLE UPLOAD
if (isset($_POST["Upload"])) {
    $targetDir = "upload/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $title = $_POST['title'];

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
        $stmt = $conn->prepare("INSERT INTO gallery (image, title) VALUES (?, ?)");
        $stmt->bind_param("ss", $fileName, $title);
        if($stmt->execute()) {
            echo "<p>Image uploaded successfully!</p>";
        } else {
            echo "<p>Error saving to database: " . $conn->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Upload failed!</p>";
    }
}

// HANDLE SEARCH
if (isset($_POST["Search"])) {
    $id = $_POST['id'];
    $query = $conn->query("SELECT * FROM gallery WHERE id='$id'");
    if ($query->num_rows < 1) {
        echo ("Invalid Entry - No Image Found");
    } else {
        $rec = $query->fetch_assoc();
        echo "<h1 align='center'>Search Result</h1>";
        echo "ID = " . $rec['id'] . "<br>";
        echo "Title = " . $rec['title'] . "<br>";
        echo "<img src='upload/".$rec['image']."' width='200'><br>";
    }
}

// HANDLE DELETE
if (isset($_POST["Delete"])) {
    $id = $_POST['id'];
    $query = $conn->query("SELECT * FROM gallery WHERE id='$id'");
    if ($query->num_rows < 1) {
        echo ("Invalid Entry - No Image Found");
    } else {
        $rec = $query->fetch_assoc();
        @unlink("upload/".$rec['image']); // delete file
        $conn->query("DELETE FROM gallery WHERE id='$id'");
        echo "<h1 align='center'>Image deleted successfully</h1>";
    }
}

// HANDLE REPORT
if (isset($_POST["Report"])) {
    $query = $conn->query("SELECT * FROM gallery");
    if ($query->num_rows < 1) {
        echo "No images found.";
    } else {
        echo "<h2 align='center'>Gallery Report</h2>";
        echo "<table border='2' align='center' width='60%'>";
        echo "<tr><th>ID</th><th>Title</th><th>Image</th></tr>";
        while ($rec = $query->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$rec['id']."</td>";
            echo "<td>".$rec['title']."</td>";
            echo "<td><img src='upload/".$rec['image']."' width='100'></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

// HANDLE LOGOUT
if (isset($_POST["Logout"])) {
    header('Location: login.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Gallery Management - Admin</title>
  <style>
    :root{--bg:#f7fbff;--card:#fff;--accent:#1565c0;--muted:#666}
    body{font-family:Segoe UI,Arial,Helvetica,sans-serif;background:var(--bg);margin:0;padding:24px}
    nav{background:#333;padding:10px 20px;border-radius:6px}
    nav .nav-inner{max-width:1100px;margin:0 auto;display:flex;align-items:center;gap:12px}
    nav a{color:#fff;text-decoration:none;margin-right:12px}
    .container{max-width:900px;margin:22px auto;background:var(--card);padding:18px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06)}
    h2{margin-top:0;color:#222;text-align:center}
    label{display:block;margin:8px 0 6px;color:var(--muted)}
    select,input[type=file],input[type=text]{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box}
    .controls{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px}
    .controls button{background:var(--accent);color:#fff;border:none;padding:10px 14px;border-radius:6px;cursor:pointer}
    .controls button.logout{background:#e74c3c}
    table{width:100%;border-collapse:collapse;margin-top:18px}
    th,td{border:1px solid #e6e6e6;padding:10px;text-align:center}
    th{background:var(--accent);color:#fff}
    .img-thumb{max-width:120px;border-radius:6px}
    .form-row{margin-bottom:12px}
  </style>
</head>
<body>
  <nav>
    <div class="nav-inner">
      <a href="registeradminpannel.php">Registration Admin</a>
      <a href="adminpannel.php">Gallery Admin</a>
      <div style="margin-left:auto">
        <form method="post" style="display:inline">
          <button type="submit" name="Logout" class="logout" style="padding:8px 12px;border-radius:6px;border:none;cursor:pointer;color:#fff;background:#e74c3c">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container">
    <h2>Gallery Management</h2>

    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-row">
        <label for="title">Select title</label>
        <select name="title" id="title">
          <option value="">--Choose title--</option>
          <option value="india">india</option>
          <option value="thailand">thailand</option>
          <option value="malayasia">malayasia</option>
          <option value="singapore">singapore</option>
          <option value="dubai">dubai</option>
          <option value="indonesia">indonesia</option>
        </select>
      </div>

      <div class="form-row">
        <label>Choose Image</label>
        <input type="file" name="image">
      </div>

      <div class="controls">
        <button type="submit" name="Upload">Upload</button>
        <button type="submit" name="Delete">Delete</button>
        <button type="submit" name="Report">Report</button>
        <button type="submit" name="Search">Search</button>
      </div>

      <div class="form-row" style="margin-top:12px">
        <label for="id">ID (for Search/Delete)</label>
        <input type="text" name="id" id="id">
      </div>
    </form>

    <!-- results/report output will be printed below by existing PHP logic -->

  </div>
</body>
</html>