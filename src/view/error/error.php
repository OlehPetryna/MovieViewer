<?php /**
 * @var $code integer
 * @var $msg string
 */?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta charset="utf-8">
</head>
<body>
<div class="error-block">
    <div class="error-block__error-wrapper">
        <div class="error-block__error-code">Exception #<?=$code?></div>
        <div class="error-block__error_message"><?=$msg?></div>
        <a href="/" class="error-block__go-back-btn">Main page</a>
    </div>
</div>
</body>
</html>
