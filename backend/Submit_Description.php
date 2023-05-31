

<?php
require_once 'Auth.php';
checkAuthentication();



if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Method Not Allowed");
}



function getItems() {
    $descriptionsFile = 'Descriptions.json';
    $items = [];

    if (file_exists($descriptionsFile)) {
        $itemsJson = file_get_contents($descriptionsFile);
    }
    
    return $itemsJson;
}



$description = $_POST['description'];
$imageName = $_POST['image'];
$user_token = $_POST['token'];
$Existing=FALSE;

function submitDescription($description, $user_token, $imageName) {
    $descriptionsFile = 'Descriptions.json';
    $items = [];

    if (file_exists($descriptionsFile)) {
        $itemsJson = file_get_contents($descriptionsFile);
        $items = json_decode($itemsJson, true);
    }

    $itemIndex = findItemIndex($imageName, $items);

    if ($itemIndex === -1) {



        $item = [
            "item" => $imageName,
            "descriptions" => [
                [$description, 0, $user_token, []]
            ]
        ];
        $items[] = $item;
    } else {



        $existingdescriptions=getItems();

        $array = json_decode($existingdescriptions, true);
        // Loop through all descriptions in the array and print the ID value
        foreach ($array as $val) {
          foreach ($val['descriptions'] as $desc) {
            $id = $desc[2];
            if($user_token==$id){
                header('Location: Describe.php');
                exit();
            }
          }
        }


        $newDescription = [$description, 0, $user_token, []];
        $items[$itemIndex]['descriptions'][] = $newDescription;
    }

    // Save the updated items to the Descriptions.json file
    file_put_contents($descriptionsFile, json_encode($items));
    echo '<script>location.href = "Describe.php";</script>';
    header('Location: Describe.php');
    exit();
}

function findItemIndex($itemName, $items) {
    foreach ($items as $index => $item) {
        if ($item['item'] === $itemName) {
            return $index;
        }
    }
    return -1;
}

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




if(IsValid($user_token)){
    submitDescription($description, $user_token,$imageName);
}
echo '<script>location.href = "Describe.php";</script>';
header('Location: Describe.php');
exit();
?>