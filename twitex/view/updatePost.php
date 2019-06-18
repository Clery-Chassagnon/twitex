<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;
            charset=utf-8" />
    <?php if(!isset($_SESSION['login'])):?><meta http-equiv="refresh" content="0; URL=/twitex/">
    <?php endif?>
</head>
<title>Update post</title>
<body>
<h1>Update post</h1>
<?php if(isset($params['flash'])) {
    echo "
           <p style='color: green'>
            " . $params['flash'] . " 
           </p>
        ";
} ?>

<p>
    <a href="/twitex/profile/<?=$_SESSION['username']; ?>"> Your Profil </a>
</p>

<p>
    <a href="/twitex/timeline">Timeline</a>
</p>

<table>
        <tr>
            <td>
            <form action="/twitex/handlePostUpdate/<?php echo $params['post']->getId()?>" method="post">
                <input type="text" name="message" value="<?php if(isset($params['post'])) echo $params['post']->getMessage(); ?>">
                <input type="hidden" name="postId" value="<?php echo $params['post']->getId(); ?>">
                <button type="submit">Update</button>
            </form>
            </td>
            <td><a href="/twitex/handlePostDelete/<?php echo $params['post']->getId(); ?>">Delete</a></td>
        </tr>

</table>

</body>
</html>