<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login Pengguna</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.8)),
                  url('https://png.pngtree.com/background/20230527/original/pngtree-an-old-bookcase-in-a-library-picture-image_2760144.jpg') no-repeat center center/cover;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .logo-wrapper {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      gap: 10px;
      animation: fadeIn 1s ease;
    }

    .logo-wrapper img {
      width: 60px;
    }

    .logo-wrapper span {
      font-size: 40px;
      font-weight: 800;
      color: #ffa650;
      text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
    }

    .container {
      width: 100%;
      max-width: 360px;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      text-align: center;
      animation: fadeIn 1.5s ease;
    }

    .container h2 {
      font-size: 24px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 14px 18px;
      margin: 12px 0;
      border-radius: 30px;
      border: none;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 14px;
      transition: background 0.3s ease;
    }

    input:focus {
      background: rgba(255, 255, 255, 0.2);
      outline: none;
    }

    input::placeholder {
      color: #ddd;
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      border-radius: 30px;
      background: #ff7b00;
      color: white;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #e66a00;
      transform: scale(1.03);
      box-shadow: 0 0 10px rgba(255, 123, 0, 0.5);
    }

    .container p {
      margin-top: 15px;
      font-size: 14px;
    }

    .container a {
      color: #4aa3ff;
      text-decoration: none;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-10px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <!-- Logo Book.U -->
  <div class="logo-wrapper">
    <img src="https://img.icons8.com/ios-filled/50/FFA650/books.png" alt="logo" />
    <span>Book.U</span>
  </div>

  <!-- Form Login -->
  <div class="container">
    <h2>LOGIN</h2>
    <form action="login_process.php" method="post">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Belum memiliki akun? <a href="register.php">Daftar disini</a></p>
  </div>

</body>
</html>