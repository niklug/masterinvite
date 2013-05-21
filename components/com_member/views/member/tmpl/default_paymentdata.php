<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$payment_data = $this->payment_data;

$is_holland = $this->is_holland;

?>
<div id="paymentdata">
    <form id="save_payment_method" method="post" action="">
        <p><?php echo JText::_('COM_MEMBER_PAYMENTDATA_TITLE') ?></p>
        <fieldset>
            <div class="fieldgroup">
                <label class="grouplabel"  for="paymentway"><?php echo JText::_('COM_MEMBER_SAVING') ?></label>
                <input class="groupinput"
                    onclick="choosePayoutMethod(this.value)" 
                    <?php if($payment_data->payment_method == '1') echo  'checked="yes"'?>
                    <?php if(!$payment_data->payment_method) echo  'checked="yes"'?>
                    type="radio"
                    name="payment_method"
                    value="1"
                />
            </div>
        </fieldset>
        <?php if($is_holland) { ?>
        <fieldset>
            <div class="fieldgroup">
                <label class="grouplabel"  for="paymentway"><?php echo JText::_('COM_MEMBER_PAYOUT_TO_BANK') ?></label>
                <input class="groupinput"
                       onclick="choosePayoutMethod(this.value)"
                       <?php if($payment_data->payment_method == '2') echo  'checked="yes"'?> 
                       type="radio"
                       name="payment_method"
                       value="2"
                 />
            </div>
        </fieldset>
        <?php } ?>
        <fieldset>
            <div class="fieldgroup">
                <label class="grouplabel"  for="paymentway"><?php echo JText::_('COM_MEMBER_PAYOUT_TO_PAYPAL') ?></label>
                <input class="groupinput"
                       onclick="choosePayoutMethod(this.value)" 
                       <?php if($payment_data->payment_method == '3') echo  'checked="yes"'?> 
                       type="radio"
                       name="payment_method"
                       value="3"
                />
            </div>
        </fieldset>
        </br>
        
        <div id="saving"  <?php if($payment_data->payment_method != '1') echo  'style="display:none;"'?> ><?php echo JText::_('COM_MEMBER_PAYOUT_SAVING_CONDITION') ?></div>
        
        <div id="tobank" <?php if($payment_data->payment_method != '2') echo  'style="display:none;"'?>>
            <fieldset>
                <div class="fieldgroup">
                    <label for="accountholder"><?php echo JText::_('COM_MEMBER_PAYOUT_BANK_ACCOUNT_HOLDER') ?>:</label>
                    <input id="accountholder"
                           name="payment_bank_account_holder"
                           value="<?php echo $payment_data->payment_bank_account_holder ?>"
                    />
                </div>
            </fieldset>
            <fieldset>
                <div class="fieldgroup">
                    <label for="accountnumber"><?php echo JText::_('COM_MEMBER_PAYOUT_BANK_ACCOUNT_NUMBER') ?>:</label>
                    <input id="accountnumber" 
                           name="payment_bank_account_number"
                           value="<?php echo $payment_data->payment_bank_account_number ?>"
                    />
                </div>
            </fieldset>
            <fieldset>
                <div class="fieldgroup">
                    <label for="ibannumber"><?php echo JText::_('COM_MEMBER_PAYOUT_BANK_IBAN_NUMBER') ?>:</label>
                    <input id="ibannumber"
                           name="payment_bank_iban_number"
                           value="<?php echo $payment_data->payment_bank_iban_number ?>"
                    />
                </div>
            </fieldset>
        </div>
        
        <div id="topaypal" <?php if($payment_data->payment_method != '3') echo  'style="display:none;"'?>>
            <fieldset>
                <div class="fieldgroup">
                    <label for="paypaladress"><?php echo JText::_('COM_MEMBER_PAYOUT_PAYPAL_ADRESS') ?>:</label>
                    <input id="paypaladress"
                           name="payment_paypal_adress" 
                           value="<?php echo $payment_data->payment_paypal_adress ?>"
                    />
                </div>
            </fieldset>
            
        </div>
        <fieldset>
            <div class="savesettings">
                <button id="request_submit_payment"
                        
                        class="cyan"><?php echo JText::_('COM_MEMBER_PAYOUT_SAVE _SETTINGS') ?>
                </button> 
            </div>
        </fieldset>
        <?php echo JHtml::_('form.token'); ?>
    </form>
    
</div>
