<?php use \app\helpers\Html; ?>

<div class="panel panel-info">
    <div class="panel-body">
        <form action="<?php echo Html::ActionPath('admin', 'holidays')?>" method="POST" class="ajax-form">
            <div class="form-group">
                <label for="holidays">Праздники: </label>
                <textarea rows="20" class="form-control" name="UserData[holidays]" id="holidays"><?php echo $this->Get('holidays');?></textarea>
            </div>
            <button class="btn btn-default">Сохранить</button>
        </form>
    </div>
</div>