<?php use \app\helpers\Html; ?>

<div class="panel panel-info">
    <div class="panel-body">
        <form action="<?php echo Html::ActionPath('admin', 'blacklist')?>" method="POST" class="ajax-form">
            <div class="form-group">
                <label for="phones">Номера: </label>
                <textarea rows="20" class="form-control" name="UserData[phones]" id="phones"><?php echo $this->Get('phones');?></textarea>
            </div>
            <button class="btn btn-default">Сохранить</button>
        </form>
    </div>
</div>