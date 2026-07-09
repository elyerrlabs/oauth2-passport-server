<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ config('app.name', 'Laravel') }} - {{ __('Logs') }}</title>
    <link nonce="{{ $nonce }}" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link nonce="{{ $nonce }}" rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    
    <style nonce="{{ $nonce }}">
        :root {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --text-primary: #212529;
            --text-secondary: #6c757d;
            --text-muted: #868e96;
            --border-color: #dee2e6;
            --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            --log-error: #dc3545;
            --log-warning: #ffc107;
            --log-info: #17a2b8;
            --log-debug: #6c757d;
            --log-error-bg: #fff5f5;
            --log-warning-bg: #fffbeb;
            --log-info-bg: #f0f9ff;
            --log-debug-bg: #f8f9fa;
            --code-bg: #f6f8fa;
            --code-text: #24292e;
            --trace-bg: #f6f8fa;
            --trace-text: #24292e;
            --trace-border: #d0d7de;
            --btn-hover: #e9ecef;
        }

        [data-theme="dark"] {
            --bg-primary: #0d1117;
            --bg-secondary: #161b22;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --text-muted: #484f58;
            --border-color: #30363d;
            --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.5);
            --log-error-bg: #1a0e0e;
            --log-warning-bg: #1a160e;
            --log-info-bg: #0e1a1a;
            --log-debug-bg: #0d1117;
            --code-bg: #0d1117;
            --code-text: #e6edf3;
            --trace-bg: #0d1117;
            --trace-text: #e6edf3;
            --trace-border: #30363d;
            --btn-hover: #1c2333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            padding: 15px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin: 0;
        }

        .log-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 18px 25px;
            margin-bottom: 20px;
            color: white;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .log-header h1 {
            margin: 0;
            font-weight: 300;
            font-size: 1.4rem;
        }

        .log-header .badge {
            font-size: 0.75rem;
            padding: 5px 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
        }

        .sidebar {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            height: fit-content;
            max-height: calc(100vh - 180px);
            overflow-y: auto;
            position: sticky;
            top: 15px;
            width: 100%;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 2px;
        }

        .sidebar .list-group-item {
            background: transparent;
            border: none;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 2px;
            color: var(--text-primary);
            transition: all 0.2s ease;
            font-size: 0.8rem;
            word-break: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar .list-group-item:hover {
            background: rgba(102, 126, 234, 0.08);
            padding-left: 15px;
        }

        .sidebar .list-group-item.llv-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 500;
        }

        .table-container {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            width: 100%;
        }

        /* Log Card Styles */
        .log-card {
            background: var(--bg-primary);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-left: 4px solid var(--border-color);
            transition: all 0.2s ease;
            position: relative;
        }

        .log-card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transform: translateX(3px);
        }

        .log-card.level-error {
            border-left-color: var(--log-error);
            background: var(--log-error-bg);
        }

        .log-card.level-warning {
            border-left-color: var(--log-warning);
            background: var(--log-warning-bg);
        }

        .log-card.level-info {
            border-left-color: var(--log-info);
            background: var(--log-info-bg);
        }

        .log-card.level-debug {
            border-left-color: var(--log-debug);
            background: var(--log-debug-bg);
        }

        /* Log Header (Level, Context, Date) */
        .log-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 15px;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border-color);
        }

        .log-level-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }

        .log-level-badge.error { background: var(--log-error); color: white; }
        .log-level-badge.warning { background: var(--log-warning); color: #2d2d2d; }
        .log-level-badge.info { background: var(--log-info); color: white; }
        .log-level-badge.debug { background: var(--log-debug); color: white; }

        .log-context {
            font-size: 0.75rem;
            background: rgba(102, 126, 234, 0.1);
            padding: 2px 10px;
            border-radius: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .log-date {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-family: 'SF Mono', 'Consolas', monospace;
            margin-left: auto;
            white-space: nowrap;
        }

        /* Log Body */
        .log-body {
            position: relative;
        }

        .log-text {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.75rem;
            line-height: 1.5;
            word-break: break-word;
            max-height: 200px;
            overflow-y: auto;
            padding: 8px 10px;
            background: var(--code-bg);
            color: var(--code-text);
            border-radius: 6px;
        }

        .log-text::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .log-text::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .log-text::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 2px;
        }

        .log-text pre {
            margin: 0;
            color: var(--code-text);
            background: transparent;
            padding: 0;
            font-family: inherit;
        }

        /* JSON View */
        .json-view {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.7rem;
            padding: 10px;
            background: #1e1e1e;
            border-radius: 6px;
            color: #d4d4d4;
            overflow-x: auto;
            margin: 5px 0;
            max-height: 200px;
            overflow-y: auto;
            line-height: 1.4;
        }

        .json-key { color: #f92672; }
        .json-string { color: #a6e22e; }
        .json-number { color: #ae81ff; }
        .json-boolean { color: #66d9ef; }
        .json-null { color: #f8f8f2; }
        .json-bracket { color: #f8f8f2; }

        /* Stack Trace */
        .stack-trace-container {
            margin-top: 8px;
        }

        .stack-trace {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.7rem;
            padding: 12px;
            background: var(--trace-bg);
            color: var(--trace-text);
            border-radius: 6px;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            line-height: 1.6;
            border: 1px solid var(--trace-border);
            margin-top: 8px;
            display: none;
        }

        .stack-trace.visible {
            display: block;
        }

        .stack-trace pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: inherit;
            font-size: 0.7rem;
            color: var(--trace-text);
            background: transparent;
            padding: 0;
        }

        .stack-trace::-webkit-scrollbar,
        .json-view::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .stack-trace::-webkit-scrollbar-track,
        .json-view::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        .stack-trace::-webkit-scrollbar-thumb,
        .json-view::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 3px;
        }

        /* Expand Button */
        .expand-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 4px 14px;
            font-size: 0.7rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .expand-btn:hover {
            background: var(--btn-hover);
            color: var(--text-primary);
            border-color: #667eea;
        }

        .expand-btn .fa-chevron-down,
        .expand-btn .fa-chevron-up {
            font-size: 0.6rem;
            transition: transform 0.3s ease;
        }

        .expand-btn .badge-trace {
            background: rgba(102, 126, 234, 0.15);
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 0.6rem;
            color: var(--text-secondary);
        }

        /* In file */
        .log-in-file {
            margin-top: 5px;
            font-size: 0.65rem;
            color: var(--text-secondary);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--border-color);
        }

        .action-buttons .btn {
            border-radius: 6px;
            padding: 5px 15px;
            font-size: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .dark-mode-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            user-select: none;
        }

        .dark-mode-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .toggle-switch {
            width: 34px;
            height: 18px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 9px;
            position: relative;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .toggle-switch.active {
            background: #667eea;
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 14px;
            height: 14px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: all 0.3s ease;
        }

        .toggle-switch.active::after {
            left: 18px;
        }

        /* DataTable overrides */
        #table-log {
            width: 100% !important;
            font-size: 0.8rem;
        }

        #table-log td {
            padding: 6px 8px;
            vertical-align: top;
            border: none;
        }

        #table-log tr {
            border-bottom: 1px solid var(--border-color);
        }

        #table-log thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 12px;
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        /* DataTable pagination */
        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 4px 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.8rem;
            margin-left: 5px;
            width: 200px;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 4px 8px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.8rem;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.75rem;
            color: var(--text-secondary);
            padding-top: 10px;
        }

        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary) !important;
            margin: 0 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white !important;
            border-color: transparent;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                position: static;
                max-height: 200px;
                margin-bottom: 15px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .log-header {
                padding: 15px;
                flex-direction: column;
                align-items: stretch;
            }

            .log-header h1 {
                font-size: 1.2rem;
            }

            .table-container {
                padding: 10px;
            }

            .log-meta {
                flex-wrap: wrap;
            }

            .log-date {
                margin-left: 0;
                width: 100%;
            }

            .log-text {
                font-size: 0.65rem;
                max-height: 150px;
            }

            .json-view, .stack-trace {
                font-size: 0.6rem;
                max-height: 120px;
            }
        }

        @media (max-width: 576px) {
            .log-level-badge {
                font-size: 0.6rem;
                padding: 1px 6px;
            }

            .log-context {
                font-size: 0.65rem;
                padding: 1px 8px;
            }
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .log-entry {
            animation: fadeIn 0.2s ease-out;
        }

        /* Smooth expand */
        .stack-trace {
            transition: all 0.3s ease;
        }

        .stack-trace.visible {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="max-width: 100%; padding: 0;">
        <!-- Header -->
        <div class="log-header">
            <div>
                <h1>
                    <i class="fas fa-terminal"></i>
                    System Logs
                </h1>
                <div style="margin-top: 5px;">
                    <span class="badge">
                        <i class="fas fa-file-alt"></i> {{ count($files) }} files
                    </span>
                    @if($current_file)
                        <span class="badge">
                            <i class="fas fa-file"></i> {{ $current_file }}
                        </span>
                    @endif
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                <div class="dark-mode-toggle" onclick="toggleDarkMode()">
                    <i class="fas fa-moon" style="font-size: 0.8rem;"></i>
                    <div class="toggle-switch" id="darkModeSwitch"></div>
                    <i class="fas fa-sun" style="font-size: 0.8rem;"></i>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-light btn-sm" style="border-radius: 6px; font-size: 0.75rem; padding: 5px 12px;">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row" style="margin: 0 -10px;">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4" style="padding: 0 10px;">
                <div class="sidebar">
                    <h6 style="margin-bottom: 12px; font-weight: 600; font-size: 0.8rem;">
                        <i class="fas fa-folder-open"></i> Files
                    </h6>
                    <div class="list-group">
                        @foreach($folders as $folder)
                            <div class="list-group-item" style="cursor: default; background: rgba(102, 126, 234, 0.05); font-weight: 500;">
                                <i class="fas fa-folder"></i> {{ basename($folder) }}
                            </div>
                            <?php
                            \Rap2hpoutre\LaravelLogViewer\LaravelLogViewer::DirectoryTreeStructure($storage_path, $structure);
                            ?>
                        @endforeach
                        @foreach($files as $file)
                            <a href="?l={{ \Illuminate\Support\Facades\Crypt::encryptString($file) }}" 
                               class="list-group-item @if ($current_file == $file) llv-active @endif">
                                <i class="fas fa-file-code"></i> 
                                <span style="overflow: hidden; text-overflow: ellipsis;">{{ $file }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8" style="padding: 0 10px;">
                <div class="table-container">
                    @if ($logs === null)
                        <div class="alert alert-warning" style="font-size: 0.85rem; border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle"></i>
                            Log file is too large (>50M). Please download it to view.
                        </div>
                    @else
                        <table id="table-log" class="table">
                            <thead>
                                <tr>
                                    <th style="width: 100%;">Log Entry</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $key => $log)
                                    <tr class="log-entry">
                                        <td>
                                            <!-- Card -->
                                            <div class="log-card level-{{ $log['level_class'] }}">
                                                <!-- Meta: Level, Context, Date -->
                                                <div class="log-meta">
                                                    <span class="log-level-badge {{ $log['level_class'] }}">
                                                        <i class="fas fa-{{ $log['level_img'] }}" style="font-size: 0.6rem; margin-right: 4px;"></i>
                                                        {{ $log['level'] }}
                                                    </span>
                                                    
                                                    @if($standardFormat)
                                                        <span class="log-context">
                                                            <i class="fas fa-tag" style="font-size: 0.6rem;"></i>
                                                            {{ $log['context'] }}
                                                        </span>
                                                    @endif
                                                    
                                                    <span class="log-date">
                                                        <i class="far fa-clock"></i>
                                                        {{ $log['date'] }}
                                                    </span>
                                                </div>

                                                <!-- Body: Content + Stack Trace -->
                                                <div class="log-body">
                                                    <!-- Content -->
                                                    <div class="log-text" data-log="{{ base64_encode($log['text']) }}">
                                                        <!-- Will be rendered by JavaScript -->
                                                    </div>

                                                    @if (isset($log['in_file']))
                                                        <div class="log-in-file">
                                                            <i class="fas fa-file"></i> {{ $log['in_file'] }}
                                                        </div>
                                                    @endif

                                                    <!-- Stack Trace -->
                                                    @if ($log['stack'])
                                                        <div class="stack-trace-container">
                                                            <button type="button" class="expand-btn" data-target="stack{{{$key}}}">
                                                                <i class="fas fa-code"></i>
                                                                <span>Stack Trace</span>
                                                                <i class="fas fa-chevron-down"></i>
                                                                <span class="badge-trace">{{ count(explode("\n", trim($log['stack']))) }} lines</span>
                                                            </button>
                                                            <div class="stack-trace" id="stack{{{$key}}}">
                                                                <pre>{{{ trim($log['stack']) }}}</pre>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                    <!-- Actions -->
                    @if($current_file)
                        <div class="action-buttons">
                            <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                               class="btn btn-primary">
                                <i class="fas fa-download"></i> Download
                            </a>
                            <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                               class="btn btn-warning">
                                <i class="fas fa-broom"></i> Clean
                            </a>
                            <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                               class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                            @if(count($files) > 1)
                                <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                                   class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Delete All
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script nonce="{{ $nonce }}" src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script nonce="{{ $nonce }}" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script nonce="{{ $nonce }}" defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script nonce="{{ $nonce }}" type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script nonce="{{ $nonce }}" type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <script nonce="{{ $nonce }}">
        // Dark Mode
        function initDarkMode() {
            const darkMode = localStorage.getItem('darkMode') === 'true';
            const switchEl = document.getElementById('darkModeSwitch');
            if (darkMode) {
                document.body.setAttribute('data-theme', 'dark');
                switchEl.classList.add('active');
            } else {
                document.body.removeAttribute('data-theme');
                switchEl.classList.remove('active');
            }
        }

        function toggleDarkMode() {
            const switchEl = document.getElementById('darkModeSwitch');
            const isDark = switchEl.classList.toggle('active');
            if (isDark) {
                document.body.setAttribute('data-theme', 'dark');
                localStorage.setItem('darkMode', 'true');
            } else {
                document.body.removeAttribute('data-theme');
                localStorage.removeItem('darkMode');
            }
        }

        // Format log content with JSON highlighting
        function formatLogContent(text) {
            if (!text) return '<pre style="margin: 0; font-size: 0.75rem; white-space: pre-wrap; word-wrap: break-word;">No content</pre>';
            
            try {
                // Try to parse as JSON
                const json = JSON.parse(text);
                return `<div class="json-view">${syntaxHighlight(json)}</div>`;
            } catch (e) {
                // Try to find JSON in the text
                const jsonMatch = text.match(/\{[\s\S]*\}/);
                if (jsonMatch) {
                    try {
                        const json = JSON.parse(jsonMatch[0]);
                        const before = text.substring(0, text.indexOf('{'));
                        const after = text.substring(text.indexOf('}') + 1);
                        return `<div style="margin-bottom: 3px; font-size: 0.75rem; word-break: break-word; color: var(--code-text);">${escapeHtml(before)}</div>
                                <div class="json-view">${syntaxHighlight(json)}</div>
                                ${after ? `<div style="margin-top: 3px; font-size: 0.75rem; word-break: break-word; color: var(--code-text);">${escapeHtml(after)}</div>` : ''}`;
                    } catch (e) {
                        return `<pre style="margin: 0; font-size: 0.75rem; white-space: pre-wrap; word-wrap: break-word;">${escapeHtml(text)}</pre>`;
                    }
                }
                return `<pre style="margin: 0; font-size: 0.75rem; white-space: pre-wrap; word-wrap: break-word;">${escapeHtml(text)}</pre>`;
            }
        }

        function syntaxHighlight(json) {
            if (typeof json !== 'object' || json === null) {
                const type = typeof json;
                const cls = type === 'string' ? 'json-string' : 
                           type === 'number' ? 'json-number' : 
                           type === 'boolean' ? 'json-boolean' : 'json-null';
                const val = type === 'string' ? `"${escapeHtml(String(json))}"` : escapeHtml(String(json));
                return `<span class="${cls}">${val}</span>`;
            }

            const isArray = Array.isArray(json);
            let html = `<span class="json-bracket">${isArray ? '[' : '{'}</span>`;
            
            const items = Object.entries(json);
            items.forEach(([key, value], index) => {
                html += `<div style="padding-left: 15px;">`;
                if (!isArray) {
                    html += `<span class="json-key">"${escapeHtml(key)}"</span><span class="json-bracket">: </span>`;
                }
                
                if (typeof value === 'object' && value !== null) {
                    html += syntaxHighlight(value);
                } else if (typeof value === 'string') {
                    html += `<span class="json-string">"${escapeHtml(value)}"</span>`;
                } else if (typeof value === 'number') {
                    html += `<span class="json-number">${value}</span>`;
                } else if (typeof value === 'boolean') {
                    html += `<span class="json-boolean">${value}</span>`;
                } else if (value === null) {
                    html += `<span class="json-null">null</span>`;
                }
                
                html += index < items.length - 1 ? ', ' : '';
                html += `</div>`;
            });
            
            html += `<span class="json-bracket">${isArray ? ']' : '}'}</span>`;
            return html;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Toggle stack trace with pure JavaScript
        function toggleStackTrace(button) {
            const targetId = button.getAttribute('data-target');
            const target = document.getElementById(targetId);
            const icon = button.querySelector('.fa-chevron-down, .fa-chevron-up');
            
            if (!target) return;
            
            if (target.classList.contains('visible')) {
                target.classList.remove('visible');
                button.classList.remove('expanded');
                if (icon) {
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                }
            } else {
                target.classList.add('visible');
                button.classList.add('expanded');
                if (icon) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            }
        }

        // Process all log entries after page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Init dark mode
            initDarkMode();

            // Format all log texts
            document.querySelectorAll('.log-text').forEach(function(el) {
                const encoded = el.getAttribute('data-log');
                if (encoded) {
                    try {
                        const text = atob(encoded);
                        const formatted = formatLogContent(text);
                        el.innerHTML = formatted;
                    } catch (e) {
                        el.innerHTML = '<pre style="margin: 0; font-size: 0.75rem; white-space: pre-wrap; word-wrap: break-word;">Error decoding log content</pre>';
                    }
                }
            });

            // Stack trace toggle with pure JS
            document.querySelectorAll('.expand-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleStackTrace(this);
                });
            });

            // Auto-expand stack trace if contains exception
            document.querySelectorAll('.log-card').forEach(function(card) {
                const stackTrace = card.querySelector('.stack-trace pre');
                if (stackTrace && stackTrace.textContent.includes('Exception')) {
                    const btn = card.querySelector('.expand-btn');
                    if (btn) {
                        // Auto expand
                        const targetId = btn.getAttribute('data-target');
                        const target = document.getElementById(targetId);
                        if (target) {
                            target.classList.add('visible');
                            btn.classList.add('expanded');
                            const icon = btn.querySelector('.fa-chevron-down, .fa-chevron-up');
                            if (icon) {
                                icon.classList.remove('fa-chevron-down');
                                icon.classList.add('fa-chevron-up');
                            }
                        }
                    }
                }
            });

            // Initialize DataTable
            if (typeof $.fn.DataTable !== 'undefined') {
                $('#table-log').DataTable({
                    "order": [[0, 'desc']],
                    "stateSave": true,
                    "stateSaveCallback": function(settings, data) {
                        try {
                            window.localStorage.setItem("datatable", JSON.stringify(data));
                        } catch(e) {}
                    },
                    "stateLoadCallback": function(settings) {
                        try {
                            const data = JSON.parse(window.localStorage.getItem("datatable"));
                            if (data) data.start = 0;
                            return data;
                        } catch(e) {
                            return null;
                        }
                    },
                    "pageLength": 15,
                    "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "All"]],
                    "language": {
                        "search": "<i class='fas fa-search'></i>",
                        "lengthMenu": "Show _MENU_"
                    },
                    "autoWidth": false,
                    "columnDefs": [
                        { "width": "100%", "targets": 0 }
                    ]
                });
            }

            // Confirmation dialogs
            document.querySelectorAll('#delete-log, #clean-log, #delete-all-log').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to proceed with this action?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>