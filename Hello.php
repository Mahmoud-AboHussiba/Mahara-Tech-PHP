<html>
<body>
    <?php  // XML style tag for php
        echo "<p>Hello, Now it's ";
        echo date('H:i js F Y');
        echo "</p>";
    ?>
    <? // short style tag considered deprecated in recent versions of PHP
        echo "Hello World!";
    ?>
    <script language=`php`>  // outdated and invalid way to include PHP in HTML.
        echo `Hello from script`;
    </script>
   
</body>
</html>