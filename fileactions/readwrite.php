<!DOCTYPE html>
<html>
<head>
    <title>Read/Write File</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-button.active { background-color: #2196F3 !important; color: white !important; }
        .readonly-textarea {
            background-color: #f5f5f5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1200px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">File Read/Write Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Read or write file contents</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $filepath = $_POST['filepath'] ?? '';
        $content = $_POST['content'] ?? '';
        $action = $_POST['action'] ?? 'read'; // 'read' or 'write'
        
        if (empty($filepath)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: No File Path Provided</h4>
                <p>Please provide a file path.</p>
            </div>';
        } else {
            // Check if file exists
            $fileExists = file_exists($filepath);
            $isReadable = $fileExists ? is_readable($filepath) : false;
            $isWritable = $fileExists ? is_writable($filepath) : false;
            
            // Tab navigation
            echo '
            <div class="w3-bar w3-light-grey w3-round">
                <button class="w3-bar-item w3-button tab-button ' . ($action == 'read' ? 'active' : '') . '" onclick="switchTab(\'read\')">
                    <i class="w3-large">📖</i> Read File
                </button>
                <button class="w3-bar-item w3-button tab-button ' . ($action == 'write' ? 'active' : '') . '" onclick="switchTab(\'write\')">
                    <i class="w3-large">✍️</i> Write File
                </button>
            </div>';
            
            // File information section
            echo '
            <div class="w3-panel w3-light-grey w3-round w3-padding w3-margin-top">
                <h5><i class="w3-large">📄</i> File Information</h5>
                <div class="w3-row">
                    <div class="w3-col m6">
                        <p><b>Path:</b> ' . htmlspecialchars($filepath) . '</p>
                        <p><b>Exists:</b> ' . ($fileExists ? '✅ Yes' : '❌ No') . '</p>
                    </div>
                    <div class="w3-col m6">
                        <p><b>Readable:</b> ' . ($isReadable ? '✅ Yes' : '❌ No') . '</p>
                        <p><b>Writable:</b> ' . ($isWritable ? '✅ Yes' : '❌ No') . '</p>
                    </div>
                </div>';
            
            if ($fileExists) {
                $filesize = filesize($filepath);
                $filetime = date('Y-m-d H:i:s', filemtime($filepath));
                echo '
                <div class="w3-row w3-margin-top">
                    <div class="w3-col m6">
                        <p><b>Size:</b> ' . $filesize . ' bytes</p>
                    </div>
                    <div class="w3-col m6">
                        <p><b>Last Modified:</b> ' . $filetime . '</p>
                    </div>
                </div>';
            }
            echo '</div>';
            
            // Read Tab Content
            echo '<div id="readTab" class="tab-content ' . ($action == 'read' ? 'active' : '') . '">';
            
            if (!$fileExists) {
                echo '
                <div class="w3-panel w3-yellow w3-round w3-padding">
                    <h5><i class="w3-large">⚠</i> File Not Found</h5>
                    <p>File "' . htmlspecialchars($filepath) . '" does not exist.</p>
                    <p>Switch to "Write File" tab to create it.</p>
                </div>';
            } elseif (!$isReadable) {
                echo '
                <div class="w3-panel w3-red w3-round w3-padding">
                    <h5><i class="w3-large">❌</i> Cannot Read File</h5>
                    <p>File "' . htmlspecialchars($filepath) . '" is not readable.</p>
                </div>';
            } else {
                $fileContent = file_get_contents($filepath);
                echo '
                <div class="w3-panel w3-green w3-round w3-padding">
                    <h5><i class="w3-large">✅</i> File Read Successfully</h5>
                </div>
                
                <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                    <h5><i class="w3-large">📖</i> File Contents</h5>
                    <form method="POST" action="readwrite.php">
                        <input type="hidden" name="action" value="write">
                        <input type="hidden" name="filepath" value="' . htmlspecialchars($filepath) . '">
                        <textarea name="content" 
                                  class="w3-input w3-border w3-round readonly-textarea" 
                                  rows="15" 
                                  style="resize: vertical;" 
                                  readonly>' . htmlspecialchars($fileContent) . '</textarea>
                        <div class="w3-center w3-margin-top">
                            <button type="button" onclick="enableEdit()" class="w3-button w3-blue w3-round w3-padding-large">
                                <b>Edit This File</b>
                            </button>
                            <button type="submit" class="w3-button w3-green w3-round w3-padding-large" style="display:none;" id="saveButton">
                                <b>Save Changes</b>
                            </button>
                            <button type="button" onclick="location.reload()" class="w3-button w3-grey w3-round w3-padding-large">
                                <b>Cancel</b>
                            </button>
                        </div>
                    </form>
                </div>';
            }
            echo '</div>'; // End read tab
            
            // Write Tab Content
            echo '<div id="writeTab" class="tab-content ' . ($action == 'write' ? 'active' : '') . '">';
            
            if ($fileExists && !$isWritable) {
                echo '
                <div class="w3-panel w3-red w3-round w3-padding">
                    <h5><i class="w3-large">❌</i> Cannot Write to File</h5>
                    <p>File "' . htmlspecialchars($filepath) . '" is not writable.</p>
                    <p>Check file permissions.</p>
                </div>';
            }
            
            // Handle write operation if content was provided
            if ($action == 'write' && !empty($content)) {
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
                        <h4><i class="w3-large">✅</i> File ' . ($fileExists ? 'Updated' : 'Created') . ' Successfully</h4>
                        <p>' . $result . ' bytes written to file.</p>
                    </div>';
                    
                    // Refresh file info
                    $fileExists = true;
                    $filesize = filesize($filepath);
                    $filetime = date('Y-m-d H:i:s', filemtime($filepath));
                }
            }
            
            // Write form
            echo '
            <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                <h5><i class="w3-large">✍️</i> ' . ($fileExists ? 'Edit' : 'Create') . ' File Content</h5>
                <form method="POST" action="readwrite.php">
                    <input type="hidden" name="action" value="write">
                    <input type="hidden" name="filepath" value="' . htmlspecialchars($filepath) . '">
                    
                    <div class="w3-margin-bottom">
                        <label class="w3-text-blue"><b>File Path:</b></label>
                        <input type="text" 
                               value="' . htmlspecialchars($filepath) . '" 
                               class="w3-input w3-border w3-round" 
                               disabled>
                    </div>
                    
                    <div class="w3-margin-bottom">
                        <label class="w3-text-blue"><b>Content:</b></label>
                        <textarea name="content" 
                                  class="w3-input w3-border w3-round" 
                                  rows="15" 
                                  style="resize: vertical;" 
                                  placeholder="Enter file content here...">' . htmlspecialchars($content ?: ($fileExists ? file_get_contents($filepath) : '')) . '</textarea>
                    </div>
                    
                    <div class="w3-center w3-margin-top">
                        <button type="submit" class="w3-button w3-green w3-round w3-padding-large">
                            <b>' . ($fileExists ? 'Update File' : 'Create File') . '</b>
                        </button>
                        <button type="button" onclick="switchTab(\'read\')" class="w3-button w3-blue w3-round w3-padding-large">
                            <b>Switch to Read View</b>
                        </button>
                    </div>
                </form>
            </div>';
            echo '</div>'; // End write tab
        }
        ?>
        
        <div class="w3-center w3-margin-top">
            <a href="../evaluator.php" class="w3-button w3-blue w3-round w3-padding-large w3-hover-dark-blue">
                <b>Back to Evaluator</b>
            </a>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected tab
        document.getElementById(tabName + 'Tab').classList.add('active');
        
        // Activate selected button
        event.target.classList.add('active');
        
        // Update form action if needed
        if (tabName === 'read') {
            // Refresh to show current content
            location.reload();
        }
    }
    
    function enableEdit() {
        const textarea = document.querySelector('textarea[name="content"]');
        const saveButton = document.getElementById('saveButton');
        const editButton = event.target;
        
        // Make textarea editable
        textarea.classList.remove('readonly-textarea');
        textarea.readOnly = false;
        textarea.focus();
        
        // Show save button, hide edit button
        editButton.style.display = 'none';
        if (saveButton) saveButton.style.display = 'inline-block';
        
        // Switch to write tab
        switchTab('write');
    }
</script>

</body>
</html>
