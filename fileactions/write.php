<!DOCTYPE html>
<html>
<head>
    <title>Write File</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Write File Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Writing content to file</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $filepath = $_POST['filepath'] ?? '';
        $content = $_POST['content'] ?? '';
        
        if (empty($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: No File Path Provided</h4>
                <p>Please provide a file path to write to.</p>
            </div>';
        } else {
            // Create directory if it doesn't exist
            $dir = dirname($filepath);
            if (!empty($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            
            $result = file_put_contents($filepath, $content);
            
            if ($result === false) {
                echo '
                <div class="w3-panel w3-red w3-round w3-padding">
                    <h4><i class="w3-large">❌</i> Error: Write Failed</h4>
                    <p>Could not write to file "' . htmlspecialchars($filepath) . '".</p>
                </div>';
            } else {
                echo '
                <div class="w3-panel w3-green w3-round w3-padding">
                    <h4><i class="w3-large">✅</i> File Written Successfully</h4>
                    <p>' . $result . ' bytes written to file.</p>
                </div>';
                
                echo '
                <div class="w3-panel w3-light-grey w3-round w3-padding">
                    <h5><i class="w3-large">📄</i> File Information</h5>
                    <div class="w3-row">
                        <div class="w3-col m6">
                            <p><b>Path:</b> ' . htmlspecialchars($filepath) . '</p>
                            <p><b>Bytes Written:</b> ' . $result . '</p>
                        </div>
                        <div class="w3-col m6">
                            <p><b>Content Length:</b> ' . strlen($content) . ' characters</p>
                            <p><b>File Exists:</b> ' . (file_exists($filepath) ? 'Yes' : 'No') . '</p>
                        </div>
                    </div>
                </div>';
                
                if (strlen($content) > 0) {
                    echo '
                    <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                        <h5><i class="w3-large">📖</i> Written Content Preview</h5>
                        <div class="w3-code w3-dark-grey w3-round w3-padding" style="max-height: 300px; overflow-y: auto; white-space: pre-wrap; word-break: break-all;">' 
                            . htmlspecialchars(substr($content, 0, 1000)) . 
                            (strlen($content) > 1000 ? "\n\n... (content truncated)" : "") . 
                        '</div>
                    </div>';
                }
            }
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
