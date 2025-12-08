<!DOCTYPE html>
<html>
<head>
    <title>Delete File</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1000px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Delete File Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Deleting file</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $filepath = $_POST['filepath'] ?? '';
        
        if (empty($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: No File Path Provided</h4>
                <p>Please provide a file path to delete.</p>
            </div>';
        } elseif (!file_exists($filepath)) {
            echo '
            <div class="w3-panel w3-yellow w3-round w3-padding">
                <h4><i class="w3-large">⚠</i> File Not Found</h4>
                <p>File "' . htmlspecialchars($filepath) . '" does not exist.</p>
            </div>';
        } elseif (!is_writable($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: Permission Denied</h4>
                <p>File "' . htmlspecialchars($filepath) . '" is not writable.</p>
                <p>Check file permissions.</p>
            </div>';
        } else {
            // Get file info before deletion
            $filesize = filesize($filepath);
            $filetime = date('Y-m-d H:i:s', filemtime($filepath));
            $filetype = filetype($filepath);
            
            if (unlink($filepath)) {
                echo '
                <div class="w3-panel w3-green w3-round w3-padding">
                    <h4><i class="w3-large">✅</i> File Deleted Successfully</h4>
                </div>';
                
                echo '
                <div class="w3-panel w3-light-grey w3-round w3-padding">
                    <h5><i class="w3-large">🗑️</i> Deleted File Information</h5>
                    <div class="w3-row">
                        <div class="w3-col m6">
                            <p><b>Path:</b> ' . htmlspecialchars($filepath) . '</p>
                            <p><b>Size:</b> ' . $filesize . ' bytes</p>
                        </div>
                        <div class="w3-col m6">
                            <p><b>Type:</b> ' . $filetype . '</p>
                            <p><b>Last Modified:</b> ' . $filetime . '</p>
                        </div>
                    </div>
                </div>';
                
                echo '
                <div class="w3-panel w3-border w3-border-red w3-round w3-padding">
                    <h5><i class="w3-large">⚠</i> Important Note</h5>
                    <p>File has been permanently deleted. This action cannot be undone.</p>
                </div>';
            } else {
                echo '
                <div class="w3-panel w3-red w3-round w3-padding">
                    <h4><i class="w3-large">❌</i> Error: Deletion Failed</h4>
                    <p>Could not delete file "' . htmlspecialchars($filepath) . '".</p>
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
