<?php
    use \app\helpers\Html;
    $filterPrefix = $this->Get('prefix');
?>
<div class="panel panel-info">
    <div class="panel-heading">Фильтр</div>
    <div class="panel-body">
        <form method="GET">
            <!--<input type="hidden" value="<?php echo $this->Get('page'); ?>" name="page">-->
            <div class="row">
                <div class="col-md-2">

                    <div class="form-group">
                        <label for="group">Группа: </label>
                        <select class="form-control" name="<?php echo $filterPrefix;?>sGroup" id="group">
                            <?php
                                $groups = $this->Get('groups');
                                $params = $this->Get('values');

                                foreach($groups as $item){
                                    echo '<option'.($item['id'] === $params[$filterPrefix.'sGroup'] ?' selected':'').' value="'.$item['id'].'">'.$item['name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button class="btn btn-default">Применить</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>