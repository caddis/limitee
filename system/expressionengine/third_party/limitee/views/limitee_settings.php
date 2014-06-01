<?php

echo form_open('C=addons_extensions' . AMP . 'M=save_extension_settings' . AMP . 'file=limitee');

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

		if (isset($settings['rules']) and isset($settings['rules']['group_title_' . $row->channel_id])) {
			$title_max = $settings['rules']['group_title_' . $row->channel_id]['max'];
			$title_type = $settings['rules']['group_title_' . $row->channel_id]['type'];
		}

		if ($group !== null) {
			echo '</tbody>
				</table>';
		}

		echo '<table class="mainTable">
				<thead>
					<tr>
						<th colspan="3">' . $row->group_name . '</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:20%"></td>
						<td style="width:140px">' . ee()->lang->line('count_heading') . '</td>
						<td>' . ee()->lang->line('type_heading') . '</td>
					</tr>
					<tr>
						<td>Title</td>
						<td>' . form_input(array('type' => 'number', 'name' => 'rules[group_title_' . $row->channel_id . '][max]', 'value' => $title_max, 'style' => 'width:60px')) . '&nbsp; (' . ee()->lang->line('limit_label') . ': 100)' . '</td>
						<td>' . form_dropdown('rules[group_title_' . $row->channel_id . '][type]', array('1' => 'Soft', '2' => 'Hard'), $title_type) . '</td>
					</tr>';

		$group = $row->group_name;
	}

	if ($row->field_type == 'text' OR $row->field_type == 'textarea') {
		echo '<tr>
				<td>' . $row->field_label . '</td>
				<td>' . form_input(array('type' => 'number', 'name' => 'rules[' . $row->field_id . '][max]', 'value' => $max, 'style' => 'width:60px')) . (($row->field_type !== 'textarea') ? ('&nbsp; (' . ee()->lang->line('limit_label') . ': ' . $row->field_maxl . ')') : '') . '</td>
				<td>' . form_dropdown('rules[' . $row->field_id . '][type]', array('1' => 'Soft', '2' => 'Hard'), $type) . '</td>
			</tr>';
	}
}

if ($i > 0) {
	echo '</tbody>
		</table>';
}

echo form_submit(array('name' => 'submit', 'value' => 'Update'));
echo form_close();

?>