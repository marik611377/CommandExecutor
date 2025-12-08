<!DOCTYPE html>
<html>
<head>
    <title>File System Evaluator</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .action-card {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .action-card:hover {
            transform: translateY(-5px);
        }
        .action-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="w3-container w3-dark-grey w3-padding-32">

<div class="w3-container w3-card-4 w3-white w3-round-large" style="max-width: 1200px; margin: 0 auto;">
    
    <!-- Header -->
    <div class="w3-container w3-blue w3-round-top">
        <h2 class="w3-margin-0 w3-padding">File System Evaluator</h2>
        <p class="w3-margin-0 w3-padding w3-small">Manage files and directories on the server</p>
    </div>
    
    <!-- Main Content -->
    <div class="w3-container w3-padding-24">
        
        <!-- File Actions Selection -->
        <div class="w3-panel">
            <h4 class="w3-text-blue">Select File Action:</h4>
            <div class="w3-row w3-margin-top">
                
                <!-- Read Action -->
                <div class="w3-col m4 w3-padding">
                    <div class="w3-card w3-round w3-padding action-card" onclick="showForm('read')">
                        <div class="w3-center">
                            <div class="action-icon">📖</div>
                            <h5>Read File</h5>
                            <p class="w3-small w3-text-grey">Read contents of a file</p>
                        </div>
                    </div>
                </div>
                
                <!-- Write Action -->
                <div class="w3-col m4 w3-padding">
                    <div class="w3-card w3-round w3-padding action-card" onclick="showForm('write')">
                        <div class="w3-center">
                            <div class="action-icon">✍️</div>
                            <h5>Write File</h5>
                            <p class="w3-small w3-text-grey">Write content to a file</p>
                        </div>
                    </div>
                </div>
                
                <!-- Create Action -->
                <div class="w3-col m4 w3-padding">
                    <div class="w3-card w3-round w3-padding action-card" onclick="showForm('create')">
                        <div class="w3-center">
                            <div class="action-icon">📄</div>
                            <h5>Create File</h5>
                            <p class="w3-small w3-text-grey">Create a new file</p>
                        </div>
                    </div>
                </div>
                
                <!-- Delete Action -->
                <div class="w3-col m4 w3-padding">
                    <div class="w3-card w3-round w3-padding action-card" onclick="showForm('delete')">
                        <div class="w3-center">
                            <div class="action-icon">🗑️</div>
                            <h5>Delete File</h5>
                            <p class="w3-small w3-text-grey">Delete a file</p>
                        </div>
                    </div>
                </div>
                
                <!-- List Action -->
                <div class="w3-col m4 w3-padding">
                    <div class="w3-card w3-round w3-padding action-card" onclick="showForm('list')">
                        <div class="w3-center">
                            <div class="action-icon">📁</div>
                            <h5>List Directory</h5>
                            <p class="w3-small w3-text-grey">List files in a directory</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dynamic Form Area -->
        <div id="formArea" class="w3-panel w3-light-grey w3-round w3-padding" style="display: none;">
            <h4 id="formTitle" class="w3-text-blue"></h4>
            <form id="actionForm" method="POST" action="">
                <div id="formFields">
                    <!-- Form fields will be dynamically inserted here -->
                </div>
                <div class="w3-center w3-margin-top">
                    <button type="submit" class="w3-button w3-blue w3-round w3-padding-large w3-hover-dark-blue">
                        <b>Execute Action</b>
                    </button>
                    <button type="button" onclick="hideForm()" class="w3-button w3-grey w3-round w3-padding-large w3-hover-dark-grey">
                        <b>Cancel</b>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Current Directory Info -->
        <div class="w3-panel w3-border-top w3-border-blue w3-padding-16">
            <h5><i class="w3-large">📁</i> Current Directory Information</h5>
            <div class="w3-code w3-light-grey w3-round w3-padding w3-small">
                <?php echo htmlspecialchars(getcwd()); ?>
            </div>
            <p class="w3-text-grey w3-small w3-margin-top">
                <b>Note:</b> All file paths are relative to the current directory unless specified as absolute paths.
            </p>
        </div>
    </div>
</div>

<script>
    // Form configurations for each action
    const formConfigs = {
        'read': {
            title: 'Read File',
            action: 'fileactions/read.php',
            fields: [
                {
                    type: 'text',
                    name: 'filepath',
                    label: 'File Path:',
                    placeholder: 'Enter file path (e.g., test.txt or folder/file.txt)',
                    required: true
                }
            ]
        },
        'write': {
            title: 'Write to File',
            action: 'fileactions/write.php',
            fields: [
                {
                    type: 'text',
                    name: 'filepath',
                    label: 'File Path:',
                    placeholder: 'Enter file path',
                    required: true
                },
                {
                    type: 'textarea',
                    name: 'content',
                    label: 'Content:',
                    placeholder: 'Enter content to write',
                    rows: 10,
                    required: true
                }
            ]
        },
        'create': {
            title: 'Create File',
            action: 'fileactions/create.php',
            fields: [
                {
                    type: 'text',
                    name: 'filepath',
                    label: 'File Path:',
                    placeholder: 'Enter new file path',
                    required: true
                }
            ]
        },
        'delete': {
            title: 'Delete File',
            action: 'fileactions/delete.php',
            fields: [
                {
                    type: 'text',
                    name: 'filepath',
                    label: 'File Path:',
                    placeholder: 'Enter file path to delete',
                    required: true
                }
            ]
        },
        'list': {
            title: 'List Directory Contents',
            action: 'fileactions/list.php',
            fields: [
                {
                    type: 'text',
                    name: 'filepath',
                    label: 'Directory Path:',
                    placeholder: 'Enter directory path (leave empty for current directory)',
                    required: false
                }
            ]
        }
    };
    
    function showForm(action) {
        const config = formConfigs[action];
        if (!config) return;
        
        // Set form title and action
        document.getElementById('formTitle').textContent = config.title;
        document.getElementById('actionForm').action = config.action;
        
        // Build form fields
        let fieldsHtml = '';
        config.fields.forEach(field => {
            if (field.type === 'textarea') {
                fieldsHtml += `
                    <div class="w3-margin-bottom">
                        <label class="w3-text-blue"><b>${field.label}</b></label>
                        <textarea name="${field.name}" 
                                  class="w3-input w3-border w3-round" 
                                  rows="${field.rows || 5}"
                                  placeholder="${field.placeholder}"
                                  ${field.required ? 'required' : ''}
                                  style="resize: vertical;"></textarea>
                    </div>`;
            } else {
                fieldsHtml += `
                    <div class="w3-margin-bottom">
                        <label class="w3-text-blue"><b>${field.label}</b></label>
                        <input type="${field.type}" 
                               name="${field.name}" 
                               class="w3-input w3-border w3-round" 
                               placeholder="${field.placeholder}"
                               ${field.required ? 'required' : ''}>
                    </div>`;
            }
        });
        
        document.getElementById('formFields').innerHTML = fieldsHtml;
        document.getElementById('formArea').style.display = 'block';
        
        // Scroll to form
        document.getElementById('formArea').scrollIntoView({ behavior: 'smooth' });
    }
    
    function hideForm() {
        document.getElementById('formArea').style.display = 'none';
    }
    
    // Auto-focus first input when form is shown
    document.addEventListener('DOMContentLoaded', function() {
        const formArea = document.getElementById('formArea');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    if (formArea.style.display === 'block') {
                        const firstInput = formArea.querySelector('input, textarea');
                        if (firstInput) firstInput.focus();
                    }
                }
            });
        });
        observer.observe(formArea, { attributes: true });
    });
</script>

</body>
</html>
