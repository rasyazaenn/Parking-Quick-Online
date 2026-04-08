<!DOCTYPE html>
<html>
<head>
    <title>Login Parkir</title>

    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #dfe9f3, #ffffff);
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        .login-container {
            display: flex;
            background: white;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            align-items: center;
            gap: 40px;
        }

        .logo img {
            width: 220px;
        }

        h2 {
            margin-top: 0;
            font-size: 28px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input {
            width: 280px;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        input:focus {
            border-color: black;
            outline: none;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: black;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #333;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                padding: 30px;
            }

            .logo img {
                width: 180px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">

        <!-- LOGO -->
        <div class="logo">
            <img src="logo.png">
        </div>

        <!-- FORM -->
        <div>
            <center><h2>Login Parqo</h2></center>
            <form method="POST" action="index.php?url=auth/login">
                
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>

                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>

                <button type="submit">Login</button>
            </form>
        </div>

    </div>

</body>
</html>