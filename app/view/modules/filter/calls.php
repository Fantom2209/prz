<?php

use \app\core\Config;

$filterPrefix = $this->Get('prefix');

?>
<div class="panel panel-info">
    <div class="panel-heading">Фильтр</div>
    <div class="panel-body">
        <form method="GET">

            <?php $this->SetName(Config::FILTER_CALLS); ?>

            <div class="row">

                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'site'; ?>
                        <label for="<?php echo $itemName; ?>">Сайт: </label>
                        <select class="form-control" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>">
                            <?php
                                $sites = $this->Get('sites');
                                $params = $this->Get('values');
                                var_dump($params);

                                echo '<option value="all">Все</option>';
                                foreach($sites as $item){
                                    echo '<option'.(isset($params[$filterPrefix.$itemName]) && $item['id'] === $params[$filterPrefix.$itemName] ?' selected':'').' value="'.$item['id'].'">'.$item['name'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'telmanager'; ?>
                        <label for="<?php echo $itemName; ?>">Номер менеджера: </label>
                        <input class="form-control" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>" value="<?php echo !empty($params[$filterPrefix.$itemName]) ? $params[$filterPrefix.$itemName] : '';?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'tel'; ?>
                        <label for="<?php echo $itemName; ?>">Номер клиента: </label>
                        <input class="form-control" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>" value="<?php echo !empty($params[$filterPrefix.$itemName]) ? $params[$filterPrefix.$itemName] : '';?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'dateFrom'; ?>
                        <label for="<?php echo $itemName; ?>">C: </label>
                        <input class="form-control dp" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>" value="<?php echo !empty($params[$filterPrefix.$itemName]) ? $params[$filterPrefix.$itemName] : date('Y-n-m');?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'dateTill'; ?>
                        <label for="<?php echo $itemName; ?>">По: </label>
                        <input class="form-control dp" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>" value="<?php echo !empty($params[$filterPrefix.$itemName]) ? $params[$filterPrefix.$itemName] : date('Y-n-m');?>">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <?php $itemName = 'rating'; ?>
                        <label for="<?php echo $itemName; ?>">Рейтинг: </label>
                        <select class="form-control" name="<?php echo $filterPrefix . $itemName;?>" id="<?php echo $itemName; ?>">

                            <?php
                                $items = array(
                                    array('id' => 'all', 'name' => 'Все'),
                                    array('id' => '1', 'name' => '1'),
                                    array('id' => '2', 'name' => '2'),
                                    array('id' => '3', 'name' => '3'),
                                    array('id' => '4', 'name' => '4'),
                                    array('id' => '5', 'name' => '5'),
                                    array('id' => '0', 'name' => 'Без оценки'),
                                );

                                foreach($items as $item){
                                    echo '<option'.($item['id'] === $params[$filterPrefix.$itemName] ?' selected':'').' value="'.$item['id'].'">'.$item['name'].'</option>';
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