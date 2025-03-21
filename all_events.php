<?php
include('navbar.php');
include 'connection.php';

$sql = "SELECT event_id, event_name, event_description, event_time FROM events ORDER BY event_time DESC";
$eventResult = $conn->query($sql);

$colors = [
   '#FFEB3B'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Events</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./CSS/home.css">
  <style>
  body {
    background: url('uploads/wall2.jpg');
    /* Add a textured pattern */
    background-size: cover;
  }
  </style>
</head>

<body>


  <div class="container my-5">
    <div class="col-md-3 d-flex justify-content-start">
      <div class="input-group" style="max-width: 250px; width: 100%;">
        <input type="text" class="form-control" id="eventSearchInput" placeholder="Search Events..." aria-label="Search"
          onkeyup="filterEvents()">
        <button class="btn btn-warning" type="button">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </div>


    <div class="col-md-6 text-center">
      <h2 class="mb-0">All Events</h2>
    </div>

    <div class="col-md-3"></div>

    <div class="row gy-4">
      <?php
            if ($eventResult->num_rows > 0):
                while ($row = $eventResult->fetch_assoc()):
                    $formatted_time = date('D, M j, Y, g:i A ', strtotime($row['event_time']));

                    $random_color = $colors[array_rand($colors)];
            ?>
      <div class="col-md-4">
        <a href="event_info.php?event_id=<?php echo $row['event_id']; ?>" class="text-decoration-none">
          <div class="card shadow-lg" style="background-color: <?php echo $random_color; ?>;height: 300px">
            <!-- <div class="card-body"> -->
            <div class="card-body" style="background: url('uploads/wall2.jpg') no-repeat center center/cover;">

              <h5 class="card-title text-dark "><?php echo $row['event_name']; ?></h5>
              <p class="card-text text-truncate"
                style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 8; -webkit-box-orient: vertical; word-wrap: break-word; white-space: normal;">
                <?php echo strip_tags($row['event_description'], '<b><i><strong>'); ?>
              </p>
              <span class="badge bg-info text-dark"><?php echo $formatted_time; ?></span>
            </div>
          </div>
        </a>
      </div>
      <?php
                endwhile;
            else:
            ?>
      <p class="text-center">No events found.</p>
      <?php endif; ?>
    </div>
  </div>
  </div>

  <script>
  function filterEvents() {
    let input = document.getElementById("eventSearchInput").value.toLowerCase();
    let eventCards = document.querySelectorAll(".col-md-4");

    eventCards.forEach((card) => {
      let title = card.querySelector(".card-title").innerText.toLowerCase();
      let description = card.querySelector(".card-text").innerText.toLowerCase();

      if (title.includes(input) || description.includes(input)) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  }
  </script>


  <?php include('footer.php') ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>