<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <title>Profile modification</title>
</head>
<header>
    <div id="cadre-menu">
        <nav id="bordure-menu">
            <div>
                <p><a class="Lien" href="/twitex/profile/<?=$_SESSION['username']; ?>">Your Profile</a> <a href="/twitex/timeline">Timeline</a> <a class="Lien" href="/twitex/handleDisconnection">Disconnect</a></p>
            </div>
        </nav>

        <div id="bouton-menu"> </div>
    </div>
    <header/>

    <body>
<div id="profileModif">
    <h2>Profile modification</h2>

    <?php if(isset($params['error'])) {
        $e = $params['error'];
        echo "
           <p style='color: red'>
            $e
           </p>
        ";
    } ?>

    <?php $user = $_SESSION;?>

    <form action="/twitex/handleProfileUpdate" method="post">
        <section class="content">
        <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-1" name="username" value="<?php if(isset($user)) echo $user['username']; ?>" maxlength="25" minlength="3"  />
                <label class="input__label input__label--kozakura" for="input-1">
                    <span class="input__label-content input__label-content--kozakura" data-content="Username">Username</span>
                </label>
            <br>
            <br>
        </span>

            <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-2" name="login" placeholder="@...." value="<?php if(isset($user)) echo $user['login']; ?>" maxlength="25" minlength="3" />
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
            <input class="input__field input__field--kozakura" type="text" name="email" value="<?php if(isset($user)) echo $user['email']; ?>" maxlength="50" id="input-5" />
                <label class="input__label input__label--kozakura" for="input-5">
                    <span class="input__label-content input__label-content--kozakura" data-content="Email">Email</span>
                </label>
                <br>
            <br>
        </span>

            <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" type="text" id="input-6"  name="biography" value="<?php if(isset($user)) echo $user['biography']; ?>" maxlength="140" />
                <label class="input__label input__label--kozakura" for="input-6">
                    <span class="input__label-content input__label-content--kozakura" data-content="Biography">Biography</span>
                </label>
                <br>
            <br>
        </span>

            <span class="input input--kozakura">
            <input class="input__field input__field--kozakura" id="input-7"  type="date" name="birthday" value="<?php if(isset($user)) echo $user['birthday']; ?>"
                   min="1910-01-01" max="2018-12-31"/>
                <label class="input__label input__label--kozakura" for="input-7">
                    <span class="input__label-content input__label-content--kozakura" data-content="Birthday">Birthday</span>
                </label>
                <br>
            <br>
        </span>
        </section>

        <button type="submit">Save modifications</button>
    </form>
</div>
</body>
<footer>
    <p></p>
</footer>
</html>