<?php
 include_once 'config.php'; 
 
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
  <title>Tech Active - Akshay Kumar</title>
  <style>
    .drop-zone {
      border: 2px dashed #ccc;
      padding: 20px;
      text-align: center;
      cursor: pointer;
    }

    .drop-zone.dragged {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>

<?php
// index.php

// Check if a success or error message is present in the URL parameters
if (isset($_GET['success'])) {
    $message = $_GET['success'];
    $alertClass = 'alert-success';
} elseif (isset($_GET['error'])) {
    $message = $_GET['error'];
    $alertClass = 'alert-danger';
}

// Display the message if it exists
if (isset($message)) {
    echo '<div class="alert ' . $alertClass . '">' . $message . '</div>';
}
?>


<div class="container mt-5">
<div class="row">
  <div class="col-md-6 offset-md-3">
    <form action="insert.php" method="POST" enctype="multipart/form-data">
      <div id="dropZone" class="drop-zone">
        <h5>Drag and Drop your file here</h5>
        <p>or</p>
        <p>
          <span>Select Manually</span>
          <input type="file" id="fileInput" class="form-control" name="file" multiple>
        </p>
        <ul id="fileList" class="list-group mt-3"></ul>
      </div>
      <div class="col-15 d-flex justify-content-center">
        <button type="submit" name="submit" class="btn btn-primary mt-3">Upload</button>
      </div>
    </form>
  </div>
</div>

<div class="container">
  <a href="token.php" class="btn btn-primary">Generate Token</a>
</div>

<script>
  const dropZone = document.getElementById('dropZone');
  const fileInput = document.getElementById('fileInput');
  const fileList = document.getElementById('fileList');

  dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragged');
  });

  dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragged');
  });

  dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragged');
    const files = e.dataTransfer.files;
    handleFiles(files);
  });

  fileInput.addEventListener('change', () => {
    const files = fileInput.files;
    handleFiles(files);
  });

  function handleFiles(files) {
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      console.log('File:', file);
      const listItem = document.createElement('li');
      listItem.classList.add('list-group-item');
      listItem.textContent = file.name;
      fileList.appendChild(listItem);
    }

    // Update the file input's value with the new files
    fileInput.files = files;
  }
</script>

</body>

</html>
