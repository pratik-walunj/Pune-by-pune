<?php
include('navbar.php');
include('connection.php');

$sql = "SELECT communities.community_id, communities.community_name, communities.community_description, communities.image_path, 
        COUNT(community_members.user_id) AS member_count
        FROM communities
        LEFT JOIN community_members ON communities.community_id = community_members.community_id
        WHERE communities.status = 1
        GROUP BY communities.community_id";

$result = $conn->query($sql);

$domains = "SELECT interest_id, interest_name FROM interest";
$resultDomain = $conn->query($domains);

$eventsQuery = "
    SELECT event_id, event_name, event_description, event_time
    FROM events
    WHERE event_time >= NOW()
    ORDER BY event_time ASC
    LIMIT 6
";

$eventResult = $conn->query($eventsQuery);
if (!$eventResult) {
    echo "Error fetching events: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pune By Pune</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="./CSS/home.css">
  <style>
  .hero-section {
    text-align: center;
    padding: 80px 20px;
    font-family: "Oswald", serif;
  }

  .category-slider,
  #eventSlider,
  #featureSlider {
    display: flex;
    overflow-x: auto;
    white-space: nowrap;
    scroll-behavior: smooth;
    gap: 15px;
    padding: 10px;
  }

  .category-slider::-webkit-scrollbar,
  #eventSlider::-webkit-scrollbar,
  #featureSlider::-webkit-scrollbar {
    display: none;
  }

  .category-box,
  .feature-card {
    flex: 0 0 auto;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
  }

  .category-box:hover,
  .feature-card:hover {
    transform: scale(1.05);
  }

  .btn-green {
    background-color: #5cb85c;
    color: white;
    border: none;
  }

  .btn-green:hover {
    background-color: #4cae4c;
  }

  body {
    background: url('uploads/wall2.jpg');
    /* Add a textured pattern */
    background-size: cover;
  }

  .card {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    height: 250px;
    background-color: #fff;
  }

  .card:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
  }

  .card-content {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;
    transition: opacity 0.3s ease-in-out;
  }

  .card:hover .card-content {
    opacity: 0;
  }

  .card-slide {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 165, 0, 0.8);
    /* Transparent orange */
    color: #fff;
    padding: 20px;
    transition: top 0.3s ease-in-out;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
  }

  .card:hover .card-slide {
    top: 0;
  }
  </style>
</head>

<body>

  <section class="hero-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 text-center" style="padding: 120px;">
          <h1>पुणे By Pune</h1>
          <p>Welcome to Pune by Pune—your go-to community platform designed to bring Puneites together through shared
            passions and interests. Whether you're looking to connect over technology, business, hobbies, fitness, or
            social events, we make it easy to find your tribe and build meaningful relationships right here in the heart
            of Pune.</p>
          <a href="home.php"><button class="btn btn-green">Get Started</button></a>
        </div>
        <div class="col-md-6">
          <img src="uploads/community.png" alt="Illustration" class="img-fluid">
        </div>
      </div>
    </div>





    <div class="container">
      <div class="row justify-content-center" style="margin-top: 100px;">
        <div class="col-md-5">
          <div class="card text-center">
            <div class="card-content">
              <h2>Our Mission</h2>
            </div>
            <div class="card-slide">
              <p>To create a vibrant, inclusive space where people can connect, collaborate, and grow. From local
                meetups and workshops to social gatherings and fitness sessions, Pune by Pune bridges the gap between
                online connections and real-world experiences.</p>
            </div>
          </div>
        </div>
        <div class="col-md-5">
          <div class="card text-center">
            <div class="card-content">
              <h2>Our Vision</h2>
            </div>
            <div class="card-slide">
              <p>We believe that building a strong community starts with making it easy for everyone to participate. Our
                user-friendly platform empowers you to create, discover, and join groups that match your interests—no
                tech skills required.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script>
    function openPopup(id) {
      document.getElementById('overlay').classList.add('active');
      document.getElementById(id).classList.add('active');
    }

    function closePopup() {
      document.getElementById('overlay').classList.remove('active');
      document.getElementById('missionPopup').classList.remove('active');
      document.getElementById('visionPopup').classList.remove('active');
    }
    </script>

  </section>

  <!-- Categories -->
  <section class="container my-4">
    <h2 class="text-center">Explore Categories</h2>
    <div id="featureSlider" class="feature-slider">
      <?php
        if ($resultDomain->num_rows > 0) {
            while ($row = $resultDomain->fetch_assoc()) {
                echo '<div class="category-box">';
                echo '<h5>' . htmlspecialchars($row['interest_name']) . '</h5>';
                echo '</div>';
            }
        } else {
            echo '<p>No categories available.</p>';
        }
        ?>
    </div>
  </section>

  <?php include('footer.php'); ?>

  <script>
  function autoScroll(slider, speed = 2) {
    let maxScroll = slider.scrollWidth - slider.clientWidth;
    let scrollAmount = 0;

    function scroll() {
      if (scrollAmount >= maxScroll) {
        slider.scrollTo({
          left: 0,
          behavior: "smooth"
        });
        scrollAmount = 0;
      } else {
        slider.scrollBy({
          left: speed,
          behavior: "smooth"
        });
        scrollAmount += speed;
      }
    }

    let interval = setInterval(scroll, 50);

    slider.addEventListener("mouseenter", () => clearInterval(interval));
    slider.addEventListener("mouseleave", () => interval = setInterval(scroll, 50));
  }

  document.addEventListener("DOMContentLoaded", function() {
    const eventSlider = document.getElementById("eventSlider");
    const featureSlider = document.getElementById("featureSlider");

    if (eventSlider.scrollWidth > eventSlider.clientWidth) autoScroll(eventSlider, 3);
    if (featureSlider.scrollWidth > featureSlider.clientWidth) autoScroll(featureSlider, 3);
  });

  function getRandomColor() {
    const letters = "89ABCDEF";
    let color = "#";
    for (let i = 0; i < 6; i++) {
      color += letters[Math.floor(Math.random() * letters.length)];
    }
    return color;
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".category-box").forEach(box => {
      box.style.backgroundColor = getRandomColor();
    });
  });

  function autoScrollFeatureSlider() {
    const slider = document.getElementById("featureSlider");

    if (slider.scrollWidth <= slider.clientWidth) return; // Prevent scrolling if not needed

    let scrollAmount = 0;
    const speed = 2; // Adjust speed
    const maxScroll = slider.scrollWidth - slider.clientWidth;

    function scroll() {
      if (scrollAmount >= maxScroll) {
        slider.scrollTo({
          left: 0,
          behavior: "smooth"
        });
        scrollAmount = 0;
      } else {
        slider.scrollBy({
          left: speed,
          behavior: "smooth"
        });
        scrollAmount += speed;
      }
    }

    let interval = setInterval(scroll, 50);

    // Pause on hover
    slider.addEventListener("mouseenter", () => clearInterval(interval));
    slider.addEventListener("mouseleave", () => interval = setInterval(scroll, 50));
  }

  document.addEventListener("DOMContentLoaded", autoScrollFeatureSlider);
  </script>

</body>

</html>