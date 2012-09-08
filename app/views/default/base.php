
<!doctype html>
<html <?php echo "lang=$lang xml:lang=$lang"; ?> >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<title><?php echo $title; ?></title>
		<meta name="keywords" <?php echo "content='{$keywords}'"; ?> />
        <meta name="description" <?php echo "content='{$description}'"; ?> />
        
         <!-- <link type="text/css" rel="stylesheet" href="css/layout.css" /> -->
	</head>
	<body>
	
		<?php include($pageContent) ?>
		
	</body>
</html>

