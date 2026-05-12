<!DOCTYPE html>
<html lang="en">
    <head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>3EDTAK - ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  
  <style>
    body { 
      background-color: #f5f7fa; 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar {
      background-color: #ffffff !important;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .hero {
      background: linear-gradient(135deg, #0d6efd 0%, #004085 100%);
      color: white;
      padding: 60px 0;
      border-bottom-left-radius: 50px;
      border-bottom-right-radius: 50px;
    }

    .feature-card {
      background: white;
      border: 1px solid #dee2e6;
      border-radius: 15px;
      padding: 25px;
      height: 100%;
      transition: 0.3s;
    }
    .feature-card:hover {
      border-color: #0d6efd;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .icon-circle {
      width: 60px;
      height: 60px;
      background-color: #e7f1ff;
      color: #0d6efd;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 15px;
    }

    .btn-main {
      border-radius: 8px;
      padding: 10px 25px;
      font-weight: 500;
    }

    footer {
      background-color: #343a40;
      color: #adb5bd;
      padding: 30px 0;
      margin-top: 50px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">🛠️ 3EDTAK - ToolShare</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
        <li class="nav-item ms-lg-3">
          <a href="{{ route('register') }}" class="btn btn-primary btn-sm btn-main text-white">Join Now</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<header class="hero text-center text-md-start">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-7">
        <h1 class="display-5 fw-bold mb-3">Borrow Specialized Tools From Your Neighbors</h1>
        <p class="lead mb-4">Don't spend thousands on tools you'll use once. Our community library lets you rent 3D printers, drills, and more safely and affordably.</p>
        <div class="d-flex gap-3 justify-content-center justify-content-md-start">
          <a href="{{ route('login') }}" class="btn btn-warning btn-main fw-bold">Browse Tools</a>
          <a href="{{ route('register') }}" class="btn btn-outline-light btn-main">List Your Tool</a>
        </div>
      </div>
      <div class="col-md-5 d-none d-md-block">
        <div class="bg-white p-4 rounded-4 shadow text-dark">
          <h6 class="fw-bold border-bottom pb-2">Recently Added</h6>
          <div class="d-flex justify-content-between py-2">
            <span>🔹 Bosch Laser Level</span>
            <span class="text-success fw-bold">30 EGP</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <span>🔹 Digital Oscilloscope</span>
            <span class="text-success fw-bold">50 EGP</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <span>🔹 Industrial Sewing Machine</span>
            <span class="text-success fw-bold">40 EGP</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<section class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold">Why use 3EDTAK - ToolShare?</h2>
    <p class="text-muted">A secure platform for community resource sharing</p>
  </div>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="feature-card">
        <div class="icon-circle">👤</div>
        <h5 class="fw-bold">Identity Verification</h5>
        <p class="text-muted small">We use KYC (Know Your Customer) to verify all members before they can borrow high-value tools.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <div class="icon-circle">⭐</div>
        <h5 class="fw-bold">Trust Score</h5>
        <p class="text-muted small">Every user has a rating based on their rental history, ensuring the safety of your equipment.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="feature-card">
        <div class="icon-circle">🛡️</div>
        <h5 class="fw-bold">Secure Deposits</h5>
        <p class="text-muted small">We hold a refundable deposit in escrow to protect lenders against any accidental damage.</p>
      </div>
    </div>
  </div>
</section>

<div class="container py-5">
  <div class="p-5 bg-light rounded-4 border">
    <div class="row align-items-center text-center text-md-start">
      <div class="col-md-8">
        <h3 class="fw-bold">System Management</h3>
        <p class="mb-0">Access different modules for Members, Librarians, and Maintenance staff.</p>
      </div>
      <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <a href="{{ route('login') }}" class="btn btn-dark px-4 py-2">Portal Login</a>
      </div>
    </div>
  </div>
</div>

<footer>
  <div class="container text-center">
    <p class="mb-2">3EDTAK - ToolShare</p>
    <div class="small opacity-75">
      <p class="mb-1"></p>
      <p>Group Project - Spring 2026</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
