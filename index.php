?php

$SUPABASE_URL = "https://dietcxtcntxxmeobdaug.supabase.co";
$SUPABASE_KEY = "YOUR_ANON_KEY_HERE"; // <-- paste your anon key here

// ----------------------
// FETCH POSTS
// ----------------------
function fetchPosts($SUPABASE_URL, $SUPABASE_KEY) {

    $url = $SUPABASE_URL . "/rest/v1/posts?select=username,message&order=id.desc";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// ----------------------
// INSERT POST
// ----------------------
function insertPost($SUPABASE_URL, $SUPABASE_KEY, $username, $message) {

    $url = $SUPABASE_URL . "/rest/v1/posts";

    $data = [
        "username" => $username,
        "message" => $message
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $SUPABASE_KEY",
        "Authorization: Bearer $SUPABASE_KEY",
        "Content-Type: application/json",
        "Prefer: return=representation"
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_exec($ch);
    curl_close($ch);
}

// ----------------------
// HANDLE FORM SUBMIT
// ----------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $message  = trim($_POST['message'] ?? '');

    if ($username !== '' && $message !== '') {
        insertPost($SUPABASE_URL, $SUPABASE_KEY, $username, $message);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Load posts
$posts = fetchPosts($SUPABASE_URL, $SUPABASE_KEY);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lancaire Hub</title>
    <link rel="shortcut icon" href="icon.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <h1>HUB<br><br>LANCAIRE</h1>
    <img class="headerlogo" src="icon.svg">
</header>

<main>

    <!-- LEFT: POSTS -->
    <div class="left">

        <?php
        if ($posts) {
            foreach ($posts as $row) {
                echo "<section class='test'>
                        <h1>" . htmlspecialchars($row['username']) . "</h1>
                        <p>" . htmlspecialchars($row['message']) . "</p>
                      </section>";
            }
        }
        ?>

    </div>

    <div class="middle"></div>

    <!-- RIGHT: FORM -->
    <div class="right">

        <form method="POST" action="index.php">
            <br>

            Username<br>
            <textarea name="username" maxlength="15" class="inputuser"></textarea><br>

            Text<br>
            <textarea name="message" id="message" class="inputmessage"></textarea><br>

            Post<br>
            <button class="inputbutton" type="submit"></button>
        </form>

        <script>
            const textarea = document.getElementById('message');

            function autoResize() {
                textarea.style.height = '60px';
                textarea.style.height = textarea.scrollHeight + 'px';
            }

            textarea.addEventListener('input', autoResize);
            autoResize();
        </script>

    </div>

</main>
</body>
</html>
