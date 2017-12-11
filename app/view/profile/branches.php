<?php use \app\helpers\Html;?>

<div class="row">
    <div class="col-md-2">
        <a href="#modalAddBranch" data-toggle="modal" class="btn btn-primary">Добавить филиал</a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if($this->Get('empty')) {
            $classModif = ' hidden';
            ?>

            <div class="panel panel-default empty-table">Пока ничего нет...</div>

        <?php } ?>

        <table class="table table-view<?php echo isset($classModif) ? $classModif :'';?>">
            <tr>
                <th>Название</th>
                <th>Временная зона</th>
                <th colspan="3">Операции</th>
            </tr>
            <?php foreach($this->Get('data') as $item):
                echo Html::Snipet('BranchLine', array(
                    $item['name'], $item['time_zone'],
                    Html::ActionPath('branch', 'update', array($item['id'])),
                    Html::ActionPath('branch', 'property', array($item['id'])),
                    Html::ActionPath('branch', 'delete', array($item['id'])),
                ));
                ?>
            <?php endforeach;?>
        </table>

    </div>
</div>


<div id="modalAddBranch" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Добавить филиал</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('branch', 'add')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id_user" name="UserData[user_id]" value="<?php echo $this->Get('UserId');?>">
                    <div class="form-group">
                        <label>Название:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Временная зона:</label>
                        <input class="form-control" type="text" id="field_time_zone" name="UserData[String:time_zone]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalScheduleBranch" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">График работы</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('branch', 'add')?>" method="POST" class="ajax-form">

                    <div class="form-group">
                        <label>Понедельник:</label>
                        <input class="form-control" type="checkbox" id="field_name" name="UserData[String:name]">
                        <label>C:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <label>По:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <label>Обед C:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <label>По:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalUpdateBranch" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Обновить Филиал</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('branch', 'update')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id" name="UserData[id]">
                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Временная зона:</label>
                        <input class="form-control" type="text" id="field_time_zone" name="UserData[String:time_zone]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>