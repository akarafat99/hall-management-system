<?php
include_once 'class-file/SessionManager.php';
$session = SessionStatic::class;

// show popup if any
include_once 'popup-1.php';
$session::get('msg1') ? showPopup($session::get('msg1')) : '';
$session::delete('msg1');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MM Hall</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: sans-serif;
    }

    main {
      overflow-x: hidden;
    }

    section {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-attachment: fixed;
      background-size: cover;
      background-position: center;
    }

    /* Section 1: Hero with centered text and button */
    #hero {
      background-image: url('images/hero-bannar.jpg');
    }

    /* Darker overlay */
    #hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    #hero .hero-content {
      position: relative;
      z-index: 2;
      text-align: center;
      color: #fff;
      padding: 0 2rem;
      max-width: 800px;
    }

    #hero .hero-heading {
      font-size: 10rem;
      font-weight: 900;
      margin-bottom: 1rem;
      opacity: 0;
      animation: fadeInDown 1s ease-out forwards;
    }

    #hero .hero-text {
      font-size: 1.5rem;
      margin-bottom: 2rem;
      opacity: 0;
      animation: fadeInDown 1s ease-out 0.5s forwards;
    }

    #hero .hero-subtext {
      font-size: 2rem;
      margin-bottom: 2rem;
      opacity: 0;
      animation: fadeInDown 1s ease-out 1s forwards;
    }

    #dynamic-word {
      font-weight: 700;
      display: inline-block;
      opacity: 0;
      transition: opacity 0.5s;
    }

    #hero .btn-simple {
      background: #007bff;
      color: #fff;
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 2rem;
      transition: transform .3s, opacity .5s;
      opacity: 0;
      animation: fadeInDown 1s ease-out 1.5s forwards;
    }

    #hero .btn-simple:hover {
      transform: translateY(-3px);
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Section 2: Notices */
    #notices {
      background-color: #fff;
    }

    #notices .text-col {
      padding: 4rem;
    }

    #notices .text-col h2 {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    #notices .text-col p {
      font-size: 1.25rem;
      margin-bottom: 2rem;
    }

    #notices .btn-simple {
      background: #007bff;
      color: #fff;
      padding: 0.75rem 2rem;
      border: none;
      border-radius: 2rem;
      transition: transform .3s;
    }

    #notices .btn-simple:hover {
      transform: translateY(-3px);
    }

    #notices .img-col {
      min-height: 400px;
      flex: 1;
      background-image: url('images/notice-hero.jpg');
      background-size: cover;
      background-position: center;
    }
  </style>
</head>

<body>
  <?php
  if ($session::get('user') != null) include_once 'student/navbar-student-1.php';
  else include_once 'student/navbar-student-2.php';
  ?>
  <main>
    <!-- Section 1: Hero -->
    <section id="hero">
      <div class="hero-content">
        <h1>
          Munshi Mohammad Meherulla Hall
          <small class="text-light fs-5">JUST</small>
        </h1>
        <br>
        <p class="hero-text">
          <i>
            Welcome to MM Hall—your home away from home. This residence hall is affiliated with Jashore University of Science and Technology.
          </i>
        </p>
        <br>
        <p class="hero-subtext"> <span id="dynamic-word">_</span></p>
      </div>
    </section>

    <!-- Section 2: Notices -->
    <section id="notices">
      <div class="container-fluid d-flex p-0">
        <div class="text-col col-md-6">
          <h2>Notices</h2>
          <p>Stay updated with the latest hall announcements, deadlines, and event notifications.</p>
          <a class="btn-simple decoration-none" href="notice.php">View Notices</a>
        </div>
        <div class="img-col col-md-6"></div>
      </div>
    </section>
  </main>

  <footer class="bg-dark text-white text-center py-4">
    &copy; 2025 MM Hall. All rights reserved.
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Dynamic word script -->
  <script>
    const words = [
      "Digitally integrated",
      "Administratively efficient",
      "Academically oriented",
      "Operationally streamlined",
      "User-centric",
      "Time-optimized",
      "Process-driven",
      "Virtually accessible",
      "Compliance-assured",
      "Resource-adaptive"
    ];
    let idx = 0;
    const dynamicEl = document.getElementById('dynamic-word');

    function showNextWord() {
      dynamicEl.style.opacity = 0;
      setTimeout(() => {
        dynamicEl.textContent = words[idx];
        dynamicEl.style.opacity = 1;
        idx = (idx + 1) % words.length;
      }, 500);
    }
    // cycle every 2.5s, start after subtext animation
    setTimeout(() => {
      showNextWord();
      setInterval(showNextWord, 2500);
    }, 1000);
  </script>
</body>

</html>