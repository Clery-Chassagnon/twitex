<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
</head>
<title>Inscription</title>
<body>
    <h1> Twitex </h1>
<div>
    <h2>Inscription</h2>

    <?php if(isset($params['error'])) {
        $e = $params['error'];
        echo "
           <p style='color: red'>
            $e
           </p>
        ";
    } ?>

    <form action="/twitex/handleInscription" method="POST">
         <section class="content">
        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-1" name="username" value="<?php if(isset($account)) echo $account['username']; ?>" maxlength="25" minlength="3"  />
                <label class="input__label input__label--kozakura" for="input-1">
                    <span class="input__label-content input__label-content--kozakura" data-content="Username">Username</span>
                </label>
        <br>
        <br>
        </span>
        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-2" name="login" placeholder="@...." value="<?php if(isset($account)) echo $account['login']; ?>" maxlength="25" minlength="3" />
                <label class="input__label input__label--kozakura" for="input-2">
                    <span class="input__label-content input__label-content--kozakura" data-content="Login">Login</span>
                </label>
              <br>
        <br>
        </span>

        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" id="input-3"  type="password" name="password" maxlength="16" minlength="6"/>
                <label class="input__label input__label--kozakura" for="input-3">
                    <span class="input__label-content input__label-content--kozakura" data-content="Password">Password</span>
                </label>
              <br>
        <br>
        </span>

        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" id="input-4" type="password" name="repeatPassword"/>
                <label class="input__label input__label--kozakura" for="input-4">
                    <span class="input__label-content input__label-content--kozakura" data-content="RepeatPassword">Repeat your Password</span>
                </label>
              <br>
        <br>
        </span>

 <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" name="email" value="<?php if(isset($account)) echo $account['email']; ?>" maxlength="50" id="input-5" />
                <label class="input__label input__label--kozakura" for="input-5">
                    <span class="input__label-content input__label-content--kozakura" data-content="Email">Email</span>
                </label>
       <br>
        <br>
        </span>

        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-6"  name="biography" value="<?php if(isset($account)) echo $account['biography']; ?>" maxlength="140" />
                <label class="input__label input__label--kozakura" for="input-6">
                    <span class="input__label-content input__label-content--kozakura" data-content="Biography">Biography</span>
                </label>
              <br>
        <br>
        </span>

        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" id="input-7"  type="date" name="birthday" value="<?php if(isset($account)) echo $account['birthday']; ?>"
                   min="1910-01-01" max="2018-12-31"/>
                <label class="input__label input__label--kozakura" for="input-7">
                    <span class="input__label-content input__label-content--kozakura" data-content="Birthday">Birthday</span>
                </label>
              <br>
        <br>
        </span>
</section>

        <button type="submit">Submit</button>
    </form>
    <p>
        <a id="Connection" href="/twitex/">Connexion</a>
    </p>
</div>
</body>
    <footer>
    <p></p>
    </footer>
</html>