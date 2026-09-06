<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Gio's Student Hub | LavaLust Lab</title>
    <style>
        :root {
            --ink: #eff7ff;
            --muted: #b4c6d8;
            --accent: #67dec7;
            --line: #29445d
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, #1c4969, #07131f 52%);
            color: var(--ink);
            font-family: Arial, sans-serif
        }

        canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: .9
        }

        .wrap {
            position: relative;
            z-index: 1;
            width: min(950px, calc(100% - 32px));
            margin: auto;
            padding: 32px 0 48px
        }

        nav {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin-bottom: 40px
        }

        a {
            color: var(--ink);
            text-underline-offset: 4px
        }

        .brand {
            margin-right: auto;
            color: var(--accent);
            font-weight: bold;
            text-decoration: none
        }

        .hero {
            padding: 42px;
            border: 1px solid var(--line);
            border-radius: 24px;
            background: #0c2031
        }

        .eyebrow,
        .label {
            color: var(--accent);
            font-size: .78rem;
            font-weight: bold;
            letter-spacing: .1em;
            text-transform: uppercase
        }

        h1 {
            font-size: clamp(2.7rem, 8vw, 5rem);
            line-height: .95;
            margin: 16px 0
        }

        p {
            color: var(--muted);
            line-height: 1.65
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin: 30px 0
        }

        .item {
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: #102a3e
        }

        .value {
            margin-top: 7px;
            font-weight: bold;
            overflow-wrap: anywhere;
            word-break: break-word
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 28px
        }

        .button {
            display: inline-block;
            padding: 12px 17px;
            border-radius: 10px;
            background: var(--accent);
            color: #06201d;
            font-weight: bold;
            text-decoration: none
        }

        .button.alt {
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--line)
        }

        .notice {
            padding: 14px;
            border-left: 3px solid #ffc36a;
            background: #3c321f;
            color: #ffe1a9;
            border-radius: 8px
        }
    </style>
    <style>
        .letter-glow {
            display: inline-block;
            cursor: default;
            transition: color .14s ease, text-shadow .14s ease, transform .14s ease
        }

        .letter-glow:hover {
            color: #ff9eec;
            text-shadow: 0 0 6px currentColor, 0 0 17px currentColor, 0 0 31px #39f8ff;
            transform: translateY(-2px) scale(1.13);
            animation: rainbow-flash .52s linear infinite
        }

        .letter-clicked {
            animation: letter-pop .58s ease-out !important
        }

        @keyframes rainbow-flash {
            to {
                filter: hue-rotate(360deg)
            }
        }

        @keyframes letter-pop {
            50% {
                color: #fff;
                transform: scale(1.52);
                text-shadow: 0 0 8px #fff, 0 0 25px #ff4fd8, 0 0 44px #39f8ff
            }
        }

        @media(prefers-reduced-motion:reduce) {

            .letter-glow,
            .letter-glow:hover {
                animation: none;
                transform: none
            }
        }

        <style>.item {
            position: relative;
            isolation: isolate;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease
        }

        .item>* {
            position: relative;
            z-index: 1
        }

        .item:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 18px rgba(103, 222, 199, .22)
        }

        .item.detail-active {
            border-color: transparent;
            transform: translateY(-4px) scale(1.025);
            box-shadow: 0 0 18px #ff46d6, 0 0 38px #47f6ff, 0 0 58px #a16cff
        }

        .item.detail-active::before,
        .item.detail-active::after {
            content: "";
            position: absolute;
            border-radius: inherit;
            pointer-events: none;
            background: conic-gradient(#ff46d6, #ffdf4a, #48ffbd, #45c9ff, #9666ff, #ff46d6);
            animation: rainbow-orbit 1.25s linear infinite
        }

        .item.detail-active::before {
            inset: -3px;
            z-index: -1
        }

        .item.detail-active::after {
            inset: -10px;
            z-index: -2;
            filter: blur(13px);
            opacity: .8
        }

        h1 {
            display: inline-block;
            cursor: pointer;
            transition: color .2s ease, text-shadow .2s ease
        }

        h1.detail-active {
            color: #fff;
            text-shadow: 0 0 8px #fff, 0 0 23px #ff4fd8, 0 0 44px #36eaff;
            animation: heading-rainbow 1.1s linear infinite
        }

        @keyframes rainbow-orbit {
            to {
                transform: rotate(1turn)
            }
        }

        @keyframes heading-rainbow {
            to {
                filter: hue-rotate(360deg)
            }
        }

        @media(prefers-reduced-motion:reduce) {

            .item,
            .item:hover,
            .item.detail-active {
                transform: none
            }

            .item.detail-active::before,
            .item.detail-active::after,
            h1.detail-active {
                animation: none
            }
        }

        @media(max-width:700px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:480px) {
            .grid {
                grid-template-columns: 1fr
            }
        }
    </style>
    <script src="<?= site_url('student-background.js') ?>" defer></script>
</head>

<body><canvas id="network-bg" aria-hidden="true"></canvas>
    <main class="wrap">
        <nav><a class="brand" href="<?= site_url('student') ?>">GIO / STUDENT HUB</a><a href="<?= site_url('student') ?>">Home</a><a href="<?= site_url('student/profile') ?>">Student Profile</a></nav>
        <section class="hero">
            <div class="eyebrow">LavaLust Laboratory Activity No. 3</div>
            <h1>Student<br>Information</h1>
            <p><?= htmlspecialchars($student['profile_description'], ENT_QUOTES, 'UTF-8') ?></p><?php if ($notice === 'profile-locked'): ?><p class="notice">Student profile is protected. Grant lab access first, then the middleware will allow the request.</p><?php endif; ?><?php if ($notice === 'access-revoked'): ?><p class="notice">Lab access was revoked. Try the profile route again to test the middleware redirect.</p><?php endif; ?><div class="grid"><?php foreach (['student_id' => 'Student ID', 'name' => 'Student Name', 'course' => 'Course', 'year' => 'Year Level', 'section' => 'Section', 'email' => 'Email'] as $key => $label): ?><div class="item">
                        <div class="label"><?= $label ?></div>
                        <div class="value"><?= htmlspecialchars($student[$key], ENT_QUOTES, 'UTF-8') ?></div>
                    </div><?php endforeach; ?></div>
            <div class="actions"><?php if ($has_access): ?><a class="button" href="<?= site_url('student/profile') ?>">Open protected profile</a><a class="button alt" href="<?= site_url('student/revoke') ?>">Revoke access (test)</a><?php else: ?><a class="button" href="<?= site_url('student/access') ?>">Grant profile access</a><a class="button alt" href="<?= site_url('student/profile') ?>">Try protected profile</a><?php endif; ?></div>
        </section>
    </main>
</body>

</html>