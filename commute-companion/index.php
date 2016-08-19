<!DOCTYPE html>


<html>
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <meta charset="UTF-8">

    <title>Commute Companion</title>

</head>

<body>
    

<div class="logo"></div>
<div class="login-block">
    <h1>Login</h1>
    <form name ="login" style="display : inline" action="companionFinder.php" method="post" onsubmit = "return validateForm()">
        <input type="text" name="email" placeholder="Email" id="email" />
        <input type="password" name="hashed_pass" placeholder="Password" id="hashed_pass" />
        <button> Submit </button>

    </form> 
</div>

<script>    
    function validateForm() {
    var username = document.forms["login"]["email"].value;
    var password = document.forms["login"]["hashed_pass"].value;
    if (username == null || username.replace(/^\s+/, '').replace(/\s+$/, '') == "") {
        alert("Name must be filled out");
        return false;
    }
    else if (password == null || password ==""){
        alert("Password must be filled out");
        return false;
    }
}

</script>

</body>

</html>