<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faroise — Offline</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0f172a; color: #e2e8f0; font-family: system-ui, sans-serif;
               display: flex; flex-direction: column; align-items: center; justify-content: center;
               min-height: 100vh; gap: 1.5rem; text-align: center; padding: 2rem; }
        .icon { font-size: 4rem; }
        h1 { font-size: 1.75rem; font-weight: 700; color: #22d3ee; }
        p  { color: #94a3b8; max-width: 360px; line-height: 1.6; }
        a  { display: inline-block; margin-top: .5rem; padding: .6rem 1.4rem;
             background: #0e7490; color: #fff; border-radius: .75rem; text-decoration: none;
             font-weight: 600; transition: background .2s; }
        a:hover { background: #0891b2; }
    </style>
</head>
<body>
    <div class="icon">🎵</div>
    <h1>You're offline</h1>
    <p>Faroise needs a connection to stream sounds. Please check your internet and try again.</p>
    <a href="/">Try again</a>
</body>
</html>
