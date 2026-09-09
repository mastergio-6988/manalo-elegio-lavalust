<?php
$userCount = is_countable($users) ? count($users) : 0;
$field = static function ($user, string $name): string {
    if (is_array($user)) {
        return (string) ($user[$name] ?? '');
    }

    return is_object($user) ? (string) ($user->{$name} ?? '') : '';
};
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        :root {
            --teams-purple: #5b5fc7;
            --teams-purple-dark: #464775;
            --teams-purple-soft: #edebff;
            --ink: #242424;
            --muted: #616161;
            --line: #e1dfdd;
            --canvas: #f5f5f5;
            --surface: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-width: 320px;
            background: var(--canvas);
            color: var(--ink);
            font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
            font-size: 14px;
        }

        button, input { font: inherit; }

        .app-shell {
            display: grid;
            grid-template-columns: 68px 244px minmax(0, 1fr);
            min-height: 100vh;
        }

        /* Slim application rail, inspired by the familiar Teams workspace layout. */
        .app-rail {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 13px 0;
            background: var(--teams-purple-dark);
            color: #fff;
        }

        .app-mark {
            display: grid;
            width: 34px;
            height: 34px;
            place-items: center;
            margin-bottom: 13px;
            border-radius: 10px;
            background: #fff;
            color: var(--teams-purple);
            font-size: 16px;
            font-weight: 800;
            box-shadow: 0 2px 7px rgba(0, 0, 0, .17);
        }

        .rail-button {
            position: relative;
            display: grid;
            width: 52px;
            height: 50px;
            place-items: center;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: rgba(255, 255, 255, .78);
            cursor: pointer;
        }

        .rail-button svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.8; }
        .rail-button:hover { background: rgba(255, 255, 255, .12); color: #fff; }
        .rail-button.active { color: #fff; background: rgba(255, 255, 255, .16); }
        .rail-button.active::before {
            position: absolute;
            left: -8px;
            width: 3px;
            height: 28px;
            border-radius: 3px;
            background: #fff;
            content: "";
        }

        .rail-spacer { flex: 1; }
        .profile-dot { width: 32px; height: 32px; border-radius: 50%; background: #e7a63a; color: #2d2107; display: grid; place-items: center; font-weight: 700; }

        .side-panel {
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #fff;
            border-right: 1px solid var(--line);
        }

        .workspace-heading {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 19px 18px 18px;
            border-bottom: 1px solid #edebe9;
            font-weight: 600;
        }

        .workspace-icon { display: grid; width: 29px; height: 29px; place-items: center; border-radius: 7px; background: var(--teams-purple); color: #fff; font-size: 12px; font-weight: 700; }
        .workspace-heading small { display: block; margin-top: 2px; color: var(--muted); font-size: 11px; font-weight: 400; }

        .side-content { padding: 20px 10px; }
        .side-label { margin: 0 8px 9px; color: #616161; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; }
        .side-link { display: flex; align-items: center; gap: 11px; min-height: 40px; margin: 2px 0; padding: 0 10px; border-radius: 5px; color: #424242; text-decoration: none; }
        .side-link svg { width: 19px; height: 19px; fill: none; stroke: currentColor; stroke-width: 1.8; }
        .side-link:hover { background: #f5f5f5; }
        .side-link.selected { background: var(--teams-purple-soft); color: #3d3f8f; font-weight: 600; }
        .side-link.selected svg { stroke: var(--teams-purple); }
        .side-link .count { margin-left: auto; padding: 2px 7px; border-radius: 10px; background: #e8e8f8; color: #4b4d9e; font-size: 11px; }

        .side-footer { margin-top: auto; padding: 16px; border-top: 1px solid #edebe9; color: var(--muted); font-size: 12px; line-height: 1.45; }
        .connected { display: flex; align-items: center; gap: 7px; margin-bottom: 3px; color: #237b4b; font-weight: 600; }
        .connected::before { width: 7px; height: 7px; border-radius: 50%; background: #36a269; content: ""; }

        .main-area { min-width: 0; }
        .topbar { display: flex; align-items: center; height: 64px; gap: 20px; padding: 0 30px; background: var(--teams-purple); color: #fff; box-shadow: 0 1px 2px rgba(0, 0, 0, .18); }
        .mobile-title { display: none; font-weight: 600; white-space: nowrap; }
        .search { display: flex; align-items: center; width: min(500px, 55vw); height: 34px; margin: 0 auto; padding: 0 11px; border: 1px solid rgba(255,255,255,.18); border-radius: 5px; background: rgba(24, 25, 67, .28); color: rgba(255,255,255,.84); }
        .search svg { flex: 0 0 auto; width: 17px; height: 17px; margin-right: 8px; fill: none; stroke: currentColor; stroke-width: 2; }
        .search input { width: 100%; min-width: 0; border: 0; outline: 0; background: transparent; color: #fff; }
        .search input::placeholder { color: rgba(255,255,255,.74); }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .topbar-icon { display: grid; width: 31px; height: 31px; place-items: center; border: 0; border-radius: 4px; background: transparent; color: #fff; cursor: pointer; }
        .topbar-icon:hover { background: rgba(255,255,255,.13); }
        .topbar-icon svg { width: 19px; height: 19px; fill: none; stroke: currentColor; stroke-width: 1.8; }

        .content { max-width: 1320px; margin: 0 auto; padding: 29px 42px 46px; }
        .breadcrumb { display: flex; align-items: center; gap: 7px; margin-bottom: 19px; color: #605e5c; font-size: 13px; }
        .breadcrumb span:last-child { color: #323130; font-weight: 600; }
        .breadcrumb svg { width: 14px; height: 14px; stroke: #8a8886; stroke-width: 1.8; fill: none; }

        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; margin-bottom: 26px; }
        h1 { margin: 0; font-size: 28px; font-weight: 700; letter-spacing: -.45px; }
        .page-heading p { margin: 6px 0 0; color: var(--muted); font-size: 14px; }
        .heading-actions { display: flex; gap: 9px; }
        .button { display: inline-flex; align-items: center; justify-content: center; gap: 7px; min-height: 34px; padding: 0 13px; border: 1px solid #d1d1d1; border-radius: 4px; background: #fff; color: #323130; font-weight: 600; cursor: pointer; }
        .button:hover { background: #f5f5f5; }
        .button.primary { border-color: var(--teams-purple); background: var(--teams-purple); color: #fff; }
        .button.primary:hover { background: #4f52b4; }
        .button svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; }

        .insights { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 23px; }
        .insight { display: flex; align-items: center; min-height: 91px; padding: 16px 18px; border: 1px solid #e5e5e5; border-radius: 8px; background: var(--surface); box-shadow: 0 1px 2px rgba(0,0,0,.03); }
        .insight-icon { display: grid; width: 42px; height: 42px; flex: 0 0 auto; place-items: center; margin-right: 13px; border-radius: 50%; background: #edebff; color: var(--teams-purple); }
        .insight-icon.green { background: #e7f6ed; color: #237b4b; }
        .insight-icon.blue { background: #e8f3fd; color: #276aab; }
        .insight-icon svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 1.8; }
        .insight-value { display: block; font-size: 22px; font-weight: 700; line-height: 1.1; }
        .insight-label { display: block; margin-top: 4px; color: var(--muted); font-size: 12px; }

        .directory-card { overflow: hidden; border: 1px solid #e1dfdd; border-radius: 8px; background: #fff; box-shadow: 0 2px 7px rgba(0,0,0,.05); }
        .directory-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 12px; min-height: 61px; padding: 0 20px; border-bottom: 1px solid #edebe9; }
        .directory-title { font-size: 16px; font-weight: 650; }
        .directory-title span { margin-left: 7px; color: #616161; font-size: 12px; font-weight: 400; }
        .toolbar-actions { display: flex; gap: 3px; }
        .toolbar-button { display: inline-flex; align-items: center; gap: 6px; min-height: 32px; padding: 0 9px; border: 0; border-radius: 4px; background: transparent; color: #424242; cursor: pointer; }
        .toolbar-button:hover { background: #f3f2f1; }
        .toolbar-button svg { width: 17px; height: 17px; fill: none; stroke: currentColor; stroke-width: 1.8; }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; min-width: 735px; border-collapse: collapse; text-align: left; }
        th { height: 42px; padding: 0 20px; background: #faf9f8; border-bottom: 1px solid #e1dfdd; color: #605e5c; font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
        td { padding: 13px 20px; border-bottom: 1px solid #edebe9; color: #323130; vertical-align: middle; }
        tbody tr { transition: background .15s ease; }
        tbody tr:hover { background: #f8f8fc; }
        tbody tr:last-child td { border-bottom: 0; }
        .id-cell { width: 70px; color: #605e5c; font-size: 12px; }
        .person { display: flex; align-items: center; gap: 10px; min-width: 190px; }
        .avatar { display: grid; width: 34px; height: 34px; flex: 0 0 auto; place-items: center; border-radius: 50%; background: #5965b2; color: #fff; font-size: 12px; font-weight: 700; }
        tbody tr:nth-child(2n) .avatar { background: #a95486; }
        tbody tr:nth-child(3n) .avatar { background: #2c8a86; }
        tbody tr:nth-child(4n) .avatar { background: #997342; }
        .person-name { display: block; font-weight: 600; }
        .person-detail { display: block; margin-top: 2px; color: #757575; font-size: 12px; }
        .email-cell { color: #4f52b4; }
        .username { display: inline-block; padding: 4px 8px; border-radius: 4px; background: #f3f2f1; color: #484644; font-family: Consolas, "Courier New", monospace; font-size: 12px; }
        .row-menu { display: grid; width: 29px; height: 29px; place-items: center; margin-left: auto; border: 0; border-radius: 4px; background: transparent; color: #605e5c; cursor: pointer; }
        .row-menu:hover { background: #edebff; color: #4f52b4; }
        .row-menu svg { width: 18px; height: 18px; fill: currentColor; }
        .empty-state { padding: 45px 20px; color: #616161; text-align: center; }
        .no-results[hidden] { display: none; }
        .visually-hidden { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }

        @media (max-width: 920px) {
            .app-shell { grid-template-columns: 62px minmax(0, 1fr); }
            .side-panel { display: none; }
            .content { padding: 27px 28px 42px; }
            .insights { gap: 10px; }
        }

        @media (max-width: 620px) {
            .app-shell { grid-template-columns: 52px minmax(0, 1fr); }
            .app-rail { gap: 4px; padding-top: 10px; }
            .app-mark { margin-bottom: 8px; }
            .rail-button { width: 44px; height: 43px; }
            .topbar { height: 56px; gap: 8px; padding: 0 12px; }
            .mobile-title { display: block; }
            .search { width: auto; flex: 1; margin: 0; }
            .topbar-actions { display: none; }
            .content { padding: 22px 16px 35px; }
            .breadcrumb { margin-bottom: 14px; }
            .page-heading { display: block; margin-bottom: 20px; }
            h1 { font-size: 25px; }
            .heading-actions { margin-top: 16px; }
            .heading-actions .button { flex: 1; }
            .insights { grid-template-columns: 1fr; gap: 9px; }
            .insight { min-height: 70px; }
            .directory-toolbar { padding: 0 13px; }
            .toolbar-button span { display: none; }
            th, td { padding-left: 14px; padding-right: 14px; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="app-rail" aria-label="Application navigation">
            <div class="app-mark" aria-label="User Management">U</div>
            <button class="rail-button" type="button" aria-label="Activity">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v18M3 12h18"/></svg>
            </button>
            <button class="rail-button" type="button" aria-label="Chat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3h11A2.5 2.5 0 0 1 20 5.5v7a2.5 2.5 0 0 1-2.5 2.5H11l-4.5 4v-4H6.5A2.5 2.5 0 0 1 4 12.5z"/></svg>
            </button>
            <button class="rail-button" type="button" aria-label="Teams">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 8h8v11H8zM5 6.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm14.5 2a2 2 0 1 0 0-4 2 2 0 0 0 0 4ZM2 17v-4a3 3 0 0 1 5-2.24M17 17v-3a3 3 0 0 1 4-2.24"/></svg>
            </button>
            <button class="rail-button active" type="button" aria-label="Users" aria-current="page">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3.5 20v-1a5.5 5.5 0 0 1 11 0v1M16 4.5a3 3 0 0 1 0 5.7M18.5 14a4.5 4.5 0 0 1 2 3.75V20"/></svg>
            </button>
            <button class="rail-button" type="button" aria-label="Calendar">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4m8-4v4M4 10h16"/></svg>
            </button>
            <div class="rail-spacer"></div>
            <button class="rail-button" type="button" aria-label="Settings">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1 1.7-2 1.2-1.3-1a7.5 7.5 0 0 1-1.5.6l-.5 1.6h-2.4l-.5-1.6a7.5 7.5 0 0 1-1.5-.6l-1.3 1-2-1.2.1-1.7a7.6 7.6 0 0 1-.8-1.3L4 13v-2l1.8-.7a7.6 7.6 0 0 1 .8-1.3l-.1-1.7 2-1.2 1.3 1a7.5 7.5 0 0 1 1.5-.6l.5-1.6h2.4l.5 1.6a7.5 7.5 0 0 1 1.5.6l1.3-1 2 1.2-.1 1.7a7.6 7.6 0 0 1 .8 1.3L22 11v2l-1.8.7a7.6 7.6 0 0 1-.8 1.3Z"/></svg>
            </button>
            <div class="profile-dot" title="Administrator">AD</div>
        </aside>

        <aside class="side-panel" aria-label="Workspace navigation">
            <div class="workspace-heading">
                <span class="workspace-icon">UM</span>
                <span>People workspace<small>Organization directory</small></span>
            </div>
            <nav class="side-content">
                <p class="side-label">Manage</p>
                <a class="side-link" href="#directory">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12 12 4l9 8v8H3zM9 20v-6h6v6"/></svg>
                    Overview
                </a>
                <a class="side-link selected" href="#directory" aria-current="page">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3.5 20v-1a5.5 5.5 0 0 1 11 0v1M16 4.5a3 3 0 0 1 0 5.7M18.5 14a4.5 4.5 0 0 1 2 3.75V20"/></svg>
                    Users <span class="count"><?= $userCount ?></span>
                </a>
                <a class="side-link" href="#directory">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm8 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM2.5 20v-2a5.5 5.5 0 0 1 11 0v2m1 0v-2a5.5 5.5 0 0 0-2.4-4.55M15 13.45A5.5 5.5 0 0 1 21.5 18v2"/></svg>
                    Groups
                </a>
                <a class="side-link" href="#directory">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8M8 11h8M8 15h5"/></svg>
                    Directory settings
                </a>
            </nav>
            <div class="side-footer">
                <div class="connected">Database connected</div>
                <div>Source: mydb.users</div>
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <div class="mobile-title">Users</div>
                <label class="search" aria-label="Search directory">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.3"/><path d="m16 16 4.2 4.2"/></svg>
                    <input id="user-search" type="search" placeholder="Search people" autocomplete="off">
                </label>
                <div class="topbar-actions">
                    <button class="topbar-icon" type="button" aria-label="Settings">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19 12h2m-18 0h2m7-7V3m0 18v-2m5-12.1 1.4-1.4M5.6 18.4 7 17m0-10.1L5.6 5.5m12.8 12.9L17 17"/></svg>
                    </button>
                    <button class="topbar-icon" type="button" aria-label="Help">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.6 9a2.5 2.5 0 1 1 4.35 1.67c-.8.85-1.95 1.34-1.95 2.83M12 17h.01"/></svg>
                    </button>
                </div>
            </header>

            <section class="content">
                <div class="breadcrumb" aria-label="Breadcrumb">
                    <span>People workspace</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                    <span>Users</span>
                </div>

                <div class="page-heading">
                    <div>
                        <h1>User management</h1>
                        <p>View and manage people in your organization directory.</p>
                    </div>
                    <div class="heading-actions">
                        <button class="button" type="button">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m0 0-4-4m4 4 4-4M5 20h14"/></svg>
                            Export
                        </button>
                        <button class="button primary" type="button">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                            Add user
                        </button>
                    </div>
                </div>

                <div class="insights" aria-label="Directory summary">
                    <div class="insight">
                        <span class="insight-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3.5 20v-1a5.5 5.5 0 0 1 11 0v1M16 4.5a3 3 0 0 1 0 5.7M18.5 14a4.5 4.5 0 0 1 2 3.75V20"/></svg></span>
                        <span><strong class="insight-value"><?= $userCount ?></strong><span class="insight-label">Directory members</span></span>
                    </div>
                    <div class="insight">
                        <span class="insight-icon green"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4.2 4.2L19 6.5"/></svg></span>
                        <span><strong class="insight-value">Live</strong><span class="insight-label">Database connection</span></span>
                    </div>
                    <div class="insight">
                        <span class="insight-icon blue"><svg viewBox="0 0 24 24" aria-hidden="true"><ellipse cx="12" cy="5.5" rx="7" ry="2.5"/><path d="M5 5.5v6c0 1.38 3.13 2.5 7 2.5s7-1.12 7-2.5v-6M5 11.5v6C5 18.88 8.13 20 12 20s7-1.12 7-2.5v-6"/></svg></span>
                        <span><strong class="insight-value">mydb</strong><span class="insight-label">Connected data source</span></span>
                    </div>
                </div>

                <section class="directory-card" id="directory" aria-labelledby="directory-heading">
                    <div class="directory-toolbar">
                        <div class="directory-title" id="directory-heading">All users <span><?= $userCount ?> total</span></div>
                        <div class="toolbar-actions">
                            <button class="toolbar-button" type="button" aria-label="Filter users">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16l-6.2 7.1v5.2L10.2 19v-6.9z"/></svg><span>Filter</span>
                            </button>
                            <button class="toolbar-button" type="button" aria-label="Refresh users">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 12a8 8 0 1 1-2.34-5.66L20 8.67M20 4v4.67h-4.67"/></svg><span>Refresh</span>
                            </button>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Username</th>
                                    <th scope="col"><span class="visually-hidden">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <?php foreach ($users as $user): ?>
                                    <?php
                                    $id = $field($user, 'id');
                                    $firstName = $field($user, 'firstname');
                                    $lastName = $field($user, 'lastname');
                                    $email = $field($user, 'email');
                                    $username = $field($user, 'username');
                                    $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                    ?>
                                    <tr data-search="<?= $escape($firstName . ' ' . $lastName . ' ' . $email . ' ' . $username) ?>">
                                        <td class="id-cell"><?= $escape($id) ?></td>
                                        <td>
                                            <div class="person">
                                                <span class="avatar" aria-hidden="true"><?= $escape($initials ?: '?') ?></span>
                                                <span><span class="person-name"><?= $escape(trim($firstName . ' ' . $lastName)) ?></span><span class="person-detail">Directory user</span></span>
                                            </div>
                                        </td>
                                        <td class="email-cell"><?= $escape($email) ?></td>
                                        <td><span class="username">@<?= $escape($username) ?></span></td>
                                        <td><button class="row-menu" type="button" aria-label="More options for <?= $escape(trim($firstName . ' ' . $lastName)) ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="5" cy="12" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="19" cy="12" r="1.7"/></svg></button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <p class="empty-state no-results" id="no-results" hidden>No people match your search.</p>
                    </div>
                </section>
            </section>
        </main>
    </div>

    <script>
        const searchInput = document.getElementById('user-search');
        const userRows = Array.from(document.querySelectorAll('#users-table-body tr'));
        const noResults = document.getElementById('no-results');

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim().toLowerCase();
            let visible = 0;
            userRows.forEach((row) => {
                const match = row.dataset.search.toLowerCase().includes(query);
                row.hidden = !match;
                if (match) visible += 1;
            });
            noResults.hidden = visible !== 0;
        });
    </script>
</body>
</html>
