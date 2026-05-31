<?php 
      $server = 'localhost';
      $username ='root';
      $password = '';
      $database = 'Lancaire';
      
      $con = mysqli_connect($server, $username, $password, $database);
      
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
        <div class="left">
           <?php 
           
           $query2 = "SELECT username, message FROM posts;";
           $result2 = mysqli_query($con, $query2);
           
           while($row = mysqli_fetch_array($result2)){
            echo "<section class='test'>
            <h1>". $row['username'] ."</h1>
            <p>". $row['message'] ."</p>
            </section>";
           }
           
           ?>
        </div>
        
        <div class="middle"></div>
        <div class="right">
            <form method="POST" action="index.php">
                <br>
                Username<br>
                <textarea name="username" maxlength="15" class="inputuser" type="text"> </textarea><br>
                Text<br>
                <textarea name="message" id="message" class="inputmessage" type="text"></textarea><br>
                Post<br>
                <button class="inputbutton" type="submit"></button>

                    <?php

                    if ($_SERVER["REQUEST_METHOD"] === "POST") {

                        $username = trim($_POST['username'] ?? '');
                        $message  = trim($_POST['message'] ?? '');

                        if ($username === '' || $message === '') {
                            exit;
                        }

                        $stmt = mysqli_prepare($con, "INSERT INTO posts (username, message) VALUES (?, ?)");

                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "ss", $username, $message);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            header("Location: " . $_SERVER['PHP_SELF']);
                            exit();
                        } else {
                            echo "Database error: " . mysqli_error($con);
                        }
                    }
                    ?>
                


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