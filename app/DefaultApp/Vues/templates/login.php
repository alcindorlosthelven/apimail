
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="description" content="los-framework">
    <meta name="author" content="Alcindor losthelven">
    <title><?php if(isset($titre))echo $titre; ?></title>
    <!-- Main Styles -->
    <link rel="stylesheet" href="">
</head>

<body>
<div id="contenue">
    <?php
    if(isset($contenue)){echo $contenue;}else{echo "pas de contenue";}
    ?>
</div>
</body>
</html>
