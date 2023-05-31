<?php
require_once 'Auth.php';

$generatedToken = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    if (empty($username)) {
        $errorMessage = "Please enter a valid username.";
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
        $token = base64_encode($ip . $username);
        $accountsFile = 'accounts.json';
        $accounts = [];

        if (file_exists($accountsFile)) {
            $accountsJson = file_get_contents($accountsFile);
            $accounts = json_decode($accountsJson, true);
        }

        $ipExists = false;
        $userExists = false;
        foreach ($accounts as $account) {
            if ($account['IP'] === $ip) {
                $ipExists = true;
                if ($account['Username'] === $username) {
                    $userExists = true;
                    $generatedToken = $account['Token'];
                    signInUser($username);
                }
                break;
            }
        }

        if (!$ipExists) {
            $newAccount = ['IP' => $ip, 'Username' => $username, 'Token' => $token];
            $accounts[] = $newAccount;
            file_put_contents($accountsFile, json_encode($accounts));
            $successMessage = "Account created successfully.";
            $generatedToken = $token;
        } elseif ($userExists) {
            $successMessage = "Signed in as " . $username . ".";
        } else {
            $errorMessage = "An account already exists for this IP address.";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<div class="top-bar">
<a href="Vote.php">Vote</a>
<a href="Describe.php">Describe</a>
</div>
<style>
.top-bar {
  background-color: #333;
  overflow: hidden;
}

.top-bar a {
  float: left;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

.top-bar a:hover {
  background-color: #ddd;
  color: black;
}

.top-bar a.active {
  background-color: #4CAF50;
  color: white;
}
</style>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        form {
            display: inline-block;
            margin-top: 1em;
        }
    </style>
    <script>
        function setAuthToken(token) {
            // Set token in local storage
            localStorage.setItem('auth_token', token);

            // Set token as a cookie
            const expiresInDays = 30;
            const date = new Date();
            date.setTime(date.getTime() + expiresInDays * 24 * 60 * 60 * 1000);
            const expires = 'expires=' + date.toUTCString();
            document.cookie = 'auth_token=' + token + '; ' + expires + '; path=/';
        }
    </script>
</head>

<body>
    <h1>Register</h1>
    <?php if (!empty($errorMessage)): ?>
        <div class="error" style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>
    <?php if (!empty($successMessage)): ?>
        <div class="success" style="color: green;"><?php echo htmlspecialchars($successMessage); ?></div>
        <script>
            setAuthToken('<?php echo htmlspecialchars($generatedToken); ?>');
        </script>
    <?php else: ?>
        <form action="register.php" method="POST">
            <label for="username-input">Username:</label>
            <input type="text" id="username-input" name="username" required>
            <button type="submit">Register</button>
        </form>
    <?php endif; ?>
</body>

</html>
