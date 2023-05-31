<?php
require_once './backend/auth.php';
checkAuthentication();
#Authentication



function getItems() {
    $descriptionsFile = 'Descriptions.json';
    $items = [];

    if (file_exists($descriptionsFile)) {
        $itemsJson = file_get_contents($descriptionsFile);
        $items = json_decode($itemsJson, true);
    }
    
    return $items;
}
$items = getItems();
?>


<!doctype html>
<html lang="en">
<head>
    
<div class="top-bar-class">
<a href="Vote.php">Vote</a>
<a href="Describe.php">Describe</a>
</div>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>

    <style>
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

        .item {
            margin-bottom: 40px;
        }

        .item h2 {
            margin-bottom: 20px;
        }

        .image-container {
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
        }

        .descriptions-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .description {
            width: 300px;
            margin: 10px;
            padding: 10px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .description p {
            margin-bottom: 10px;
        }

        .vote-button {
            width: 100px;
            padding: 10px;
            border-radius: 8px;
            border: none;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        .upvote {
            background-color: #10b981;
        }

        .downvote {
            background-color: #ef4444;
        }
</head>
<body>
    <h1>Vote on Descriptions</h1>
    <div id="items-container">
        <?php foreach ($items as $item): ?>
            <div class="item">
                <h2><?php echo htmlspecialchars(substr($item['item'],0,-4)); ?></h2>
                <div class="image-container">
                    <img src="<?php echo "https://github.com/Jack-Bagel/Minecraft-Lora-Training/raw/main/image/5_minecraft/" . htmlspecialchars($item['item']); ?>" alt="<?php echo htmlspecialchars($item['item']); ?>">
                </div>
                <div class="descriptions-container">
                    <?php foreach ($item['descriptions'] as $index => $description): ?>
                        <div class="description">
                            <?php echo htmlspecialchars($description[1]) ?>
                            <p><?php echo htmlspecialchars($description[0]); ?></p>
                            <button class="vote-button upvote" onclick="submitVote('<?php echo htmlspecialchars($item['item']); ?>', '<?php echo htmlspecialchars($index); ?>', 'upvote')">Upvote</button>
                            <button class="vote-button downvote" onclick="submitVote('<?php echo htmlspecialchars($item['item']); ?>', '<?php echo htmlspecialchars($index); ?>', 'downvote')">Downvote</button>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>






        async function submitVote(itemName, descriptionIndex, voteType) {
    const userToken = localStorage.getItem("auth_token");
    const response = await fetch("Submit_Vote.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            itemName: itemName,
            descriptionIndex: descriptionIndex,
            voteType: voteType,
            userToken: userToken,
        }),
    });

    if (response.status === 200) {
        location.reload()
    }
    else{
        alert("Already Voted")
    }
}

    </script>

</body>
</html>
