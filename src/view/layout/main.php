<?
/**
 * @var $this \app\base\View
 * @var $content
 */
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?= $this->renderPart("block/header") ;?>
<div class="content">
    <?= $content ?>
</div>
<?= $this->renderPart("block/footer") ;?>

<script type="application/javascript" src="/js/main.js"></script>
</body>
</html>
