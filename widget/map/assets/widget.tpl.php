<?php
$jsonValue=$tpl->get('value');
$value=json_decode(str_replace('application/json: ','',$jsonValue));
?>
<div id="map-<?php $tpl->dataId; ?>">
    <fieldset class="gllpLatlonPicker">
        <div class="control-group">
            <label class="control-label">Start Latitude</label>
            <div class="controls">
                <input class="map-lat" id="latbox" name="<?php $tpl->name; ?>[lat]" type="text" value="<?php print($value->lat); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Start Longitude</label>
            <div class="controls">
                <input class="map-lng" id="lngbox" name="<?php $tpl->name; ?>[lng]" type="text" value="<?php print($value->lng); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Zoom</label>
            <div class="controls">
               <input class="map-zoom" type="text" name="<?php $tpl->name; ?>[zoom]" class="gllpZoom" value="<?php print($value->zoom); ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label">Map Preview</label>
            <div class="controls">
                <div id="<?php $tpl->dataId; ?>" data-id="<?php $tpl->dataId; ?>"></div>
            </div>
        </div>
    </fieldset>
</div>