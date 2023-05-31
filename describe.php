<?php
require_once './backend/auth.php';
echo checkAuthentication();



function getRandomImage() {
    $urls = file("./backend/urls.txt", FILE_IGNORE_NEW_LINES);
    return $urls[array_rand($urls)];
}
$imagePath = getRandomImage();
$imageName = basename($imagePath);
$jsonFilename = "PossibleDescriptions_" . $imageName . ".json";
?>



<div class="top-bar-class">
<a href="vote.php">Vote</a>
<a href="describe.php">Describe</a>
</div>




<!doctype html>
<html lang="en">
<head>
    
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Random Image</title>
    <style>
/* Reset default styles */
/* Reset default styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    text-align: center;
    background-color: #f0f2f5;
    color: #4b5563;
    line-height: 1.5;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    padding: 32px 24px;
}

.top-bar-class {
    background-color: #4b5563;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 25px;
}

.top-bar-class a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-weight: 600;
}

.top-bar-class a:hover {
    background-color: #6b7280;
    color: white;
}

.top-bar-class a.active {
    background-color: #3b82f6;
    color: white;
}

h1 {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 24px;
}

img {
    width: 100%;
    max-width: 600px;
    height: auto;
    object-fit: cover;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

#description-input {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    color: #4b5563;
    border: 1px solid #ced4da;
    border-radius: 6px;
    background-color: #f9fafb;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

#description-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
}

button {
    display: inline-block;
    font-size: 16px;
    font-weight: 500;
    padding: 8px 16px;
    margin-top: 24px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    color: #fff;
    background-color: #3b82f6;
    border: 1px solid transparent;
    border-radius: 6px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

button:hover {
    background-color: #2563eb;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.16);
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(49, 133, 253, 0.5);
}

.Removal{
    background-color:#F7844A;
    margin-top: 4px;
}

    </style>




<script>
function getToken() {
    let token = localStorage.getItem("auth_token");
    if (!token) {
        const cookies = decodeURIComponent(document.cookie).split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.indexOf("auth_token=") === 0) {
                token = cookie.substring("auth_token=".length, cookie.length);
            }
        }
    }
    return token;
}
</script>


</head>










<body>
<h1 id='ItemName'></h1>
<script>
    document.getElementById("ItemName").textContent = "<?= htmlspecialchars($imageName) ?>";
</script>
<img src="<?= htmlspecialchars($imagePath) ?>" alt="Randomly chosen image">
<form action="Submit_Description.php" method="POST">
    <input type="hidden" id="token-input" name="token">
    <input type="hidden" name="image" value="<?= htmlspecialchars($imageName) ?>">
    <input type="hidden" name="filename" value="<?= htmlspecialchars($jsonFilename) ?>">
    <label for="description-input">Describe the image:</label>
    <input type="text" id="description-input" name="description" required>
<script>
    document.getElementById('token-input').value = getToken();
</script>


    <button type="submit">Submit</button>
</form>

<script>
    function SuggestRemoval(){
        var result = confirm("Are you sure?");
        if(result){
            alert("Haha this is broken atm, just write down the item name and il add it")
        }
    }
</script>

<button type="SuggestRemoval" class="Removal" onclick="SuggestRemoval()">Suggest Texture Removal</button>




</body>
</html>
