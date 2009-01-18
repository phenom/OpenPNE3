<?php slot('submenu'); ?>
<?php include_partial('submenu'); ?>
<?php end_slot(); ?>

<h2>
<?php switch ($type): ?>
<?php case 'mobileHome': ?>
<?php echo __('携帯版ホーム画面ウィジェット設定'); break; ?>
<?php default: ?>
<?php echo __('ホーム画面ウィジェット設定'); ?>
<?php endswitch; ?>
</h2>

<p><?php echo __('特定のページや領域に対して、あらかじめ用意された部品（ウィジェット）を自由に配置、設定することができます。') ?></p>

<ul>
<li><?php echo link_to(__('ホーム画面ウィジェット設定'), 'design/widget?type=home') ?></li>
<li><?php echo link_to(__('携帯版ホーム画面ウィジェット設定'), 'design/widget?type=mobileHome') ?></li>
</ul>


<?php use_helper('opJavascript') ?>

<div>
<form id="widgetForm" action="<?php url_for('design/widget?type='.$type) ?>" method="post">
<?php foreach ($widgets as $widgetType => $item): ?>
<?php if ($item): ?>
<?php foreach ($item as $key => $widget): ?>
<input class="<?php echo $widgetType ?>Widget" type="hidden" name="widget[<?php echo $widgetType ?>][<?php echo $key ?>]" value="<?php echo $widget->getId() ?>" />
<?php endforeach; ?>
<?php echo $sortForm->renderHiddenFields(); ?>
<?php echo $addForm->renderHiddenFields(); ?>
<?php endif; ?>
<?php endforeach; ?>
<input type="submit" value="<?php echo __('設定変更') ?>" />
</form>
</div>

<iframe src="<?php echo url_for('design/'.$type.'WidgetPlot') ?>" width="610" height="410">
</iframe>

<?php echo make_modal_box('modal', '<iframe width="400" height="400"></iframe>', 400, 400) ?>

