<?php
/**
 * @version     1.0.0
 * @package     com_member
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_member/assets/css/member.css');

$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_member');
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_member&task=credits.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'creditList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<?php
//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_member&view=credits'); ?>" method="post" name="adminForm" id="adminForm">
<?php if(!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
    
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>        
		<div class="clearfix"> </div>
		<table class="table table-striped" id="creditList">
			<thead>
				<tr>
                <?php if (isset($this->items[0]->ordering)): ?>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
                <?php endif; ?>
				
                    
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_AKEEBASUBS_SUBSCRIPTION_ID', 'a.akeebasubs_subscription_id', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_USER_ID', 'a.user_id', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_CREDIT_INVOICE_NUMBER', 'a.credit_invoice_number', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_PUBLISH_UP', 'a.publish_up', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_PUBLISH_DOWN', 'a.publish_down', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_AMOUNT', 'a.amount', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_TRANSACTIONS', 'a.transactions', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_CREDIT_PAID', 'a.credit_paid', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JHtml::_('grid.sort',  'COM_MEMBER_CREDITS_CREATED_ON', 'a.created_on', $listDirn, $listOrder); ?>
				</th>
				<th class='left'>
				<?php echo JText::_('COM_MEMBER_CREDITS_PAYMENT_DATA') ?>
				</th>

                                        <th>Preview</th>
				</tr>
			</thead>
			<tfoot>
                <?php 
                if(isset($this->items[0])){
                    $colspan = count(get_object_vars($this->items[0]));
                }
                else{
                    $colspan = 10;
                }
            ?>
			<tr>
				<td colspan="<?php echo $colspan ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'a.ordering');
                $canCreate	= $user->authorise('core.create',		'com_member');
                $canEdit	= $user->authorise('core.edit',			'com_member');
                $canCheckin	= $user->authorise('core.manage',		'com_member');
                $canChange	= $user->authorise('core.edit.state',	'com_member');
				?>
				<tr class="row<?php echo $i % 2; ?>">
                    
                <?php if (isset($this->items[0]->ordering)): ?>
					<td class="order nowrap center hidden-phone">
					<?php if ($canChange) :
						$disableClassName = '';
						$disabledLabel	  = '';
						if (!$saveOrder) :
							$disabledLabel    = JText::_('JORDERINGDISABLED');
							$disableClassName = 'inactive tip-top';
						endif; ?>
						<span class="sortable-handler hasTooltip <?php echo $disableClassName?>" title="<?php echo $disabledLabel?>">
							<i class="icon-menu"></i>
						</span>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
					<?php else : ?>
						<span class="sortable-handler inactive" >
							<i class="icon-menu"></i>
						</span>
					<?php endif; ?>
					</td>
                <?php endif; ?>
					
                   
				<td>

					<?php echo $item->akeebasubs_subscription_id; ?>
				</td>
				<td>

					<?php echo $item->user_id;?>
				</td>
				<td>
                                        <?php if($item->credit_invoice_number) {
                                            echo $item->credit_invoice_number;
                                        }else {
                                            echo '-';
                                        } ?>
				</td>
				<td>

					<?php echo $item->publish_up; ?>
				</td>
				<td>

					<?php echo $item->publish_down; ?>
				</td>
				<td>

                                        <?php
                                        // amount
                                        $amount =  $this->model->getAmount($item->user_id,
                                                $item->publish_up, 
                                                $item->publish_down,
                                                $item->credit_paid,
                                                $item->amount
                                        );
                                        
                                        echo '€ ' . $amount;
                                            
                                        ?>
				</td>
				<td>

					<?php
                                        // transactions
                                        $transactions = $this->model->getTransactions($item->user_id,
                                                $item->publish_up, 
                                                $item->publish_down,
                                                $item->credit_paid,
                                                $item->transactions
                                        );
                                        echo $transactions
                                        ?>
				</td>
				<td id="paid_<?php echo $item->akeebasubs_subscription_id; ?>">
                                    
                                    <?php if($item->credit_paid != '1'):?>
                                    <a href="javascript:void(0)" 
                                       onclick="payCredit(
                                           '<?php echo $item->user_id; ?>',
                                           '<?php echo $amount ?>',
                                           '<?php echo $transactions ?>',
                                           '<?php echo $item->akeebasubs_subscription_id; ?>',
                                           '<?php echo JSession::getFormToken(); ?>=1'
                                       )"
                                    >
                                     <?php endif;?>
                                       
                                    	<?php if($item->credit_paid == '1'):?>
					<img src="<?php echo JURI::root(); ?>/media/com_akeebasubs/images/frontend/enabled.png" align="center" title="" />
                                        
                                        <?php else :?>
					<img src="<?php echo JURI::root(); ?>/media/com_akeebasubs/images/frontend/disabled.png" align="center" title="" />
					<?php endif;?>
                                    <?php if($item->credit_paid != '1'):?>
                                    </a>
                                    <?php endif;?>

				</td>
				<td>

					<?php echo $item->created_on; ?>
				</td>
				<td>

					<?php 
                                        // payment method
                                        echo $this->model->getPaymentMethod($item->user_id);
                                        ?>
				</td>


                                        <td>
                                            <?php 
                                            echo $this->model->showPreview($item);
                                            ?>
                                            
                                        </td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>  

<?php
