<?php use \app\helpers\Html;?>
<div class="row">

        <div class="col-md-12">
            <a href="#modalAddProperty" data-toggle="modal" class="btn btn-primary">Добавить свойство</a>&nbsp;&nbsp;
            <a href="#modalEnableProperty" data-toggle="modal" class="btn btn-primary ajax-link" data-href="<?php echo Html::ActionPath('siteproperties', 'enable')?>">Активация/Деактивация</a>
            <table class="table table-view">
                <tr>
                    <th>id</th>
                    <th>Название</th>
                    <th colspan="3">Операции</th>
                </tr>
                <?php foreach($this->Get('properties') as $item):
                    echo Html::Snipet('PropertyLine', array(
                        $item['id'], $item['name'],
                        Html::ActionPath('siteproperties', 'update', array($item['id'])),
                        Html::ActionPath('siteproperties', 'delete', array($item['id'])))
                    );
                    ?>
                <?php endforeach;?>
            </table>

        </div>
</div>

<div id="modalAddProperty" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Добавить свойство</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo Html::ActionPath('siteproperties', 'add')?>" method="POST" class="ajax-form">
                    <div class="form-group">
                        <label>Название:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Активен:</label>
                        <input type="checkbox" id="field_active" name="UserData[active]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Группа:</label>
                        <select class="form-control" id="field_sGroup" name="UserData[sGroup]">
                            <?php
                            foreach($this->Get('PropertiesGroup') as $item){
                                echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                            }
                            ?>
                        </select>
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Тип:</label>
                        <select class="form-control" id="field_type" name="UserData[type]">
                            <?php
                                foreach($this->Get('PropertiesType') as $item){
                                    echo '<option value="'.$item['id'].'">'.$item['name_ru'].'</option>';
                                }
                            ?>
                        </select>
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Параметры: (value,snippet,validator,max,min,step,name,default,type)</label>
                        <input class="form-control" type="text" id="field_dop" name="UserData[dop]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Системный:</label>
                        <input type="checkbox" id="field_system" name="UserData[system]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Добавить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalUpdateProperty" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Обновить свойство</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('siteproperties', 'update')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id" name="UserData[id]">
                    <div class="form-group">
                        <label>Название:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[String:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Активен:</label>
                        <input type="checkbox" id="field_active" name="UserData[active]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Группа:</label>
                        <select class="form-control" id="field_sGroup" name="UserData[sGroup]">
                            <?php
                            foreach($this->Get('PropertiesGroup') as $item){
                                echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                            }
                            ?>
                        </select>
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Тип:</label>
                        <select class="form-control" id="field_type" name="UserData[type]">
                            <?php
                            foreach($this->Get('PropertiesType') as $item){
                                echo '<option value="'.$item['id'].'">'.$item['name_ru'].'</option>';
                            }
                            ?>
                        </select>
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Параметры: (value,snippet,validator,max,min,step,name,default,type)</label>
                        <input class="form-control" type="text" id="field_dop" name="UserData[dop]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Системный:</label>
                        <input type="checkbox" id="field_system" name="UserData[system]">
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalEnableProperty" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Активировать/Деактивировать</h4>
            </div>

            <div class="modal-body">
                <form action="" method="POST" class="ajax-form">

                </form>
            </div>
        </div>
    </div>
</div>
