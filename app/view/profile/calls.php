<?php
    use \app\helpers\Html;
    $count = 42;
?>


<!--
<div class="panel panel-info">
    <div class="panel-heading">Фильтр</div>
    <div class="panel-body">
        <form>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="site">Сайт: </label>
                        <select class="form-control" name="site" id="site">
                            <option value="">Item 1</option>
                            <option value="">Item 2</option>
                            <option value="">Item 3</option>
                            <option value="">Item 4</option>
                            <option value="">Item 5</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group row">
                        <div class="col-xs-12"><label for="period">Период: </label></div>
                        <div class="col-xs-5"><input type="text" class="form-control"></div>
                        <div class="col-xs-1">-</div>
                        <div class="col-xs-5"><input type="text" class="form-control"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="status">Статус звонка: </label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Item 1</option>
                            <option value="">Item 2</option>
                            <option value="">Item 3</option>
                            <option value="">Item 4</option>
                            <option value="">Item 5</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
-->

<?php $this->modules->filter; ?>

<div class="panel panel-default">
    <div class="panel-heading">Всего звонков (<?php echo $this->Get('count');?>)</div>
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

<div id="modalComment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Коментарий</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('stats', 'commentcall')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id" name="UserData[id]">
                    <div class="form-group">
                        <textarea class="form-control" id="field_comment" name="UserData[String:comment]"></textarea>
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalInfo" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Информация</h4>
            </div>

            <div class="modal-body">

            </div>
        </div>
    </div>
</div>
