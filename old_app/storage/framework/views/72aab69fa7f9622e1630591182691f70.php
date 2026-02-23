<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>404 | Page Not Found — RenownCRM</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      color: #333;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      overflow: hidden;
    }
    .error-container {
      text-align: center;
      position: relative;
      z-index: 1;
    }
    .error-container h1 {
      font-size: 8rem;
      font-weight: bold;
      color: #007bff; /* your brand blue */
      margin-bottom: 1rem;
      position: relative;
    }
    .error-container h1 span {
      display: inline-block;
      animation: float 3s infinite ease-in-out;
    }
    .error-container h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }
    .btn-home {
      font-size: 1.1rem;
      padding: 0.75rem 1.75rem;
    }

    /* Floating animation for the “404” digits */
    @keyframes float {
      0%   { transform: translateY(0px); }
      50%  { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }

    /* Background shapes (optional) */
    .bg-shapes {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      z-index: 0;
    }
    .bg-shape {
      position: absolute;
      border-radius: 50%;
      background: rgba(0, 123, 255, 0.15);
      animation: move-shape 20s infinite alternate;
    }
    .bg-shape.one {
      width: 300px;
      height: 300px;
      top: 10%;
      left: 5%;
    }
    .bg-shape.two {
      width: 400px;
      height: 400px;
      bottom: 10%;
      right: 7%;
    }
    .bg-shape.three {
      width: 200px;
      height: 200px;
      top: 30%;
      right: 35%;
    }

    @keyframes move-shape {
      0%   { transform: translate(0,0) scale(1); }
      50%  { transform: translate(30px, -20px) scale(1.1); }
      100% { transform: translate(-20px, 30px) scale(1); }
    }

  </style>
</head>
<body>

  <div class="bg-shapes">
    <div class="bg-shape one"></div>
    <div class="bg-shape two"></div>
    <div class="bg-shape three"></div>
  </div>

  <div class="error-container">
    <h1>
      <span>4</span><span>0</span><span>4</span>
    </h1>
    <h2>Oops! Page Not Found</h2>
    <p class="mb-4">The page you’re looking for doesn’t exist or has been moved.</p>
    <a onclick="window.history.back()" class="btn btn-primary btn-sm">Go Back</a>
  </div>

  <!-- Bootstrap JS (if needed) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/renown/public_html/dev.renownsystem.com/resources/views/404.blade.php ENDPATH**/ ?>