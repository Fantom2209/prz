<?php
use \app\helpers\Html;
?>

<?php $this->modules->filter; ?>

<div class="panel panel-default">
    <div class="panel-heading">Всего сообщений (<?php echo $this->Get('count');?>)</div>
    <?php if($this->Get('empty')) {
        $classModif = ' hidden';
        ?>

        <div>Пока ничего нет...</div>

    <?php } ?>

    <table class="table table-view<?php echo isset($classModif) ? $classModif :'';?>">
        <tr>
            <th>id</th>
            <th>Сайт</th>
        </tr>
        <?php foreach($this->Get('data') as $item): ?>
            <tr>
                <td><span><?php echo $item['id'];?></span></td>
                <td><span><?php echo $item['site'];?></span></td>
                <td><a href="#modalComment" data-toggle="modal" class="ajax-link" data-href="<?php echo Html::ActionPath('stats', 'commentcall', array($item['id']));?>">Коментарий</a></td>
                <td><a href="#modalInfo" data-toggle="modal" class="ajax-link" data-href="<?php echo Html::ActionPath('stats', 'infoCall', array($item['id']));?>">Доп. инфо</a></td>
            </tr>
        <?php endforeach;?>
    </table>

</div>

<?php echo $this->Get('pagination'); ?>