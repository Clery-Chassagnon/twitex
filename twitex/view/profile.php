<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <?php if(!isset($_SESSION['login'])):?><meta http-equiv="refresh" content="0; URL=/twitex/">
    <?php endif?>
    <title>Profile</title>
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

<h1><?= $params['account']->getUsername() ?>'s profile</h1>
<div id="infos">
    <table>
   Login : <?= $params['account']->getLogin(); ?><br/>
   <br>Biography : <?= $params['account']->getBiography(); ?><br/>


<br>
    Birthday: <?= $params['account']->getBirthday(); ?>
</br>

<br>
    Date of inscription: <?= $params['account']->getRegistrationDate(); ?>
</br>

        <br>
            <a class="follows" href="/twitex/showFollowers/<?php echo $params['account']->getId() ?>">Followers: <?=  $params['account']->getNbFollowers(); ?> </a><br/><br> <a class="follows" href="/twitex/showFollowing/<?php echo $params['account']->getId() ?>">Following: <?= $params['account']->getNbFollowing(); ?></a>
        </br>
    </table>
</div>

<p>
    <?php if($_SESSION['id'] == $params['account']->getId()):?>


   <br><a class="profModFollow" href="/twitex/handleProfileDelete">Delete your profile</a><br>

    <br><a class="profModFollow" href="/twitex/profileUpdate">Modify your profile</a>

    <?php elseif ($params['account']->isFollowing()):?><a class="profModFollow" href="/twitex/handleUnfollow/<?php if(isset($params['account'])) echo $params['account']->getId(); ?>">Unfollow</a>
    <?php else:?> <a class="profModFollow" href="/twitex/handleFollow/<?php if(isset($params['account'])) echo $params['account']->getId(); ?>">Follow</a>
    <?php endif?>
</p>


</body>
</html>