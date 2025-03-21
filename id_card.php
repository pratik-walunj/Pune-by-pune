<?php
// Database connection
$servername = "localhost:3307";
$username = "root";
$password = "";
$database = "id_card_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users from the database
$sql = "SELECT photo, name, community, domain, area, pin_code FROM users";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    die("No users found");
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin ID Cards</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <style>
  body {
    background-color: #e3f2fd;
  }

  .id-card {
    width: 250px;
    height: 400px;
    padding: 20px;
    border: 2px solid #007bff;
    border-radius: 15px;
    text-align: center;
    box-shadow: 4px 4px 15px rgba(0, 0, 0, 0.2);
    background-color: #ffffff;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 10px;
  }

  .id-card img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid #007bff;
  }

  .id-card h5,
  .id-card h6 {
    margin: 5px 0;
    font-weight: bold;
    color: #333;
  }

  .id-card h5 {
    color: #007bff;
  }

  .container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }

  .download-btn {
    margin-top: 20px;
  }
  </style>
  <script>
  function downloadAllIDCards() {
    const cards = document.querySelectorAll(".id-card");
    let images = [];

    let captureCard = (index) => {
      if (index >= cards.length) {
        zipDownload(images);
        return;
      }
      html2canvas(cards[index]).then(canvas => {
        images.push({
          name: `id_card_${index + 1}.png`,
          data: canvas.toDataURL("image/png")
        });
        captureCard(index + 1);
      });
    };

    captureCard(0);
  }

  function zipDownload(images) {
    let zip = new JSZip();
    images.forEach(img => {
      let imgData = img.data.split(',')[1];
      zip.file(img.name, imgData, {
        base64: true
      });
    });

    zip.generateAsync({
      type: "blob"
    }).then(content => {
      let link = document.createElement("a");
      link.href = URL.createObjectURL(content);
      link.download = "ID_Cards.zip";
      link.click();
    });
  }
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
</head>

<body>
  <div class="container">
    <?php foreach ($users as $user) { ?>
    <div class="id-card">
      <img src="<?php echo $user['photo']; ?>" alt="User Photo">
      <h5><?php echo $user['name']; ?></h5>
      <h6>Community: <?php echo $user['community']; ?></h6>
      <h6>Domain: <?php echo $user['domain']; ?></h6>
      <h6>Area: <?php echo $user['area']; ?></h6>
      <h6>Pin Code: <?php echo $user['pin_code']; ?></h6>
    </div>
    <?php } ?>
  </div>
  <div class="text-center">
    <button class="btn btn-primary download-btn" onclick="downloadAllIDCards()">Download ID Cards</button>
  </div>
</body>

</html>