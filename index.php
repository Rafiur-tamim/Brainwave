<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BrainWave - Admission Prep</title>
  <link rel="icon" href="img/logo.png" type="image/png" >
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
  <style>
    html { scroll-behavior: smooth; }

    /* ================== Hero Section with Video Slider ================== */
    .hero {
      height: 100vh;
      position: relative;
      overflow: hidden;
      padding-top: 80px; /* for fixed navbar */
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero video {
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 0;
    }

    .hero .overlay {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.55);
      z-index: 1;
    }

    .hero .carousel-caption {
      position: relative;
      z-index: 2;
      bottom: auto;
      top: 50%;
      transform: translateY(-50%);
      text-align: center;
      color: white;
      max-width: 800px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .hero .carousel-caption h1 {
      font-size: 3rem;
      font-weight: bold;
      text-shadow: 2px 2px 10px rgba(0,0,0,0.7);
      animation: fadeInDown 1s ease forwards;
    }

    .hero .carousel-caption p {
      font-size: 1.25rem;
      margin: 15px 0;
      text-shadow: 1px 1px 6px rgba(0,0,0,0.6);
      animation: fadeInUp 1s ease forwards;
      animation-delay: 0.5s;
    }

    .hero .carousel-caption .btn {
      margin-top: 15px;
      font-size: 1.2rem;
      padding: 12px 30px;
      animation: fadeInUp 1s ease forwards;
      animation-delay: 1s;
    }

    @keyframes fadeInDown {
      0% { opacity: 0; transform: translateY(-50px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(50px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    /* ================== Navbar ================== */
    .navbar { transition: background 0.3s ease; }
    .navbar .nav-link { color: #fff !important; margin-left: 15px; transition: 0.3s; }
    .navbar .nav-link:hover { color: #ffd700 !important; }
    @media (max-width: 991px) {
      .navbar-collapse { background: rgba(0, 0, 0, 0.9); padding: 1rem; border-radius: 10px; margin-top: 10px; }
      .navbar-nav .nav-link { color: #fff !important; font-size: 1.1rem; padding: 10px; }
      .navbar-nav .nav-link:hover { color: #0dcaf0 !important; }
    }

    /* ================== Flip Cards & Circle Stats ================== */
    .feature-card { transition: transform 0.4s ease, box-shadow 0.4s ease; border-radius: 15px; overflow: hidden; padding: 20px; background: #fff; text-align: center; }
    .feature-card:hover { transform: scale(1.08) rotate(1deg); box-shadow: 0 20px 40px rgba(7, 150, 126, 0.25); }

    .flip-card { background-color: transparent; width: 100%; height: 250px; perspective: 1000px; margin: auto; }
    .flip-card-inner { position: relative; width: 100%; height: 100%; transition: transform 0.8s; transform-style: preserve-3d; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); border: 2px solid #105c6dff; }
    .flip-card:hover .flip-card-inner { transform: rotateY(180deg); }
    .flip-card-front, .flip-card-back { position: absolute; width: 100%; height: 100%; border-radius: 15px; display: flex; flex-direction: column; justify-content: center; align-items: center; -webkit-backface-visibility: hidden; backface-visibility: hidden; color: white; padding: 20px; }
    .flip-card-front { background: white; font-weight: bold; color: #105c6dff; }
    .flip-card-back { background: linear-gradient(135deg, #105c6dff, #208684ff); transform: rotateY(180deg); text-align: center; }

    .circle-card { width: 150px; height: 150px; border-radius: 50%; display: flex; flex-direction: column; justify-content: center; align-items: center; margin: auto; color: #333; font-weight: bold; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: transform 0.5s ease, box-shadow 0.5s ease; cursor: pointer; }
    .circle-card:hover { transform: scale(1.15) rotate(5deg); box-shadow: 0 15px 35px rgba(0,0,0,0.2); }
    .circle-card .number { font-size: 2rem; }

    /* ================== Quiz Box ================== */
    .quiz-box { padding: 20px; border-radius: 15px; background: #f8f9fa; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .badge-reward { display: none; font-size: 2rem; margin-top: 15px; animation: pop 0.8s ease forwards; }
    @keyframes pop { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }

    /* ================== Testimonial Card ================== */
    .card1 { display: block; position: relative; max-width: 262px; background-color: #f2f8f9; border-radius: 4px; padding: 32px 24px; margin: 12px; text-decoration: none; z-index: 0; overflow: hidden; text-align: center; }
    .card1 img { object-fit: cover; }
    .card1:before { content: ""; position: absolute; z-index: -1; top: -16px; right: -16px; background: #00838d; height: 32px; width: 32px; border-radius: 32px; transform: scale(1); transform-origin: 50% 50%; transition: transform 0.25s ease-out; }
    .card1:hover:before { transform: scale(21); }
    .card1:hover h3 { color: #ffffff; }
    .card1:hover p { color: rgba(255,255,255,0.8); }
    .go-corner { display: flex; align-items: center; justify-content: center; position: absolute; width: 32px; height: 32px; overflow: hidden; top: 0; right: 0; background-color: #00838d; border-radius: 0 4px 0 32px; }
    .go-arrow { margin-top: -4px; margin-right: -4px; color: white; font-family: courier, sans; }

  </style>
</head>
<body>

<!-- ================== Navbar ================== -->
<nav class="navbar navbar-expand-lg navbar-dark bg-transparent fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="index.php">
      <img src="img/logo.png" width="40" class="me-2"> BrainWave
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse text-center" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
        <li class="nav-item"><a class="nav-link" href="#stats">Why Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#quizModal">Try Quiz</a></li>
        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
        <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link text-warning fw-semibold" href="dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link text-danger fw-semibold" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-success fw-semibold" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link text-info fw-semibold" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- ================== Hero Video Carousel ================== -->
<section class="hero">
  <div id="videoCarousel" class="carousel slide carousel-fade w-100 h-100 position-absolute top-0 start-0" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#videoCarousel" data-bs-slide-to="0" class="active"></button>
      <button type="button" data-bs-target="#videoCarousel" data-bs-slide-to="1"></button>
      <button type="button" data-bs-target="#videoCarousel" data-bs-slide-to="2"></button>
    </div>

    <div class="carousel-inner h-100">
      <div class="carousel-item active h-100">
        <video class="w-100 h-100 object-fit-cover" autoplay loop muted playsinline>
          <source src="videos/slide1.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <div class="carousel-caption">
          <h1>🚀 Prepare Smarter. Succeed Faster.</h1>
          <p>Access study materials, live classes & mock tests</p>
          <a href="register.php" class="btn btn-primary btn-lg">Get Started</a>
        </div>
      </div>
      <div class="carousel-item h-100">
        <video class="w-100 h-100 object-fit-cover" autoplay loop muted playsinline>
          <source src="videos/slide2.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <div class="carousel-caption">
          <h1>📚 Learn from Expert Tutors</h1>
          <p>Join live sessions & improve your skills</p>
          <a href="register.php" class="btn btn-success btn-lg">Join Now</a>
        </div>
      </div>
      <div class="carousel-item h-100">
        <video class="w-100 h-100 object-fit-cover" autoplay loop muted playsinline>
          <source src="videos/slide3.mp4" type="video/mp4">
        </video>
        <div class="overlay"></div>
        <div class="carousel-caption">
          <h1>📈 Track Your Progress</h1>
          <p>Analyze results & identify improvement areas</p>
          <a href="register.php" class="btn btn-warning btn-lg">Start Learning</a>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#videoCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#videoCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<!-- ================== Features Section ================== -->
<section id="features" class="py-5">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Features</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-4">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front"><i class="fas fa-book fa-3x"></i><h4>Study Materials</h4></div>
            <div class="flip-card-back"><p>Access unit-wise resources, notes, and guides to prepare effectively.</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front"><i class="fas fa-pencil-alt fa-3x"></i><h4>Mock Tests</h4></div>
            <div class="flip-card-back"><p>Test your knowledge with real exam-style questions and instant results.</p></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="flip-card">
          <div class="flip-card-inner">
            <div class="flip-card-front"><i class="fas fa-chart-line fa-3x"></i><h4>Analytics</h4></div>
            <div class="flip-card-back"><p>Track your progress and identify areas that need improvement.</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ================== Why Choose Us ================== -->
<section id="stats" class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="fw-bold mb-5">Why BrainWave?</h2>
    <div class="row g-4 justify-content-center">
      <div class="col-md-3"><div class="circle-card border border-2 border-primary"><h3 class="number">500+</h3><p>Students Enrolled</p></div></div>
      <div class="col-md-3"><div class="circle-card border border-2 border-success"><h3 class="number">10+</h3><p>Expert Tutors</p></div></div>
      <div class="col-md-3"><div class="circle-card border border-2 border-warning"><h3 class="number">200+</h3><p>Study Materials</p></div></div>
      <div class="col-md-3"><div class="circle-card border border-2 border-danger"><h3 class="number">98%</h3><p>Success Rate</p></div></div>
    </div>
  </div>
</section>

<!-- ================== Interactive Quiz Modal ================== -->
<div class="modal fade" id="quizModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white rounded-top-4">
        <h5 class="modal-title fw-bold"><i class="fas fa-brain me-2"></i> BrainWave Quiz</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="quiz-box p-4">
          <!-- Timer Progress -->
          <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span>⏱ Time Left:</span>
              <span id="timerText">60s</span>
            </div>
            <div class="progress rounded-pill" style="height: 12px;">
              <div id="timerBar" class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
            </div>
          </div>

          <!-- Quiz Content -->
          <div id="quizContent"></div>

          <!-- Result -->
          <div id="quizResult" class="mt-4 text-center fs-5 fw-bold text-success" style="display:none;">
            <i class="fas fa-check-circle me-2"></i>Score:
          </div>

          <!-- CTA for enrollment -->
          <div id="enrollCTA" class="text-center mt-3" style="display:none;">
            <p class="fs-6 text-danger"><i class="fas fa-exclamation-circle me-1"></i>Time's up! Improve your preparation with BrainWave full system.</p>
            <a href="register.php" class="btn btn-lg btn-primary rounded-pill"><i class="fas fa-graduation-cap me-2"></i>Enroll Now</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Quiz questions
const quizQuestions = [
  {question: "Q1: What is 5 + 3?", options:["6","8","10"], answer:"8", icon:"fas fa-plus"},
  {question: "Q2: Which is the capital of Bangladesh?", options:["Chittagong","Dhaka","Sylhet"], answer:"Dhaka", icon:"fas fa-city"},
  {question: "Q3: Which gas do plants release?", options:["Oxygen","Carbon Dioxide","Nitrogen"], answer:"Oxygen", icon:"fas fa-leaf"}
];

let currentQuestion = 0;
let score = 0;
let totalTime = 60;
let timerInterval;

// Start quiz
function startQuiz() {
  currentQuestion = 0;
  score = 0;
  document.getElementById("quizResult").style.display = "none";
  document.getElementById("enrollCTA").style.display = "none";
  document.getElementById("quizContent").style.display = "block";
  totalTime = 60;
  loadQuestion();
  startTimer();
}

// Timer
function startTimer() {
  updateTimerBar();
  timerInterval = setInterval(() => {
    totalTime--;
    document.getElementById("timerText").innerText = totalTime + "s";
    updateTimerBar();
    if(totalTime <= 0) finishQuiz();
  }, 1000);
}

function updateTimerBar() {
  const percent = (totalTime/60)*100;
  document.getElementById("timerBar").style.width = percent + "%";
}

// Load question
function loadQuestion() {
  if(currentQuestion >= quizQuestions.length) {
    finishQuiz();
    return;
  }
  const q = quizQuestions[currentQuestion];
  let html = `<div class="card p-4 mb-3 shadow-sm rounded-4 text-center">
                <h5><i class="${q.icon} me-2"></i>${q.question}</h5>`;
  q.options.forEach(opt => {
    html += `<button class="btn btn-outline-primary w-100 my-2 rounded-pill fw-semibold" onclick="checkAnswer('${opt}')"><i class="fas fa-question-circle me-2"></i>${opt}</button>`;
  });
  html += `</div>`;
  document.getElementById("quizContent").innerHTML = html;
}

// Check answer
function checkAnswer(selected) {
  const q = quizQuestions[currentQuestion];
  if(selected === q.answer) {
    score++;
    currentQuestion++;
    loadQuestion();
  } else {
    alert("❌ Wrong answer! Try again.");
  }
}

// Finish quiz
function finishQuiz() {
  clearInterval(timerInterval);
  document.getElementById("quizContent").style.display = "none";
  document.getElementById("quizResult").style.display = "block";
  document.getElementById("quizResult").innerHTML = `<i class="fas fa-check-circle me-2"></i>You scored ${score} out of ${quizQuestions.length}.`;

  if(score < quizQuestions.length) {
    document.getElementById("enrollCTA").style.display = "block";
  }
}

// Start quiz when modal opens
var quizModal = document.getElementById('quizModal');
quizModal.addEventListener('shown.bs.modal', function () {
  startQuiz();
});
</script>

<!-- ================== What Our Students Say ================== -->
<section id="testimonials" class="py-5" data-aos="fade-up">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">What Our Students Say</h2>
    <div id="testimonialSlider" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <!-- Slide 1 -->
        <div class="carousel-item active">
          <div class="d-flex justify-content-center flex-wrap">
            <a class="card1" href="#">
              <img src="img/student1.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Student 1">
              <h3>Ayesha Rahman</h3>
              <p class="small">Unit A - Science<br>"BrainWave helped me ace my admission test with confidence!"</p>
              <div class="go-corner">
                <div class="go-arrow">→</div>
              </div>
            </a>
            <a class="card1" href="#">
              <img src="img/student2.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Student 2">
              <h3>Rahim Uddin</h3>
              <p class="small">Unit B - Humanities<br>"The live classes made difficult topics so much easier."</p>
              <div class="go-corner">
                <div class="go-arrow">→</div>
              </div>
            </a>
            <a class="card1" href="#">
              <img src="img/student3.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Student 3">
              <h3>Nusrat Jahan</h3>
              <p class="small">Unit C - Business<br>"I love the mock tests and progress tracking features!"</p>
              <div class="go-corner">
                <div class="go-arrow">→</div>
              </div>
            </a>
          </div>
        </div>
        <!-- Slide 2 (duplicate for more students if needed) -->
        <div class="carousel-item">
          <div class="d-flex justify-content-center flex-wrap">
            <a class="card1" href="#">
              <img src="img/student4.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Student 4">
              <h3>Karim Hasan</h3>
              <p class="small">Unit A - Science<br>"The analytics helped me improve my weak areas!"</p>
              <div class="go-corner">
                <div class="go-arrow">→</div>
              </div>
            </a>
            <a class="card1" href="#">
              <img src="img/student5.jpg" class="rounded-circle mb-3" width="80" height="80" alt="Student 5">
              <h3>Fatima Akter</h3>
              <p class="small">Unit B - Humanities<br>"I could prepare easily with study materials."</p>
              <div class="go-corner">
                <div class="go-arrow">→</div>
              </div>
            </a>
          </div>
        </div>
      </div>

      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>

<!-- ================== Plans ================== -->
<section id="plans" class="py-5 bg-light" data-aos="zoom-in">
  <div class="container text-center">
    <h2 class="fw-bold mb-4">Choose Your Plan</h2>
    <p class="text-muted mb-5">Select the unit you want to prepare for and start learning today.</p>
    <div class="row g-4">
      <div class="col-md-4"><div class="card feature-card p-4 h-100 border border-2 border-primary shadow"><h4 class="fw-bold text-primary">Unit A</h4><p class="mt-3">Science subjects</p><ul class="list-unstyled"><li>📘 Study Materials</li><li>📝 Mock Tests</li><li>🎥 Live Classes</li></ul><h3 class="fw-bold my-3">৳499</h3><a href="register.php" class="btn btn-primary w-100">Get Started</a></div></div>
      <div class="col-md-4"><div class="card feature-card p-4 h-100 border border-2 border-success shadow"><h4 class="fw-bold text-success">Unit B</h4><p class="mt-3">Humanities subjects</p><ul class="list-unstyled"><li>📘 Study Materials</li><li>📝 Mock Tests</li><li>🎥 Live Classes</li></ul><h3 class="fw-bold my-3">৳399</h3><a href="register.php" class="btn btn-success w-100">Get Started</a></div></div>
      <div class="col-md-4"><div class="card feature-card p-4 h-100 border border-2 border-danger shadow"><h4 class="fw-bold text-danger">Unit C</h4><p class="mt-3">Business Studies subjects</p><ul class="list-unstyled"><li>📘 Study Materials</li><li>📝 Mock Tests</li><li>🎥 Live Classes</li></ul><h3 class="fw-bold my-3">৳449</h3><a href="register.php" class="btn btn-danger w-100">Get Started</a></div></div>
    </div>
  </div>
</section>

<!-- ================== Newsletter ================== -->
<section id="contact" class="py-5 bg-dark text-white text-center" data-aos="fade-up">
  <div class="container">
    <h2 class="fw-bold mb-3">Stay Updated</h2>
    <p>Subscribe to get the latest updates on study materials, tests, and live sessions.</p>
    <form class="d-flex justify-content-center">
      <input type="email" class="form-control w-50 me-2" placeholder="Enter your email">
      <button class="btn btn-primary">Subscribe</button>
    </form>
  </div>
</section>

<!-- ================== Footer ================== -->
<footer class="bg-dark text-white text-center py-3">
  <p>&copy; 2025 BrainWave. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init();

  // Video Carousel autoplay
  const videoCarousel = new bootstrap.Carousel('#videoCarousel', {
    interval: 6000,
    ride: 'carousel'
  });

  // // Quiz check
  // function checkQuiz() {
  //   let score = 0;
  //   const form = document.forms['quizForm'];
  //   if(form.q1.value === "8") score++;
  //   if(form.q2.value === "Dhaka") score++;
  //   let result = document.getElementById("result");
  //   let badge = document.getElementById("rewardBadge");
  //   if(score === 2) {
  //     result.innerHTML = "✅ Perfect! You scored 2/2.";
  //     badge.style.display = "block";
  //   } else {
  //     result.innerHTML = "👍 You scored " + score + "/2. Try again!";
  //     badge.style.display = "none";
  //   }
  // }
</script>
</body>
</html>
