<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Photo & Video Gallery</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f8f9fa;
    }
    h2 {
      text-align: center;
      margin: 20px 0;
      color: #333;
    }
    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 15px;
      padding: 20px;
    }
    .gallery-item {
      cursor: pointer;
      overflow: hidden;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.3s;
      position: relative;
    }
    .gallery-item:hover {
      transform: scale(1.05);
    }
    .gallery-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 10px;
    }
    .gallery-overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.6);
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      color: white;
      opacity: 0;
      transition: 0.3s;
      border-radius: 10px;
    }
    .gallery-item:hover .gallery-overlay {
      opacity: 1;
    }
    .upload-form {
      max-width: 500px;
      margin: 20px auto;
      padding: 20px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .upload-form input, .upload-form button {
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      font-size: 16px;
    }
    .upload-form button {
      background: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }
    .upload-form button:hover {
      background: #0056b3;
    }
  </style>
</head>
<body>

  <h2>Photo & Video Gallery</h2>

 
  <?php
  // Database connection
  $conn = new mysqli("localhost", "root", "", "kolonnawatravels");

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Handle Upload
  if (isset($_POST['upload'])) {
      $title = $conn->real_escape_string($_POST['title']);
      $image = $_FILES['image']['name'];
      $target = "upload/" . basename($image);

      if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
          $conn->query("INSERT INTO gallery (title, image) VALUES ('$title', '$image')");
          echo "<script>alert('Photo uploaded successfully!'); window.location.href='';</script>";
      } else {
          echo "<script>alert('Failed to upload photo.');</script>";
      }
  }

  // Fetch Photos
  $result = $conn->query("SELECT * FROM gallery where title ='dubai'");
  ?>

  <!-- Gallery Start -->
  <div class="gallery">
      <?php while($row = $result->fetch_assoc()): ?>
          <div class="gallery-item">
              <img src="upload/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>">
              <div class="gallery-overlay">
                  <h5><?php echo $row['title']; ?></h5>
                  <a href="upload/<?php echo $row['image']; ?>" target="_blank" class="btn btn-light">
                      <i class="fas fa-eye"></i> View
                  </a>
              </div>
          </div>
      <?php endwhile; ?>
  </div>
  <!-- Gallery End -->

</body>
</html>
