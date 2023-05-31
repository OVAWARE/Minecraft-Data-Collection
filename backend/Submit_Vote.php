<?php
require_once 'Auth.php';
checkAuthentication();


function IsValid($token) {
    $accountsFile = 'accounts.json';

    if (!file_exists($accountsFile)) {
        return false;
    }

    $accountsJson = file_get_contents($accountsFile);
    $accounts = json_decode($accountsJson, true);

    foreach ($accounts as $account) {
        if ($account['Token'] === $token) {
            return true;
        }
    }

    return false;
}

function canUserVote($userToken, $description) {
    if(IsValid($userToken)){
        return !in_array($userToken, $description[3]);
    }
    else{
        return false;
    }
}

function submitVote($itemName, $descriptionIndex, $voteType, $userToken) {
    $descriptionsFile = 'Descriptions.json';
    $items = [];

    if (file_exists($descriptionsFile)) {
        $itemsJson = file_get_contents($descriptionsFile);
        $items = json_decode($itemsJson, true);
    }

    $itemIndex = findItemIndex($itemName, $items);

    if ($itemIndex !== -1 && canUserVote($userToken, $items[$itemIndex]['descriptions'][$descriptionIndex])) {
        // Update vote count based on vote type (upvote or downvote)
        if ($voteType === 'upvote') {
            $items[$itemIndex]['descriptions'][$descriptionIndex][1]++;
        } else {
            $items[$itemIndex]['descriptions'][$descriptionIndex][1]--;
        }
        // Add the user's ID to the list of users who have already voted
        $items[$itemIndex]['descriptions'][$descriptionIndex][3][] = $userToken;
        // Save the updated items to the Descriptions.json file
        file_put_contents($descriptionsFile, json_encode($items));
        return true;
    }

    return false;
}

function findItemIndex($itemName, $items) {
    foreach ($items as $index => $item) {
        if ($item['item'] === $itemName) {
            return $index;
        }
    }
    return -1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $itemName = isset($input['itemName']) ? $input['itemName'] : null;
    $descriptionIndex = isset($input['descriptionIndex']) ? intval($input['descriptionIndex']) : null;
    $voteType = isset($input['voteType']) ? $input['voteType'] : null;
    $userToken = isset($input['userToken']) ? $input['userToken'] : null;


    if ($itemName !== null && $descriptionIndex !== null && $voteType !== null && $userToken !== null) {
        if (submitVote($itemName, $descriptionIndex, $voteType, $userToken)) {
            http_response_code(200);
            echo "Vote submitted successfully!";
        } else {
            http_response_code(400);
            echo "Unable to submit vote.";
        }
    } else {
        http_response_code(400);
        echo "Invalid input data.";
    }
} else {
    http_response_code(405);
    echo "Invalid request method.";
}
?>
