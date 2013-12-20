<?php
$config     =$tpl->get('config');
$main       =$tpl->get('main');
?>
<fieldset class="gllpLatlonPicker">
    <div class="gllpMap">Google Maps</div></br>
    <div class="control-group">
        <label class="control-label">Mapping Provider</label>
        <div class="controls">
            <select name="widget[<?php $tpl->widgetIndex; ?>][config][provider]" class="chzn-select">
                <?php
                $provider=$tpl->get('provider');
                ?>
                <option value="google" <?php print (!$provider=='google')?'selected':''; ?>>Google</option>
                <option value="nokia" <?php print ($provider=='nokia')?'selected':''; ?>>Nokia</option>
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Version</label>
        <div class="controls">
            <input name="widget[<?php $tpl->widgetIndex; ?>][config][version]" type="text" class="gllpLatitude"  value="<?php $tpl->version ?>">
        </div>
    </div>
</fieldset>