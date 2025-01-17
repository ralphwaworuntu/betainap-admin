<?php


$transactions = $result[Tags::RESULT];
$pagination = $result[Tags::PAGINATION];


?>

<div class="box box-solid">

    <div class="box-header">
        <div class="box-title">
            <strong><?=Translate::sprint("Transactions")?></strong>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive">
        <table id="example2" class="table table-bordered table-hover">
            <thead>
            <tr>
                <!--    <th>ID</th>-->
                <th width="20%"><?=Translate::sprint("TransactionID","")?></th>
                <th width="25%"><?=Translate::sprint("Client")?></th>
                <th width="25%"><?=Translate::sprint("Operation")?></th>
                <th width="10%"><?=Translate::sprint("Amount","")?></th>
                <th width="20%"><?=Translate::sprint("Date","")?></th>
            </tr>
            </thead>
            <tbody>
            <?php  if(count($transactions)>0): ?>

                <?php foreach ($transactions as $value): ?>
                    <tr>
                        <td><?=$value['no']?></td>
                        <?php if(SessionManager::getData("manager")==1): ?>
                            <td>
                            <?php if($value['from']['username']!="--" && $value['from']['username']!="--"): ?>
                                <?php if($value['operation']=="send"): ?>
                                    @<?=$value['client']['username']?>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;@<?=$value['from']['username']?>
                                <?php elseif($value['operation']=="receive"): ?>
                                    @<?=$value['from']['username']?>&nbsp;&nbsp;&gt;&gt;&nbsp;&nbsp;@<?=$value['client']['username']?>
                                <?php endif;?>
                            <?php endif; ?>

                        </td>
                        <?php elseif(SessionManager::getData("id_user") == $value['user_id']): ?>
                            <td>
                                <?php
                                    $sender = $this->mWalletModel->getSenderByTranId($value['no']);
                                    if($sender != NULL){
                                        echo "@".$sender['username'];
                                    }else{
                                        echo "@".$value['client']['username'];
                                    }
                                ?>
                            </td>
                        <?php else: ?>
                            <td><?= SessionManager::getData("manager")==1?$value['client']['email']:'@'.$value['client']['username']  ?></td>
                        <?php endif; ?>
                        <td>
                            <?php if($value['operation']=="send"): ?>
                                <span class="text-red"><i class="mdi mdi-arrow-up"></i>&nbsp;
                                    <?php if($value['note']!="--" and $value['note']!=""  ): ?>
                                        <?=$value['note']?>
                                    <?php else: ?>
                                        <?=_lang("Sent")?>
                                    <?php endif; ?>
                                </span>
                            <?php elseif($value['operation']=="receive"): ?>
                                <span class="text-green"><i class="mdi mdi-arrow-down"></i>&nbsp;
                                    <?php if($value['note']!="--" and $value['note']!=""  ): ?>
                                        <?=$value['note']?>
                                    <?php else: ?>
                                        <?=_lang("Received")?>
                                    <?php endif; ?>
                                </span>
                            <?php elseif($value['operation']=="top-up"): ?>
                                <span class="text-green"><i class="mdi mdi-arrow-down"></i>&nbsp;<?=_lang("Top-up")?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($value['operation']=="send"): ?>
                                <span class="text-red">-<?=Currency::parseCurrencyFormat($value['amount'],$value['currency'])?></span>
                            <?php elseif($value['operation']=="receive"): ?>
                                <strong class="text-green">+<?=Currency::parseCurrencyFormat($value['amount'],$value['currency'])?></strong>
                            <?php elseif($value['operation']=="top-up"): ?>
                                <strong class="text-green">+<?=Currency::parseCurrencyFormat($value['amount'],$value['currency'])?></strong>
                            <?php endif; ?>

                        </td>
                        <td><?=date("Y-m-d H:i",strtotime($value['created_at']))?></td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>
        </table>

        <div class="row">
            <div class="col-sm-12 pull-right">
                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">

                    <?php

                    echo $pagination->links(array(
                        "page"    =>RequestInput::get("page"),
                        "status"    =>RequestInput::get("status"),
                    ),admin_url("payment/transactions"));

                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
