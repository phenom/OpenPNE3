<?php echo $member->getProfile('nickname') ?> さんのホームです。
<ul>
<?php if ($sf_user->hasCredential('friend')): ?>
<li><?php echo link_to('フレンドをやめる', 'friend/unlink?id=' . $member->getId()) ?></li>
<?php else: ?>
<li><?php echo link_to('フレンドになる', 'friend/link?id=' . $member->getId()) ?></li>
<?php endif; ?>
</ul>