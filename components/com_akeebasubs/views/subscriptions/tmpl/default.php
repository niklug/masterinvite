<?php
/**
 *  @package AkeebaSubs
 *  @copyright Copyright (c)2010-2013 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die();
?>
<style>
#akeebasubs select {
    width: 71px !important;
}
</style>
<?php

FOFTemplateUtils::addCSS('media://com_akeebasubs/css/frontend.css?'.AKEEBASUBS_VERSIONHASH);

$this->loadHelper('cparams');
$this->loadHelper('modules');
$this->loadHelper('format');

JLoader::import('joomla.utilities.date');

if (!isset($this->extensions))
{
	$this->extensions = array();
}
?>

<div id="akeebasubs" class="subscriptions">
	
	<form action="<?php echo JRoute::_('index.php?option=com_akeebasubs&view=subscriptions') ?>" method="post" class="adminform" name="adminForm" id="adminForm">
	<input type="hidden" name="<?php echo JFactory::getSession()->getFormToken();?>" value="1" />

	<table class="table table-striped" width="100%">
		<thead>
			<tr>
				<th width="40px">
					<?php echo JText::_('COM_AKEEBASUBS_DATE')?>
				</th>
				<th width="100px">
					<?php echo JText::_('COM_AKEEBASUBS_INVOICE_NUMBER')?>
				</th>
				<th width="60px">
					<?php echo JText::_('COM_AKEEBASUBS_AMOUNT')?>
				</th>
                                <th width="40px">
					<?php echo JText::_('COM_AKEEBASUBS_PAID')?>
				</th>
				<th style="width:220px">
					<?php echo JText::_('COM_AKEEBASUBS_PERIOD')?>
				</th>
                                <th>
					<?php echo JText::_('COM_AKEEBASUBS_DOWNLOAD')?>
				</th>
			
				<th>
					<?php echo JText::_('COM_AKEEBASUBS_ACTION')?>
				</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="20">
					<?php if(($this->pagination->total > 15)) echo $this->pagination->getListFooter() ?>
				</td>
			</tr>
		</tfoot>

		<tbody>
			<?php if(count($this->items)): ?>
			<?php $m = 1; $i = 0; ?>
			<?php foreach($this->items as $subscription):?>
			<?php
				$m = 1 - $m;
				$email = trim($subscription->email);
				$email = strtolower($email);
				$rowClass = ($subscription->enabled) ? '' : 'expired';

				$canRenew = AkeebasubsHelperCparams::getParam('showrenew', 1) ? true : false;
				$level = $this->allLevels[$subscription->akeebasubs_level_id];
				if($level->only_once) {
					if(in_array($subscription->akeebasubs_level_id,$this->subIDs)) {
						$canRenew = false;
					}
				}

				$jPublishUp = new JDate($subscription->publish_up);
			?>
			<tr class="row<?php echo $m?> <?php echo $rowClass?>">
				<td align="left">
					<?php $date = strtotime( $subscription->created_on); echo date('d-m-Y', $date) ?>
				</td>
				<td>
					<?php echo $this->invoices[ $subscription->akeebasubs_subscription_id]->display_number ?>
				</td>
				<td>
                                    <?php if(AkeebasubsHelperCparams::getParam('currencypos','before') == 'before'): ?>
                                    <?php echo AkeebasubsHelperCparams::getParam('currencysymbol','€')?>
                                    <?php endif; ?>
                                    <?php echo sprintf('%2.02F',$subscription->gross_amount)?>
                                    <?php if(AkeebasubsHelperCparams::getParam('currencypos','before') == 'after'): ?>
                                    <?php echo AkeebasubsHelperCparams::getParam('currencysymbol','€')?>
                                    <?php endif; ?>
				
				</td>
                                <td align="center">
                                   
					<?php if($subscription->state == 'C'):?>
					<img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/enabled.png" align="center" title="" />
                                        
                                        <?php elseif($subscription->state == 'N'):?>
					<img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/disabled.png" align="center" title="" />
                                        <?php elseif($subscription->state == 'P'):?>
					<img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/scheduled.png" align="center" title="" />
                                        
					<?php elseif($jPublishUp->toUnix() >= time()):?>
					<img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/scheduled.png" align="center" title="" />
                                        <?php else:?>
					<img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/disabled.png" align="center" title="" />
					<?php endif;?>
                                 </td>
                                
				<td >
					<?php if(empty($subscription->publish_up) || ($subscription->publish_up == '0000-00-00 00:00:00')):?>
					&mdash;
					<?php else:?>
					<?php echo AkeebasubsHelperFormat::date($subscription->publish_up) ?>
					<?php endif;?>
                                        <strong><?php echo JText::_('to')?></strong>
					<?php if(empty($subscription->publish_up) || ($subscription->publish_down == '0000-00-00 00:00:00')):?>
					&mdash;
					<?php else:?>
					<?php echo AkeebasubsHelperFormat::date($subscription->publish_down) ?>
					<?php endif;?>
				</td>
                                <td>
                                        <?php if(array_key_exists($subscription->akeebasubs_subscription_id, $this->invoices)):
                                    
					$invoice = $this->invoices[$subscription->akeebasubs_subscription_id];
					if($invoice->extension == 'akeebasubs')
					{
						$url = JRoute::_('index.php?option=com_akeebasubs&view=invoices&task=download&id='.$invoice->akeebasubs_subscription_id);
					}
					elseif(array_key_exists($invoice->extension, $this->extensions))
					{
						$url = JRoute::_(sprintf($extensions[$invoice->extension]['backendurl'], $invoice->invoice_no));
					}
					else
					{
						$url = '';
					}
					if(!empty($url)):
					?>
					<a style="margin-right:5px;"  href="<?php echo $url; ?>">
                                            <img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/download.jpg" align="center" title="Save invoice" />
                                        </a>
					<?php endif; ?>
					<?php endif; ?>
                                </td>
                                <td >
		
		

                                    <?php if(($subscription->state != 'C') && (in_array($subscription->akeebasubs_level_id, $this->activeLevels))):?>
                                                    <?php if($canRenew): ?>
                                    <?php $slug = FOFModel::getTmpInstance('Levels','AkeebasubsModel')
                                                            ->setId($subscription->akeebasubs_level_id)
                                                            ->getItem()
                                                            ->slug;?>

                                    <a href="javascript:void(0)" onclick="pay_now()">
                                             <button  class="cyan" type="button">PAY NOW</button>
                                    </a>
                                    <?php endif;?>
                                    <?php endif;?>
                                </td>
			</tr>
			<?php endforeach; ?>
			<?php else: ?>
			<tr>
				<td colspan="20">
					<?php echo JText::_('COM_AKEEBASUBS_COMMON_NORECORDS') ?>
				</td>
			</tr>
			<?php endif; ?>
		</tbody>

	</table>
	</form>
</div>