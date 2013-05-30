<?php 
include 'core/init.php'; 
include 'includes/overall/header.php'; 
?>

<script>
function alertsize(pixels){
    pixels+=32;
    document.getElementById('myiframe').style.height=pixels+"px";
}
</script>

<h1>Test Results</h1>
<iframe src='data/results.php' style='width:65%; background:white;' frameborder='0' id="myiframe" scrolling="auto"></iframe>

<?php include 'includes/overall/footer.php'; ?>