<?php 
include 'core/init.php';
protect_page(); 
include 'includes/overall/header.php'; 
?>

<h1>AI Development</h1>
<?php
$usernamehash = md5($user_data['username']); 
$file = $_SERVER['DOCUMENT_ROOT']. "/data/errors//" . $usernamehash . ".txt";
if(file_exists($file)) {
	if(filesize( $file ) != 0)
	{
		echo "<span style=\"color:#FF0000\">ERROR WITH BOT, PLEASE READ ERRORS <a href=\"data/errors/" . $usernamehash . ".txt\" target=\"_blank\">HERE!</a></span>";
	}
}
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<div id="editor"><?php ai_read(); ?></div>
<input type="submit" name="submit" value="Save AI" onmousedown="save_ai()"><input type="submit" name="submit" value="Compile AI" onmousedown="compile_ai()"><input type="submit" name="submit" value="Test AI" onclick="test_ai(); return false;">
<input type="hidden" name="save" value="1">
</form>
    
<script src="http://rawgithub.com/ajaxorg/ace-builds/master/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.getSession().setMode("ace/mode/java");
	document.getElementById('editor').style.fontSize='12px';
	editor.getSession().setUseWrapMode(true);
	editor.setShowPrintMargin(false);
	function save_ai() {
		$.ajax({
			type: 'POST',
			url: 'core/functions/save_ai.php',
			data: {data: editor.getValue()},
		});
	}
	function compile_ai() {
		save_ai();
		$.ajax({
			type: 'POST',
			url: 'data/compile_ai.php',
		});
	}
	function test_ai() {
		window.location = "data/test.php";
	}
	editor.commands.addCommand({
		name: 'save',
		bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
		exec: function(editor) {
			save_ai();
		},
		readOnly: true
	});
</script>

<?php include 'includes/overall/footer.php'; ?>