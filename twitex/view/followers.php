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
        <h1>Followers</h1>

        <table>
            <?php if($params['followers'] === null) echo "No followers";
            else foreach ($params['followers'] as $follower) : ?>
                <tr>
                    <td><a id="Profile" href="/twitex/profile/<?=$follower->getUsername();?>"><?php echo $follower->getUsername() ?></a></td>
                    <td><?= $follower->getLogin(); ?></td>
                    <td><?= $follower->getBiography(); ?></td>
                    <td> Followers: <?=  $follower->getNbFollowers(); ?> Following: <?= $follower->getNbFollowing(); ?></td>
                    <td><a id="Follow" href="/twitex/handleFollow/<?php if(isset($follower)) echo $follower->getId(); ?>">Follow</a></td>
                </tr>

            <?php endforeach; ?>

        </table>
    </div>

    </body>
    <footer>
        <p></p>
    </footer>
</html>
