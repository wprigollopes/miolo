<html>
<head>
<title><?=$title?></title>
</head>
<body bgcolor="white" style="font-size: 12pt;">
<? include 'header.inc'; ?> 
<center>
    <h3><?=$title?></h3>
    <h4><a href="index.php">Arquivo: <?=$file?></a></h4>
</center>
<br>
<div style="margin-left: 20px; margin-right: 20px; border: 1px solid black; padding: 10px; font-size: 10pt;">
<? highlight_file($file); ?>
</div>
<? include 'footer.inc'; ?>
</body>
</html>
