<!DOCTYPE HTML>
<html>
<head>
    <title>Connexion</title>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
    <h1> Twitex </h1>
<div>
    <h2>Connexion</h2>

    <?php if(isset($params['error'])) {
        $e = $params['error'];
        echo "
           <p style='color: red'>
            $e
           </p>
        ";
    } ?>

    <form action="/twitex/handleConnexion" method="POST">
        <section class="content">
        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-1" name="email" value="<?php if(isset($account)) echo $account['email']; ?>" />
                <label class="input__label input__label--kozakura" for="input-1">
                    <span class="input__label-content input__label-content--kozakura" data-content="Email">Email</span>
                </label>
        </span>
            <br>
            <br>
            <span class=" input input--kozakura">
            <input class="input__field input__field--kozakura" type="password" name="password" id="input-2" />
                <label class="input__label input__label--kozakura" for="input-2">
                    <span class="input__label-content input__label-content--kozakura" data-content="Password">Password</span>
                </label>
        </span>
        </section>
        <br>
        <button type="Submit">Submit</button>
    </form>


    <p>
        <a id="Register" href="/twitex/inscription">Inscription</a>
    </p>
</div>
</body>
    <footer>
    <p></p>
    </footer>

</html>