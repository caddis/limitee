<?=form_open('C=addons_extensions' . AMP . 'M=save_extension_settings' . AMP . 'file=limitee'); ?>

<table class="mainTable">
	<thead>
		<tr>
			<th colspan="3"><?=ee()->lang->line('group_heading'); ?></th>
		</tr>
	</thead>
<?php

$i = 0;
$group = null;

foreach ($channel_fields as $row) {
	$max = '';
	$type = '1';

	if (isset($settings['rules']) and isset($settings['rules'][$row->field_id])) {
		$max = $settings['rules'][$row->field_id]['max'];
		$type = $settings['rules'][$row->field_id]['type'];
	}

	$row_class = ($i++ % 2) ? 'odd' : 'even';

	if ($group != $row->group_name) {
		$title_max = '';
		$title_type = '1';

		if (isset($settings['rules']) and isset($settings['rules']['group_title_' . $row->group_id])) {
			$title_max = $settings['rules']['group_title_' . $row->group_id]['max'];
			$title_type = $settings['rules']['group_title_' . $row->group_id]['type'];
		}

		echo '<tr>'
				. '<td colspan="3"><b>' . $row->group_name . ' Channel</b></td>'
			. '</tr>'
			. '<tr>'
				. '<td style="width:20%"></td>'
				. '<td style="width:140px">' . ee()->lang->line('count_heading') . '</td>'
				. '<td>' . ee()->lang->line('type_heading') . '</td>'
			. '</tr>'
			. '<tr>'
				. '<td>Title</td>'
				. '<td>' . form_input(array('type' => 'number', 'name' => 'rules[group_title_' . $row->group_id . '][max]', 'value' => $title_max, 'style' => 'width:60px')) . '&nbsp; (' . ee()->lang->line('limit_label') . ': 100)' . '</td>'
				. '<td>' . form_dropdown('rules[group_title_' . $row->group_id . '][type]', array('1' => 'Soft', '2' => 'Hard'), $title_type) . '</td>'
			. '</tr>';

		$group_name = $row->group_name;
	}

	echo '<tr>'
			. '<td>' . $row->field_label . '</td>'
			. '<td>' . form_input(array('type' => 'number', 'name' => 'rules[' . $row->field_id . '][max]', 'value' => $max, 'style' => 'width:60px')) . (($row->field_type !== 'textarea') ? ('&nbsp; (' . ee()->lang->line('limit_label') . ': ' . $row->field_maxl . ')') : '') . '</td>'
			. '<td>' . form_dropdown('rules[' . $row->field_id . '][type]', array('1' => 'Soft', '2' => 'Hard'), $type) . '</td>'
		. '</tr>';
}

?>
</table>
<?=form_submit(array('name' => 'submit', 'value' => 'Update')); ?>

<?=form_close(); ?>