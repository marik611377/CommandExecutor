<!DOCTYPE html>
<html>
<head>
    <title>List Directory</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .file-icon { font-size: 20px; margin-right: 10px; }
        .file-item:hover { background-color: #f0f0f0; }
        .size-cell { text-align: right; }
        .type-badge { font-size: 10px; padding: 2px 6px; margin-left: 5px; }
    </style>
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1200px; margin: 0 auto;">
    
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">List Directory Action</h2>
        <p class="w3-margin-0 w3-padding w3-small">Listing directory contents</p>
    </div>
    
    <div class="w3-container w3-padding-24">
        <?php
        $directory = $_POST['filepath'] ?? '.';
        
        if (!is_dir($directory)) {
            echo '
            <div class="w3-panel w3-red w3-round w3-padding">
                <h4><i class="w3-large">❌</i> Error: Invalid Directory</h4>
                <p>Directory "' . htmlspecialchars($directory) . '" does not exist or is not accessible.</p>
            </div>';
        } else {
            // Get real path for display
            $realpath = realpath($directory);
            
            echo '
            <div class="w3-panel w3-green w3-round w3-padding">
                <h4><i class="w3-large">✅</i> Directory Listing</h4>
                <p>Showing contents of: ' . htmlspecialchars($realpath) . '</p>
            </div>';
            
            // Get directory contents
            $items = scandir($directory);
            $directories = [];
            $files = [];
            
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                
                $fullpath = $directory . DIRECTORY_SEPARATOR . $item;
                if (is_dir($fullpath)) {
                    $directories[] = [
                        'name' => $item,
                        'type' => 'directory',
                        'size' => '-',
                        'modified' => filemtime($fullpath),
                        'perms' => substr(sprintf('%o', fileperms($fullpath)), -4)
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'type' => 'file',
                        'size' => filesize($fullpath),
                        'modified' => filemtime($fullpath),
                        'perms' => substr(sprintf('%o', fileperms($fullpath)), -4)
                    ];
                }
            }
            
            // Sort directories and files
            usort($directories, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            usort($files, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            $allItems = array_merge($directories, $files);
            $totalCount = count($allItems);
            $dirCount = count($directories);
            $fileCount = count($files);
            
            echo '
            <div class="w3-panel w3-light-grey w3-round w3-padding">
                <h5><i class="w3-large">📊</i> Directory Statistics</h5>
                <div class="w3-row">
                    <div class="w3-col m4">
                        <p><b>Total Items:</b> ' . $totalCount . '</p>
                        <p><b>Directories:</b> ' . $dirCount . '</p>
                    </div>
                    <div class="w3-col m4">
                        <p><b>Files:</b> ' . $fileCount . '</p>
                        <p><b>Path:</b> ' . htmlspecialchars($realpath) . '</p>
                    </div>
                    <div class="w3-col m4">
                        <p><b>Readable:</b> ' . (is_readable($directory) ? 'Yes' : 'No') . '</p>
                        <p><b>Writable:</b> ' . (is_writable($directory) ? 'Yes' : 'No') . '</p>
                    </div>
                </div>
            </div>';
            
            if ($totalCount > 0) {
                echo '
                <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                    <h5><i class="w3-large">📋</i> Directory Contents</h5>
                    <div class="w3-responsive">
                        <table class="w3-table w3-striped w3-bordered">
                            <thead>
                                <tr class="w3-blue">
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th class="size-cell">Size</th>
                                    <th>Permissions</th>
                                    <th>Last Modified</th>
                                </tr>
                            </thead>
                            <tbody>';
                
                foreach ($allItems as $item) {
                    $icon = $item['type'] === 'directory' ? '📁' : '📄';
                    $typeBadge = $item['type'] === 'directory' ? 
                        '<span class="w3-tag w3-blue type-badge">DIR</span>' : 
                        '<span class="w3-tag w3-green type-badge">FILE</span>';
                    
                    $size = $item['type'] === 'directory' ? '-' : $this->formatBytes($item['size']);
                    $modified = date('Y-m-d H:i:s', $item['modified']);
                    
                    echo '
                                <tr class="file-item">
                                    <td>' . $icon . ' ' . $typeBadge . '</td>
                                    <td><b>' . htmlspecialchars($item['name']) . '</b></td>
                                    <td class="size-cell">' . $size . '</td>
                                    <td>' . $item['perms'] . '</td>
                                    <td>' . $modified . '</td>
                                </tr>';
                }
                
                echo '
                            </tbody>
                        </table>
                    </div>
                </div>';
            } else {
                echo '
                <div class="w3-panel w3-yellow w3-round w3-padding">
                    <h5><i class="w3-large">📁</i> Empty Directory</h5>
                    <p>This directory contains no files or subdirectories.</p>
                </div>';
            }
        }
        
        // Helper function to format file sizes
        function formatBytes($bytes, $precision = 2) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
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
