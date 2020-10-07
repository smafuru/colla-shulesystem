<?php ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
        
        <SCRIPT LANGUAGE="JavaScript">

            function isNumber(form, n){
               var validChar = '0123456789'; // legal chars  
                var idadi = n;
                for (var i = 1; i <= n; i++) {
                    var field = form.elements['alama' + i].value;
                    var strlen = field.length;
                    for (index = 0; index < strlen; index++) {
                        if (validChar.indexOf(field.charAt(index)) < 0 ||field > 50 || field < 0) {
                            alert("alama > 50 au alama < 0 au ulichoingiza sio alama");
                            return false;
                        }                        
                    } // end scanning

                }
            }

            function tema() {
                alert("haya ndao");
                return false;
            }
            
        </SCRIPT>
</head>
<body>

<form name = "login" method="post" action="processLogin.php" >

		<input type="text" name="alama" id="alama" value="" onchange="return isNumber(this.form, 2)">
		<input type="submit" name="submit" id="submit" value="Submit" onclick="return tema()">

</form>

</body>

</html>
