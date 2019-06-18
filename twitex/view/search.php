<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <?php if(!isset($_SESSION['login'])):?><meta http-equiv="refresh" content="0; URL=/twitex/">
    <?php endif?>
</head>
<title>Timeline</title>
<body>

<?php if(isset($error)) {
    echo "
           <p style='color: red'>
            An error has occured, please retry.
           </p>
        ";
} ?>

<div>
    <p><a class="Lien" href="/twitex/profile/<?=$_SESSION['username']; ?>">Your Profile</a> <a href="/twitex/timeline">Timeline</a> <a class="Lien" href="/twitex/handleDisconnection">Disconnect</a></p>
</div>

<form action = "/twitex/search" method = "POST">
    <input type = "text" name = "recherche" placeholder="Search a message or a username by using @" value="<?php if(isset($params['recherche'])) echo $params['recherche']; ?>">
    <button type="submit"> Search </button>
</form>
<p>

<table>
    <?php if(isset($params['accounts'])) : foreach ($params['accounts'] as $account) : ?>
        <tr>
            <td><a id="Profile" href="/twitex/profile/<?=$account->getUsername();?>"><?php echo $account->getUsername() ?></a></td>
            <td><?= $account->getLogin(); ?></td>
            <td><?= $account->getBiography(); ?></td>
            <td> Followers: <?=  $account->getNbFollowers(); ?> Following: <?= $account->getNbFollowing(); ?></td>
            <td>
            <?php if($_SESSION['id'] == $account->getId()): elseif ($account->isFollowing()):?><a href="/twitex/handleUnfollow/<?php if(isset($account)) echo $account->getId(); ?>">Unfollow</a>
            <?php else:?> <a href="/twitex/handleFollow/<?php if(isset($account)) echo $account->getId(); ?>">Follow</a>
            <?php endif?>
            </td>
        </tr>

    <?php endforeach; ?>


    <?php elseif(isset($params['posts'])) : foreach ($params['posts'] as $post) : ?>
        <table>
            <?php if($post->getReposterUsername() != null) :?><tr><?php echo $post->getReposterUsername() ?> has repost</tr>
            <?php endif?>
            <tr><td><a href="/twitex/profile/<?= $post->getWriterInfos()->getUsername(); ?>"><?php echo $post->getWriterInfos()->getUsername() ?></a></td></tr>
            <tr><td><?= $post->getWriterInfos()->getLogin(); ?></td>
                <td> Laziness <?php $date1   = strtotime(date ( 'c'));
                    $date2 = strtotime($post->getWritingDate());
                    $diff  = abs($date1 - $date2);
                    $tmp = $diff;
                    $retour['second'] = $tmp % 60;

                    $tmp = floor( ($tmp - $retour['second']) /60 );
                    $retour['minute'] = $tmp % 60;

                    $tmp = floor( ($tmp - $retour['minute'])/60 );
                    $retour['hour'] = $tmp % 24;

                    $tmp = floor( ($tmp - $retour['hour'])  /24 );
                    $retour['day'] = $tmp % 365;

                    $tmp = floor( ($tmp - $retour['hour'])  /365 );
                    $retour['year'] = $tmp;


                    if ($retour['year'] <= 0)
                    {
                        if ($retour['day'] <= 0)
                        {
                            if ($retour['hour'] <= 0)
                            {
                                if ($retour['minute'] <= 0)
                                {
                                    echo $retour['second'] . ' second';
                                }
                                else echo $retour['minute'] . ' minute ' . $retour['second'] . ' second';
                            }
                            else echo $retour['hour'] . ' hour '. $retour['minute'] . ' minute ' . $retour['second'] . ' second';
                        }
                        else echo $retour['day'] . ' day '. $retour['hour'] . ' hour '. $retour['minute'] . ' minute ' . $retour['second'] . ' second';
                    }
                    else echo $retour['year'] . 'year' . $retour['day'] . ' day '. $retour['hour'] . ' hour '. $retour['minute'] . ' minute ' . $retour['second'] . ' second';?> ago </td></tr>
            <tr><td><?= $post->getMessage(); ?></td></tr>
            <tr>
                <td></td>
                <td>Likes: <?= $post->getNbLikes(); ?></td>
                <td>Reposts: <?= $post->getNbReposts(); ?></td>
            </tr>
            <tr>
                <td></td>
                <td><?php if($post->isLiked()):?><a href="/twitex/handleUnlike/<?php echo $post->getId(); ?>">Unlike</a>
                    <?php else:?> <a href="/twitex/handleLike/<?php echo $post->getId(); ?>">Like</a>
                    <?php endif?>
                </td>
                <td>
                    <?php if($post->isReposted()):?><a href="/twitex/handleUnrepost/<?php echo $post->getId(); ?>">Unrepost</a>
                    <?php else:?> <a href="/twitex/handleRepost/<?php echo $post->getId(); ?>">Repost</a>
                    <?php endif?>
                </td>
            </tr>
        </table>
        <br />
    <?php endforeach?>
    <?php else : echo "Nothing found";?>
    <?php endif?>
</table>

</body>
</html>