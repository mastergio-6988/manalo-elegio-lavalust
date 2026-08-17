<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student History</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            background: #07131f;
            color: #eff7ff;
            font-family: Arial, sans-serif
        }

        .wrap {
            width: min(850px, calc(100% - 32px));
            margin: auto;
            padding: 34px 0
        }

        .card {
            padding: 38px;
            border: 1px solid #29445d;
            border-radius: 24px;
            background: #0c2031
        }

        a {
            color: #67dec7
        }

        .event {
            padding: 18px 0;
            border-bottom: 1px solid #29445d
        }

        .event:last-child {
            border: 0
        }

        .date {
            color: #67dec7;
            font-size: .85rem;
            font-weight: bold
        }

        .detail {
            color: #b4c6d8;
            line-height: 1.6
        }
    </style>
</head>

<body>
    <main class="wrap">
        <p><a href="/student">← Student Hub</a></p>
        <section class="card">
            <p class="date">STUDENT ACTIVITY</p>
            <h1>Student History</h1>
            <div class="event">
                <div class="date">Profile</div>
                <p class="detail">Student profile and middleware-protected access are available from the Student Hub.</p>
            </div>
            <div class="event">
                <div class="date">Laboratory Activity No. 3</div>
                <p class="detail">LavaLust routes, controllers, views, and middleware are configured for this project.</p>
            </div>
        </section>
    </main>
</body>

</html>