
<!Doctype html>
<html>
    <head>
        <title>Error</title>
    </head>
    <body>
        <div style="background: wheat">
            <?php echo 
                "Exception: ".
                $e->getFile() . " on line".
                $e->getLine(). "\n\n\n"; 
            ?>
            <br> 
            <br>
            <?= print_r($e) ?>
            <br>
            <br>
            <?= print_r(get_included_files()) ?>
        </div>
    </body>
</html>

