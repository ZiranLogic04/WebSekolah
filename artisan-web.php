<?php
// =========================================
// Artisan Web Runner (for shared hosting)
// DELETE THIS FILE AFTER USE !!!
// =========================================

chdir(_DIR_);

function runCommand($command)
{
    echo "<h3> php artisan $command </h3>";

    $output = [];
    $return_var = 0;

    exec("php artisan $command 2>&1", $output, $return_var);

    echo "<pre>";
    echo implode("\n", $output);
    echo "</pre>";
    echo "<hr>";
}

$command = $_GET['run'] ?? null;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Artisan Web Runner</title>
    <style>
        body { font-family: Arial; padding:20px; }
        button { padding:10px; margin:5px; }
    </style>
</head>
<body>
<h1>Laravel Artisan Web Runner</h1>
<p><b>Hapus file ini setelah selesai!</b></p>

<?php
// Jika ada ?run=
if ($command) {
    runCommand($command);
}
?>

<h2>Commands</h2>

<a href="?run=key:generate"><button>key:generate</button></a>
<a href="?run=storage:link"><button>storage:link</button></a>
<a href="?run=config:clear"><button>config:clear</button></a>
<a href="?run=config:cache"><button>config:cache</button></a>
<a href="?run=route:cache"><button>route:cache</button></a>
<a href="?run=view:cache"><button>view:cache</button></a>
<a href="?run=optimize"><button>optimize</button></a>
<a href="?run=optimize:clear"><button>optimize:clear</button></a>

<hr>
<p style="color:red;">⚠ Setelah selesai setup, hapus file ini segera!</p>

</body>
</html>