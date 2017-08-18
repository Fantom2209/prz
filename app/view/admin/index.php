<?php use \app\helpers\Html;?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-view">
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th colspan="3">Операции</th>
            </tr>
            <?php foreach($this->Get('Users') as $item):
                    $isBan = $item['ban'] == '1';
                    $classBan = $isBan ? 'ban-item' : '';
                    echo Html::Snipet('UserLine', array(
                        $classBan, $item['id'], $item['name'], $item['email'], $item['role_name'],
                        Html::ActionPath('account', 'update', array($item['id'])),
                        Html::ActionPath('account', $isBan ? 'amnesty' : 'ban', array($item['id'])),
                        ($isBan ? 'Помиловать' : 'Бан'),
                        Html::ActionPath('account', 'superlogin', array($item['id']))
                    ));
            ?>
            <?php endforeach;?>
        </table>
    </div>
</div>

<div id="modalUpdateUser" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Редактировать пользователя</h4>
            </div>

            <div class="modal-body">
                <form action="<?php echo \app\helpers\Html::ActionPath('Account', 'Update')?>" method="POST" class="ajax-form">
                    <input type="hidden" id="field_id" name="UserData[id]">
                    <div class="form-group">
                        <label>Email:</label>
                        <input class="form-control" type="text" id="field_email" name="UserData[Email:email]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Телефон:</label>
                        <input class="form-control" type="text" id="field_phone" name="UserData[Phone:phone]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Имя:</label>
                        <input class="form-control" type="text" id="field_name" name="UserData[DefaultText:name]">
                        <div class="error-box"></div>
                    </div>
                    <div class="form-group">
                        <label>Роль:</label>
                        <select class="form-control" id="field_role_id" name="UserData[role_id]">
                        <?php
                            foreach($this->Get('role') as $item){
                                echo '<option value="'.$item['id'].'">'.$item['name'].'</option>';
                            }
                        ?>
                        </select>
                        <div class="error-box"></div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>
</div>
