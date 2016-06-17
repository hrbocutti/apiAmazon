<html>
<body>
<?php
    
    if ($_GET['back']=='erroFeed') {
        echo "<p style='color: red;'>XML Inválido !</p>";
    }

    if ($_GET['back']=='erroFeedType') {
        echo "<p style='color: red;'>Selecione Feed Type Valido !</p>";
    }

    if ($_GET['back']=='erroPost') {
        echo "<p style='color: red;'>URL Inválida!</p>";
    }

?>
<!--
    <form action="MarketplaceWebService/Samples/SubmitFeedSample.php" method="POST" enctype="multipart/form-data">
    -->
    <form action="MarketplaceWebService/Samples/SubmitFeedSample.php?envio=true" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Tem certeza desta a&ccedil;&atilde;o ?');">
        <label>Feed Type: </label><br>
        <select name="feedType">
            <option value="null"></option>
            <option value="Product Feed">Product Feed</option>
            <option value="Inventory Feed">Inventory Feed</option>
            <option value="Pricing Feed">Pricing Feed</option>
            <option value="Product Images Feed">Product Images Feed</option>
        </select>
        <label>XML: </label><br>
        <textarea style="margin: 0px; width: 432px; height: 320px;" id="xml" name="xml"></textarea>
        <br><input type="submit" value="Submit">
    </form>
</body>
</html>