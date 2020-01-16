<button id="myActionButton">PUSH</button>
<script>
    $('#myActionButton').click(function(){$.post("functions.php",{ myActionName: "Выполнить" });});
</script>

