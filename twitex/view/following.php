<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <?php if(!isset($_SESSION['login'])):?><meta http-equiv="refresh" content="0; URL=/twitex/">
    <?php endif?>
</head>
<title>Following</title>



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
<div>
<h1>Following</h1>

    <div  id="Poster">
<table>
    <?php if($params['followings'] === null) echo "Following no one";
    else foreach ($params['followings'] as $following) : ?>
        <tr>
            <td><a id="Profile" href="/twitex/profile/<?=$following->getUsername();?>"><?php echo $following->getUsername() ?></a></td>
            <td><a id="Unfollow" href="/twitex/handleUnfollow/<?php if(isset($following)) echo $following->getId(); ?>">Unfollow</a></td>

            <td class="Info" id="Login"><?= $following->getLogin(); ?></td>
            <td class="Info" id="Biography"><?= $following->getBiography(); ?></td>
            <td class="Info" id="Foll"> Followers: <?=  $following->getNbFollowers(); ?> Following: <?= $following->getNbFollowing(); ?> <hr></td>
        </tr>

    <?php endforeach; ?>

</table>
</div>
</div>

</body>
    <footer>
    <p></p>
    </footer>
</html>
