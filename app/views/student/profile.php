<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Protected Profile | Gio's Student Hub</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #07131f;
            color: #eff7ff
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
            width: min(800px, calc(100% - 32px));
            margin: auto;
            padding: 32px 0 48px
        }

        a {
            color: #67dec7;
            text-underline-offset: 4px
        }

        nav {
            display: flex;
            gap: 18px;
            margin-bottom: 38px
        }

        .card {
            padding: 38px;
            border: 1px solid #29445d;
            border-radius: 24px;
            background: #0c2031
        }

        .badge {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 999px;
            background: #123f39;
            color: #8df3df;
            font-weight: bold;
            font-size: .78rem;
            letter-spacing: .07em
        }

        .lead,
        .foot {
            color: #b4c6d8;
            line-height: 1.65
        }

        .details {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 14px 20px;
            margin: 30px 0
        }

        .details dt {
            color: #67dec7;
            font-weight: bold
        }

        .details dd {
            margin: 0;
            color: #e3edf6;
            overflow-wrap: anywhere;
            word-break: break-word
        }

        .foot {
            border-top: 1px solid #29445d;
            padding-top: 20px;
            font-size: .9rem
        }

        @media(max-width:520px) {
            .details {
                grid-template-columns: 1fr;
                gap: 4px
            }

            .details dd {
                margin-bottom: 12px
            }
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
    </style>
    <script src="<?= site_url('student-background.js') ?>" defer></script>
</head>

<body><canvas id="network-bg" aria-hidden="true"></canvas>
    <main class="wrap">
        <nav><a href="<?= site_url('student') ?>">Home</a><a href="<?= site_url('student/revoke') ?>">Revoke access</a></nav>
        <section class="card"><span class="badge">MIDDLEWARE ACCESS GRANTED</span>
            <h1><?= htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="lead">This page was reached only after <code>StudentMiddleware</code> confirmed the active session permission.</p>
            <dl class="details">
                <dt>Student ID</dt>
                <dd><?= htmlspecialchars($student['student_id'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Course</dt>
                <dd><?= htmlspecialchars($student['course'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Year & Section</dt>
                <dd><?= htmlspecialchars($student['year'] . ' — ' . $student['section'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Email</dt>
                <dd><?= htmlspecialchars($student['email'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Address</dt>
                <dd><?= htmlspecialchars($student['address'], ENT_QUOTES, 'UTF-8') ?></dd>
                <dt>Skills</dt>
                <dd><?= htmlspecialchars($student['skills'], ENT_QUOTES, 'UTF-8') ?></dd>
            </dl>
            <p class="foot">Access granted in this browser session: <?= htmlspecialchars($access_granted_at ?: 'just now', ENT_QUOTES, 'UTF-8') ?></p>
        </section>
    </main>
</body>

</html>