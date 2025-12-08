<!DOCTYPE html>
<html>
<head>
    <title>Create File</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Create File Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Creating new file</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $filepath = $_POST['filepath'] ?? '';
        
        if (empty($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: No File Path Provided</h4>
                <p>Please provide a file path to create.</p>
            </div>';
        } elseif (file_exists($filepath)) {
            echo '
            <div class="w3-panel w3-yellow w3-round w3-padding">
                <h4><i class="w3-large">⚠</i> File Already Exists</h4>
                <p>File "' . htmlspecialchars($filepath) . '" already exists.</p>
                <p><b>File Size:</b> ' . filesize($filepath) . ' bytes</p>
                <p><b>Last Modified:</b> ' . date('Y-m-d H:i:s', filemtime($filepath)) . '</p>
            </div>';
        } else {
            // Create directory if it doesn't exist
            $dir = dirname($filepath);
            if (!empty($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            
            $handle = fopen($filepath, 'w');
            
            if ($handle === false) {
                echo '
                <div class="w3-panel w3-red w3-round w3-padding">
                    <h4><i class="w3-large">❌</i> Error: Creation Failed</h4>
                    <p>Could not create file "' . htmlspecialchars($filepath) . '".</p>
                    <p>Check directory permissions.</p>
                </div>';
            } else {
                fclose($handle);
                
                echo '
                <div class="w3-panel w3-green w3-round w3-padding">
                    <h4><i class="w3-large">✅</i> File Created Successfully</h4>
                </div>';
                
                echo '
                <div class="w3-panel w3-light-grey w3-round w3-padding">
                    <h5><i class="w3-large">📄</i> File Information</h5>
                    <div class="w3-row">
                        <div class="w3-col m6">
                            <p><b>Path:</b> ' . htmlspecialchars($filepath) . '</p>
                            <p><b>Created:</b> ' . date('Y-m-d H:i:s') . '</p>
                        </div>
                        <div class="w3-col m6">
                            <p><b>File Size:</b> ' . filesize($filepath) . ' bytes</p>
                            <p><b>Writable:</b> ' . (is_writable($filepath) ? 'Yes' : 'No') . '</p>
                        </div>
                    </div>
                </div>';
                
                echo '
                <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                    <h5><i class="w3-large">📁</i> Directory Location</h5>
                    <div class="w3-code w3-light-grey w3-round w3-padding">' 
                        . htmlspecialchars(dirname(realpath($filepath))) . 
                    '</div>
                </div>';
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
