<?php
$conn = mysqli_connect('localhost','root','','thedigity');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>


<input type="text" name="" id="">
<button onclick="copy();">
  Copy link
</button>

<input type="hidden" id="link-to-copy" value="ysa">

<script>
    function copy() {
        navigator.clipboard.writeText($('#link-to-copy').val());
    }
</script>
</body>
</html>