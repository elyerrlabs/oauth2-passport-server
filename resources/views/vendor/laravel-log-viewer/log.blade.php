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
            --bg-primary: #f5f7fa;
            --bg-secondary: #ffffff;
            --text-primary: #2d3748;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.05);
            --log-error: #f56565;
            --log-warning: #ed8936;
            --log-info: #4299e1;
            --log-debug: #a0aec0;
            --log-error-bg: #fff5f5;
            --log-warning-bg: #fffbeb;
            --log-info-bg: #f0f9ff;
            --log-debug-bg: #f7fafc;
            --code-bg: #f7fafc;
            --code-text: #2d3748;
            --trace-bg: #f7fafc;
            --trace-text: #2d3748;
            --trace-border: #e2e8f0;
            --btn-hover: #edf2f7;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }

        [data-theme="dark"] {
            --bg-primary: #1a202c;
            --bg-secondary: #2d3748;
            --text-primary: #e2e8f0;
            --text-secondary: #a0aec0;
            --text-muted: #718096;
            --border-color: #4a5568;
            --card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.3);
            --log-error-bg: #2d1b1b;
            --log-warning-bg: #2d261b;
            --log-info-bg: #1b2d3d;
            --log-debug-bg: #1a202c;
            --code-bg: #1a202c;
            --code-text: #e2e8f0;
            --trace-bg: #1a202c;
            --trace-text: #e2e8f0;
            --trace-border: #4a5568;
            --btn-hover: #4a5568;
            --gradient-start: #5a6fd6;
            --gradient-end: #6a3d92;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            padding: 15px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin: 0;
            line-height: 1.6;
        }

        .container-custom {
            max-width: 100%;
            padding: 0;
        }

        .log-header {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: var(--card-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .log-header h1 {
            margin: 0;
            font-weight: 300;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .log-header h1 i {
            margin-right: 10px;
            opacity: 0.9;
        }

        .log-header .badge {
            font-size: 0.75rem;
            padding: 6px 14px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            font-weight: 400;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .sidebar {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 18px;
            box-shadow: var(--card-shadow);
            height: fit-content;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            position: sticky;
            top: 15px;
            width: 100%;
            border: 1px solid var(--border-color);
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--bg-primary);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gradient-start);
            border-radius: 3px;
        }

        .sidebar-title {
            color: var(--text-secondary);
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .sidebar .list-group-item {
            background: transparent;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 3px;
            color: var(--text-primary);
            transition: all 0.2s ease;
            font-size: 0.85rem;
            word-break: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar .list-group-item:hover {
            background: rgba(102, 126, 234, 0.08);
            padding-left: 16px;
            color: var(--gradient-start);
        }

        .sidebar .list-group-item.llv-active {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white;
            font-weight: 500;
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .sidebar .list-group-item i {
            margin-right: 8px;
            width: 16px;
            text-align: center;
        }

        .table-container {
            background: var(--bg-secondary);
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            width: 100%;
            border: 1px solid var(--border-color);
        }

        .log-card {
            background: var(--bg-primary);
            border-radius: 10px;
            padding: 15px 18px;
            margin-bottom: 12px;
            border-left: 4px solid var(--border-color);
            transition: all 0.25s ease;
            position: relative;
        }

        .log-card:hover {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            transform: translateX(4px);
        }

        [data-theme="dark"] .log-card:hover {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.4);
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

        .log-meta {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 15px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .log-level-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .log-level-badge.error { background: var(--log-error); color: white; }
        .log-level-badge.warning { background: var(--log-warning); color: white; }
        .log-level-badge.info { background: var(--log-info); color: white; }
        .log-level-badge.debug { background: var(--log-debug); color: white; }

        .log-level-badge i {
            margin-right: 5px;
            font-size: 0.6rem;
        }

        .log-context {
            font-size: 0.75rem;
            background: rgba(102, 126, 234, 0.1);
            padding: 3px 12px;
            border-radius: 20px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .log-date {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            margin-left: auto;
            white-space: nowrap;
        }

        .log-body {
            position: relative;
        }

        .log-text {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.8rem;
            line-height: 1.6;
            word-break: break-word;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px 14px;
            background: var(--code-bg);
            color: var(--code-text);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .log-text::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .log-text::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 3px;
        }

        .log-text::-webkit-scrollbar-thumb {
            background: var(--gradient-start);
            border-radius: 3px;
        }

        .log-text pre {
            margin: 0;
            color: var(--code-text);
            background: transparent;
            padding: 0;
            font-family: inherit;
            font-size: 0.8rem;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .log-text-no-content {
            margin: 0;
            font-size: 0.8rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: var(--code-text);
        }

        .log-text-error {
            margin: 0;
            font-size: 0.8rem;
            white-space: pre-wrap;
            word-wrap: break-word;
            color: var(--code-text);
        }

        .json-view {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.75rem;
            padding: 12px 14px;
            background: #1a202c;
            border-radius: 8px;
            color: #e2e8f0;
            overflow-x: auto;
            margin: 5px 0;
            max-height: 250px;
            overflow-y: auto;
            line-height: 1.6;
        }

        .json-key { color: #fc8181; }
        .json-string { color: #68d391; }
        .json-number { color: #b794f4; }
        .json-boolean { color: #63b3ed; }
        .json-null { color: #a0aec0; }
        .json-bracket { color: #e2e8f0; }

        .json-text-before {
            margin-bottom: 4px;
            font-size: 0.8rem;
            word-break: break-word;
            color: var(--code-text);
        }

        .json-text-after {
            margin-top: 4px;
            font-size: 0.8rem;
            word-break: break-word;
            color: var(--code-text);
        }

        .stack-trace-container {
            margin-top: 10px;
        }

        .stack-trace {
            font-family: 'SF Mono', 'Consolas', 'Monaco', monospace;
            font-size: 0.75rem;
            padding: 14px 16px;
            background: var(--trace-bg);
            color: var(--trace-text);
            border-radius: 8px;
            overflow-x: auto;
            max-height: 400px;
            overflow-y: auto;
            line-height: 1.6;
            border: 1px solid var(--trace-border);
            margin-top: 10px;
            display: none;
        }

        .stack-trace.visible {
            display: block;
            animation: slideDown 0.25s ease-out;
        }

        .stack-trace pre {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: inherit;
            font-size: 0.75rem;
            color: var(--trace-text);
            background: transparent;
            padding: 0;
        }

        .stack-trace::-webkit-scrollbar,
        .json-view::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        .stack-trace::-webkit-scrollbar-track,
        .json-view::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .stack-trace::-webkit-scrollbar-thumb,
        .json-view::-webkit-scrollbar-thumb {
            background: var(--gradient-start);
            border-radius: 3px;
        }

        .expand-btn {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 5px 16px;
            font-size: 0.75rem;
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .expand-btn:hover {
            background: var(--btn-hover);
            color: var(--text-primary);
            border-color: var(--gradient-start);
        }

        .expand-btn .badge-trace {
            background: rgba(102, 126, 234, 0.12);
            padding: 1px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .log-in-file {
            margin-top: 8px;
            font-size: 0.7rem;
            color: var(--text-secondary);
            padding: 4px 10px;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 4px;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        .action-buttons .btn {
            border-radius: 8px;
            padding: 6px 18px;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .action-buttons .btn-primary {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            border: none;
            color: white;
        }

        .action-buttons .btn-primary:hover {
            opacity: 0.9;
        }

        .dark-mode-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            user-select: none;
            backdrop-filter: blur(10px);
        }

        .dark-mode-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .toggle-switch {
            width: 40px;
            height: 22px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 11px;
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
            width: 17px;
            height: 17px;
            background: white;
            border-radius: 50%;
            top: 2.5px;
            left: 2.5px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .toggle-switch.active::after {
            left: 20.5px;
        }

        #table-log {
            width: 100% !important;
            font-size: 0.85rem;
        }

        #table-log td {
            padding: 8px 6px;
            vertical-align: top;
            border: none;
        }

        #table-log tr {
            border-bottom: 1px solid var(--border-color);
        }

        #table-log thead th {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white;
            border: none;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
            margin-bottom: 15px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 6px 14px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.85rem;
            margin-left: 8px;
            width: 220px;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--gradient-start);
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 5px 10px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.85rem;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.8rem;
            color: var(--text-secondary);
            padding-top: 12px;
        }

        .dataTables_wrapper .dataTables_paginate {
            font-size: 0.8rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 5px 12px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            background: var(--bg-primary);
            color: var(--text-primary) !important;
            margin: 0 3px;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            color: white !important;
            border-color: transparent;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--btn-hover);
            border-color: var(--gradient-start);
        }

        .row-custom {
            margin: 0 -10px;
        }

        .col-sidebar {
            padding: 0 10px;
        }

        .col-content {
            padding: 0 10px;
        }

        .btn-back {
            border-radius: 8px;
            font-size: 0.8rem;
            padding: 6px 16px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            text-decoration: none;
        }

        .alert-warning-custom {
            font-size: 0.9rem;
            border-radius: 8px;
            padding: 15px 20px;
        }

        .folder-item {
            cursor: default;
            background: rgba(102, 126, 234, 0.05);
            font-weight: 500;
        }

        .log-file-name {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-badge-group {
            margin-top: 6px;
        }

        .level-icon {
            font-size: 0.6rem;
            margin-right: 5px;
        }

        .context-icon {
            font-size: 0.6rem;
        }

        .date-icon {
            margin-right: 4px;
        }

        .expand-icon {
            transition: transform 0.3s ease;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: static;
                max-height: 250px;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .log-header {
                padding: 16px 18px;
                flex-direction: column;
                align-items: stretch;
            }

            .log-header h1 {
                font-size: 1.2rem;
            }

            .log-header .badge {
                font-size: 0.7rem;
                padding: 4px 10px;
            }

            .table-container {
                padding: 12px;
            }

            .log-meta {
                gap: 6px 10px;
            }

            .log-date {
                margin-left: 0;
                width: 100%;
            }

            .log-text {
                font-size: 0.7rem;
                max-height: 150px;
                padding: 8px 12px;
            }

            .json-view, .stack-trace {
                font-size: 0.65rem;
                max-height: 150px;
                padding: 10px 12px;
            }

            .action-buttons .btn {
                font-size: 0.7rem;
                padding: 5px 14px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 150px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .log-level-badge {
                font-size: 0.6rem;
                padding: 2px 8px;
            }

            .log-context {
                font-size: 0.6rem;
                padding: 2px 8px;
            }

            .log-card {
                padding: 12px 14px;
            }

            .dark-mode-toggle {
                font-size: 0.75rem;
                padding: 4px 12px;
            }

            .toggle-switch {
                width: 34px;
                height: 19px;
            }

            .toggle-switch::after {
                width: 14px;
                height: 14px;
                top: 2.5px;
                left: 2.5px;
            }

            .toggle-switch.active::after {
                left: 17.5px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .log-entry {
            animation: fadeIn 0.25s ease-out;
        }

        .log-text {
            transition: all 0.2s ease;
        }
    </style>
</head>
<body>
    <div class="container-fluid container-custom">
        <!-- Header -->
        <div class="log-header">
            <div>
                <h1>
                    <i class="fas fa-terminal"></i>
                    {{ __('System Logs') }}
                </h1>
                <div class="header-badge-group">
                    <span class="badge">
                        <i class="fas fa-file-alt"></i> {{ count($files) }} {{ __('files') }}
                    </span>
                    @if($current_file)
                        <span class="badge">
                            <i class="fas fa-file"></i> {{ $current_file }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="header-actions">
                <div class="dark-mode-toggle" id="darkModeToggle" title="{{ __('Toggle dark mode') }}">
                    <i class="fas fa-moon"></i>
                    <div class="toggle-switch" id="darkModeSwitch"></div>
                    <i class="fas fa-sun"></i>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </div>
        </div>

        <div class="row row-custom">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4 col-sidebar">
                <div class="sidebar">
                    <h6 class="sidebar-title"><i class="fas fa-folder-open"></i> {{ __('Files') }}</h6>
                    <div class="list-group">
                        @foreach($folders as $folder)
                            <div class="list-group-item folder-item">
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
                                <span class="log-file-name">{{ $file }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8 col-content">
                <div class="table-container">
                    @if ($logs === null)
                        <div class="alert alert-warning alert-warning-custom">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ __('Log file is too large (>50M). Please download it to view.') }}
                        </div>
                    @else
                        <table id="table-log" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Log Entry') }}</th>
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
                                                        <i class="fas fa-{{ $log['level_img'] }} level-icon"></i>
                                                        {{ __($log['level']) }}
                                                    </span>
                                                    
                                                    @if($standardFormat)
                                                        <span class="log-context">
                                                            <i class="fas fa-tag context-icon"></i>
                                                            {{ $log['context'] }}
                                                        </span>
                                                    @endif
                                                    
                                                    <span class="log-date">
                                                        <i class="far fa-clock date-icon"></i>
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
                                                                <span>{{ __('Stack Trace') }}</span>
                                                                <i class="fas fa-chevron-down expand-icon"></i>
                                                                <span class="badge-trace">{{ count(explode("\n", trim($log['stack']))) }} {{ __('lines') }}</span>
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
                                <i class="fas fa-download"></i> {{ __('Download') }}
                            </a>
                            <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                               class="btn btn-warning">
                                <i class="fas fa-broom"></i> {{ __('Clean') }}
                            </a>
                            <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encryptString($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                               class="btn btn-danger">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </a>
                            @if(count($files) > 1)
                                <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encryptString($current_folder) : '' }}" 
                                   class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> {{ __('Delete All') }}
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
            if (!text) {
                return '<pre class="log-text-no-content">{{ __("No content") }}</pre>';
            }
            
            try {
                const json = JSON.parse(text);
                return '<div class="json-view">' + syntaxHighlight(json) + '</div>';
            } catch (e) {
                const jsonMatch = text.match(/\{[\s\S]*\}/);
                if (jsonMatch) {
                    try {
                        const json = JSON.parse(jsonMatch[0]);
                        const before = text.substring(0, text.indexOf('{'));
                        const after = text.substring(text.indexOf('}') + 1);
                        return '<div class="json-text-before">' + escapeHtml(before) + '</div>' +
                               '<div class="json-view">' + syntaxHighlight(json) + '</div>' +
                               (after ? '<div class="json-text-after">' + escapeHtml(after) + '</div>' : '');
                    } catch (e) {
                        return '<pre class="log-text-error">' + escapeHtml(text) + '</pre>';
                    }
                }
                return '<pre class="log-text-error">' + escapeHtml(text) + '</pre>';
            }
        }

        function syntaxHighlight(json) {
            if (typeof json !== 'object' || json === null) {
                const type = typeof json;
                const cls = type === 'string' ? 'json-string' : 
                           type === 'number' ? 'json-number' : 
                           type === 'boolean' ? 'json-boolean' : 'json-null';
                const val = type === 'string' ? '"' + escapeHtml(String(json)) + '"' : escapeHtml(String(json));
                return '<span class="' + cls + '">' + val + '</span>';
            }

            const isArray = Array.isArray(json);
            let html = '<span class="json-bracket">' + (isArray ? '[' : '{') + '</span>';
            
            const items = Object.entries(json);
            items.forEach(function([key, value], index) {
                html += '<div style="padding-left: 18px;">';
                if (!isArray) {
                    html += '<span class="json-key">"' + escapeHtml(key) + '"</span><span class="json-bracket">: </span>';
                }
                
                if (typeof value === 'object' && value !== null) {
                    html += syntaxHighlight(value);
                } else if (typeof value === 'string') {
                    html += '<span class="json-string">"' + escapeHtml(value) + '"</span>';
                } else if (typeof value === 'number') {
                    html += '<span class="json-number">' + value + '</span>';
                } else if (typeof value === 'boolean') {
                    html += '<span class="json-boolean">' + value + '</span>';
                } else if (value === null) {
                    html += '<span class="json-null">null</span>';
                }
                
                html += index < items.length - 1 ? ', ' : '';
                html += '</div>';
            });
            
            html += '<span class="json-bracket">' + (isArray ? ']' : '}') + '</span>';
            return html;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function toggleStackTrace(button) {
            const targetId = button.getAttribute('data-target');
            const target = document.getElementById(targetId);
            const icon = button.querySelector('.expand-icon');
            
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

        document.addEventListener('DOMContentLoaded', function() {
            // Init dark mode
            initDarkMode();

            // Dark mode toggle
            document.getElementById('darkModeToggle').addEventListener('click', toggleDarkMode);

            // Format all log texts
            document.querySelectorAll('.log-text').forEach(function(el) {
                const encoded = el.getAttribute('data-log');
                if (encoded) {
                    try {
                        const text = atob(encoded);
                        const formatted = formatLogContent(text);
                        el.innerHTML = formatted;
                    } catch (e) {
                        el.innerHTML = '<pre class="log-text-error">{{ __("Error decoding log content") }}</pre>';
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
                        const targetId = btn.getAttribute('data-target');
                        const target = document.getElementById(targetId);
                        if (target) {
                            target.classList.add('visible');
                            btn.classList.add('expanded');
                            const icon = btn.querySelector('.expand-icon');
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
                    "lengthMenu": [[10, 15, 25, 50, -1], [10, 15, 25, 50, "{{ __('All') }}"]],
                    "language": {
                        "search": "<i class='fas fa-search'></i>",
                        "lengthMenu": "{{ __('Show') }} _MENU_",
                        "info": "{{ __('Showing') }} _START_ {{ __('to') }} _END_ {{ __('of') }} _TOTAL_ {{ __('entries') }}",
                        "infoEmpty": "{{ __('No entries to show') }}",
                        "emptyTable": "{{ __('No log entries found') }}"
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
                    if (!confirm('{{ __("Are you sure you want to proceed with this action?") }}')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>