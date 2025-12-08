<!DOCTYPE html>
<html>
<head>
    <title>Read File</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Read File Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Reading file contents</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $filepath = $_POST['filepath'] ?? '';
        
        if (empty($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: No File Path Provided</h4>
                <p>Please provide a file path to read.</p>
            </div>';
        } elseif (!file_exists($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: File Not Found</h4>
                <p>File "' . htmlspecialchars($filepath) . '" does not exist.</p>
            </div>';
        } elseif (!is_readable($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: Permission Denied</h4>
                <p>File "' . htmlspecialchars($filepath) . '" is not readable.</p>
            </div>';
        } else {
            $content = file_get_contents($filepath);
            $filesize = filesize($filepath);
            $filetime = date('Y-m-d H:i:s', filemtime($filepath));
            
            echo '
            <div class="w3-panel w3-green w3-round w3-padding">
                <h4><i class="w3-large">✅</i> File Read Successfully</h4>
            </div>';
            
            echo '
            <div class="w3-panel w3-light-grey w3-round w3-padding">
                <h5><i class="w3-large">📄</i> File Information</h5>
                <div class="w3-row">
                    <div class="w3-col m6">
                        <p><b>Path:</b> ' . htmlspecialchars($filepath) . '</p>
                        <p><b>Size:</b> ' . $filesize . ' bytes</p>
                    </div>
                    <div class="w3-col m6">
                        <p><b>Last Modified:</b> ' . $filetime . '</p>
                        <p><b>Readable:</b> ' . (is_readable($filepath) ? 'Yes' : 'No') . '</p>
                    </div>
                </div>
            </div>';
            
            echo '
            <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                <h5><i class="w3-large">📖</i> File Contents</h5>
                <div class="w3-code w3-dark-grey w3-round w3-padding" style="max-height: 500px; overflow-y: auto; white-space: pre-wrap; word-break: break-all;">' 
                    . htmlspecialchars($content) . 
                '</div>
            </div>';
        }
        ?>
        
        <div class="w3-center w3-margin-top">
            <a href="../evaluator.php" class="w3-button w3-blue w3-round w3-padding-large w3-hover-dark-blue">
                <b>Back to Evaluator</b>
            </a>
        </div>
    </div>
</div>

</body>
</html>
