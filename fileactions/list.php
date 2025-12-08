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
        .directory-name { cursor: pointer; color: #0066cc; }
        .directory-name:hover { text-decoration: underline; }
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
        // Helper function to format file sizes
        function formatBytes($bytes, $precision = 2) {
            if ($bytes <= 0) return '0 B';
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);
            $bytes /= pow(1024, $pow);
            return round($bytes, $precision) . ' ' . $units[$pow];
        }
        
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
                        'perms' => substr(sprintf('%o', fileperms($fullpath)), -4),
                        'path' => $fullpath
                    ];
                } else {
                    $files[] = [
                        'name' => $item,
                        'type' => 'file',
                        'size' => filesize($fullpath),
                        'modified' => filemtime($fullpath),
                        'perms' => substr(sprintf('%o', fileperms($fullpath)), -4),
                        'path' => $fullpath
                    ];
                }
            }
            
            // Sort directories and files alphabetically
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
            
            // Calculate total size of files
            $totalSize = 0;
            foreach ($files as $file) {
                $totalSize += $file['size'];
            }
            
            echo '
            <div class="w3-panel w3-light-grey w3-round w3-padding">
                <h5><i class="w3-large">📊</i> Directory Statistics</h5>
                <div class="w3-row">
                    <div class="w3-col m3">
                        <p><b>Total Items:</b> ' . $totalCount . '</p>
                        <p><b>Directories:</b> ' . $dirCount . '</p>
                    </div>
                    <div class="w3-col m3">
                        <p><b>Files:</b> ' . $fileCount . '</p>
                        <p><b>Total Size:</b> ' . formatBytes($totalSize) . '</p>
                    </div>
                    <div class="w3-col m3">
                        <p><b>Path:</b> ' . htmlspecialchars($realpath) . '</p>
                        <p><b>Readable:</b> ' . (is_readable($directory) ? 'Yes' : 'No') . '</p>
                    </div>
                    <div class="w3-col m3">
                        <p><b>Writable:</b> ' . (is_writable($directory) ? 'Yes' : 'No') . '</p>
                        <p><b>Executable:</b> ' . (is_executable($directory) ? 'Yes' : 'No') . '</p>
                    </div>
                </div>
            </div>';
            
            if ($totalCount > 0) {
                echo '
                <div class="w3-panel w3-border w3-border-blue w3-round w3-padding">
                    <h5><i class="w3-large">📋</i> Directory Contents (' . $totalCount . ' items)</h5>
                    <div class="w3-responsive">
                        <table class="w3-table w3-striped w3-bordered w3-hoverable">
                            <thead>
                                <tr class="w3-blue">
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th class="size-cell">Size</th>
                                    <th>Permissions</th>
                                    <th>Last Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>';
                
                // Show parent directory link
                if ($directory != '.' && $directory != '/') {
                    $parentDir = dirname($realpath);
                    echo '
                                <tr class="w3-light-blue">
                                    <td>↩️</td>
                                    <td colspan="2">
                                        <form method="POST" action="list.php" style="display: inline;">
                                            <input type="hidden" name="filepath" value="' . htmlspecialchars(dirname($directory)) . '">
                                            <button type="submit" class="w3-button w3-blue w3-tiny w3-round">
                                                Parent Directory
                                            </button>
                                        </form>
                                    </td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>
                                        <a href="../evaluator.php" class="w3-button w3-green w3-tiny w3-round">Back to Evaluator</a>
                                    </td>
                                </tr>';
                }
                
                // Show directories
                foreach ($directories as $item) {
                    $icon = '📁';
                    $typeBadge = '<span class="w3-tag w3-blue type-badge">DIR</span>';
                    $modified = date('Y-m-d H:i:s', $item['modified']);
                    
                    echo '
                                <tr class="file-item">
                                    <td>' . $icon . ' ' . $typeBadge . '</td>
                                    <td>
                                        <form method="POST" action="list.php" style="display: inline;">
                                            <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                            <button type="submit" class="w3-button w3-blue w3-tiny w3-round" style="text-align: left; padding: 2px 8px;">
                                                📁 ' . htmlspecialchars($item['name']) . '
                                            </button>
                                        </form>
                                    </td>
                                    <td class="size-cell">-</td>
                                    <td>' . $item['perms'] . '</td>
                                    <td>' . $modified . '</td>
                                    <td>
                                        <div class="w3-dropdown-hover">
                                            <button class="w3-button w3-light-grey w3-tiny w3-round">Actions ▼</button>
                                            <div class="w3-dropdown-content w3-bar-block w3-border" style="right: 0;">
                                                <form method="POST" action="read.php" style="display: inline;">
                                                    <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                                    <button type="submit" class="w3-bar-item w3-button w3-tiny">📖 Read</button>
                                                </form>
                                                <form method="POST" action="delete.php" style="display: inline;">
                                                    <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                                    <button type="submit" class="w3-bar-item w3-button w3-tiny w3-red" onclick="return confirm(\'Delete directory ' . htmlspecialchars($item['name']) . '?\')">🗑️ Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>';
                }
                
                // Show files
                foreach ($files as $item) {
                    $icon = '📄';
                    $typeBadge = '<span class="w3-tag w3-green type-badge">FILE</span>';
                    $size = formatBytes($item['size']);
                    $modified = date('Y-m-d H:i:s', $item['modified']);
                    
                    // Get file extension for icon
                    $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
                    if (in_array($ext, ['php', 'html', 'htm', 'js', 'css'])) {
                        $icon = '💻';
                    } elseif (in_array($ext, ['txt', 'md', 'log'])) {
                        $icon = '📝';
                    } elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                        $icon = '🖼️';
                    } elseif (in_array($ext, ['pdf', 'doc', 'docx'])) {
                        $icon = '📚';
                    }
                    
                    echo '
                                <tr class="file-item">
                                    <td>' . $icon . ' ' . $typeBadge . '</td>
                                    <td><b>' . htmlspecialchars($item['name']) . '</b></td>
                                    <td class="size-cell">' . $size . '</td>
                                    <td>' . $item['perms'] . '</td>
                                    <td>' . $modified . '</td>
                                    <td>
                                        <div class="w3-dropdown-hover">
                                            <button class="w3-button w3-light-grey w3-tiny w3-round">Actions ▼</button>
                                            <div class="w3-dropdown-content w3-bar-block w3-border" style="right: 0;">
                                                <form method="POST" action="read.php" style="display: inline;">
                                                    <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                                    <button type="submit" class="w3-bar-item w3-button w3-tiny">📖 Read</button>
                                                </form>
                                                <form method="POST" action="write.php" style="display: inline;">
                                                    <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                                    <button type="submit" class="w3-bar-item w3-button w3-tiny">✍️ Edit</button>
                                                </form>
                                                <form method="POST" action="delete.php" style="display: inline;">
                                                    <input type="hidden" name="filepath" value="' . htmlspecialchars($item['path']) . '">
                                                    <button type="submit" class="w3-bar-item w3-button w3-tiny w3-red" onclick="return confirm(\'Delete file ' . htmlspecialchars($item['name']) . '?\')">🗑️ Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
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
                    <p>Use the "Create File" or "Write File" actions to add content.</p>
                </div>';
            }
            
            // Show disk space information
            if (function_exists('disk_free_space') && function_exists('disk_total_space')) {
                $free = disk_free_space($realpath);
                $total = disk_total_space($realpath);
                $used = $total - $free;
                $percent = $total > 0 ? round(($used / $total) * 100, 2) : 0;
                
                echo '
                <div class="w3-panel w3-border-top w3-border-blue w3-padding-16">
                    <h5><i class="w3-large">💾</i> Disk Space Information</h5>
                    <div class="w3-light-grey w3-round">
                        <div class="w3-container w3-blue w3-round" style="width:' . $percent . '%; height:24px;">' . $percent . '% used</div>
                    </div>
                    <div class="w3-row w3-margin-top">
                        <div class="w3-col m4">
                            <p><b>Total Space:</b> ' . formatBytes($total) . '</p>
                        </div>
                        <div class="w3-col m4">
                            <p><b>Used Space:</b> ' . formatBytes($used) . '</p>
                        </div>
                        <div class="w3-col m4">
                            <p><b>Free Space:</b> ' . formatBytes($free) . '</p>
                        </div>
                    </div>
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
