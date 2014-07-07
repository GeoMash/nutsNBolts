<select <?php print($tpl->get('multiselect')=='yes'?'multiple':'');?> data-id="<?php $tpl->dataId; ?>" name="<?php $tpl->name; ?>[]">
	<?php
		$value=$tpl->get('value');
		$value=json_decode(str_replace('application/json: ','',$value),true);	
		$options=$tpl->get('options');
		for ($i=0,$j=count($options); $i<$j; $i++)
		{
			$selected=(in_array($options[$i]->value,$value?'selected':''));
			if(in_array($options[$i]->value,$value))
			{
				$selected=' selected ';
			}
			else
			{
				$selected='';
			}
			
			print '<option value="'.$options[$i]->value.'" '.$selected.'>'.$options[$i]->label.'</option>';
		}
	?>
</select>