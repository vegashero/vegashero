<table class="vh-casino-providers" border="0" cellspacing="0" cellpadding="0">
<thead>
    <tr>
        <th width="30%">Casino</th>
        <th width="40%">Bonus</th>
        <th width="30%">&nbsp;</th>
    </tr>
</thead>
<tbody>
<!--loop through operators -->
<?php foreach($operators as $operator) :?>
    <tr>
    <td class="vh-casino"><img src="<?=$images?><?=$operator->slug?>.png" width="180px"></td>
        <td class="vh-bonus">500</td>
        <td><a href="<?=$operator->slug?>" class="vh-playnow">Sign me up for <?=$operator->name?></a></td>
    </tr>
<?php endforeach ?>

</tbody>
