<!DOCTYPE HTML>
<html>
<head>
    <title>Highlights</title>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <?php if(!isset($_SESSION['login'])):?><meta http-equiv="refresh" content="0; URL=/twitex/">
    <?php endif?>
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
    <h1>Highlights</h1>

    <?php if(isset($error)) {
        echo "
           <p style='color: red'>
            An error has occured, please retry.
           </p>
        ";
    } ?>



    <form action = "/twitex/search" method = "POST">
        <section class="content">
                <span class="input input--minoru">
                    <input class="input__field input__field--minoru" type="text" id="input-1" name="recherche" placeholder="Search a message or a username by using @"/>
                    <label class="input__label input__label--minoru" for="input-1">
                        <button type="submit" class="input__label-content input__label-content--minoru">Search</button>
                    </label>
                </span>
        </section>
    </form>

    <br>
    <div  id="Poster">
        <table>
            <?php if($params['posts'] === null) echo "No posts in your highlights";
            else foreach ($params['posts'] as $post) : ?>
                <table>
                    <tr><?php if($post->getWriterInfos()->getUsername() == $_SESSION['username']) :?> <td>You posted</td>
                        <?php elseif($post->getReposterUsername() != null) : if ($post->getReposterUsername() == $_SESSION['username']): ?> <td>You have reposted</td>
                        <?php else: ?><td><?php echo $post->getReposterUsername() ?> has repost</td>
                        <?php endif?>
                        <?php endif?></tr>
                    <tr><td><a class="User" href="/twitex/profile/<?= $post->getWriterInfos()->getUsername(); ?>"><?php echo $post->getWriterInfos()->getUsername() ?></a></td></tr>
                    <tr><td class="affiche"><?= $post->getWriterInfos()->getLogin(); ?></td>
                        <td class="affiche"> Laziness <?php $date1   = strtotime(date ( 'c'));
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
                        <?php if($_SESSION['id'] != $post->getWriterId()):?>
                            <td><?php if($post->isLiked()):?><a href="/twitex/handleUnlike/<?php echo $post->getId(); ?>">Unlike</a>
                                <?php else:?> <a href="/twitex/handleLike/<?php echo $post->getId(); ?>">Like</a>
                                <?php endif?>
                            </td>
                            <td>
                                <?php if($post->isReposted()):?><a href="/twitex/handleUnrepost/<?php echo $post->getId(); ?>">Unrepost</a>
                                <?php else:?> <a href="/twitex/handleRepost/<?php echo $post->getId(); ?>">Repost</a>
                                <?php endif?>
                            </td>
                        <?php else:?>
                            <td>
                                <a href="/twitex/PostUpdate/<?php echo $post->getId(); ?>">Update</a>
                            </td>
                            <td>
                                <a href="/twitex/handlePostDelete/<?php echo $post->getId(); ?>">Delete</a>
                            </td>
                        <?php endif?>
                    </tr>
                </table>
                <br />
            <?php endforeach?>
        </table>
    </div>

    </body>
    <footer>
        <p></p>
    </footer>
</html>