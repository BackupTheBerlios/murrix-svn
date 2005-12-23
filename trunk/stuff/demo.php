<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
	<head>
		<title>Cross-Browser Rich Text Editor</title>
		
		<script language="JavaScript" type="text/javascript" src="/murrix2/3dparty/rte/html2xhtml.js"></script>
		<!-- To decrease bandwidth, use richtext_compressed.js instead of richtext.js //-->
		<script language="JavaScript" type="text/javascript" src="/murrix2/3dparty/rte/richtext.js"></script>
		
		
		<script language="JavaScript" type="text/javascript">
			
				function submitForm() {
					//make sure hidden and iframe values are in sync before submitting form
					//to sync only 1 rte, use updateRTE(rte)
					//to sync all rtes, use updateRTEs
					//updateRTE('rte1');
					//updateRTEs();
					alert(document.getElementById('hdnrte1').value)
					//change the following line to true to submit form
					return false;
				}
				
				//Usage: initRTE(imagesPath, includesPath, cssFile, genXHTML)
				initRTE("/murrix2/3dparty/rte/images/", "/murrix2/3dparty/rte/", "", true);
			</script>
	</head>
	<body>
		
		<form id="RTEDemo" name="RTEDemo" action="javascript:void(null);" onsubmit="submitForm();">
			
			
			<input id="bah" type="text" value="1234"/>
			
			<script language="JavaScript" type="text/javascript">
			
				<?php
				//format content for preloading
				if (!(isset($_POST["rte1"]))) {
					$content = "heres the provided text";
				} else {
					//retrieve posted value
					$content = ($_POST["rte1"]);
				}
				?>//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
				writeRichText('rte1', '<?=$content;?>', 520, 200, true, false);
			
			</script>
			
			<input type="submit" name="submit" value="Submit">
		</form>
		
		
	</body>
</html>
