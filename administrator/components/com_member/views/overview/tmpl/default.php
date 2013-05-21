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
?>

<form action="<?php echo JRoute::_('index.php?option=com_member&view=overview'); ?>" method="post" name="adminForm" id="adminForm">

                <div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER');?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="Search by date" value="<?php echo $_POST['filter_search']?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button class="btn hasTooltip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button class="btn hasTooltip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="javascript:document.getElementById('filter_search').value= '';this.form.submit()"><i class="icon-remove"></i></button>
			</div>
                    
                    <select style="float:right" id="limit" class="inputbox input-mini chzn-done" onchange="javascript:this.form.submit()" size="1" name="limit" >
                        
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                
		</div>  
    
		<div class="clearfix"> </div>
		<table class="table table-striped" id="creditList">
			<thead>
                            <tr>
                                <th class="left">#</th>
                                <th class="center">Period</th>
                                <th class="center">Transactions made</th>
                                <th class="center">Incoming money</th>
                                <th class="center">Credits earned</th>
                                <th class="center">Credits payout</th>
                                <th class="center">Credits saved</th>
                              
                            </tr>
			</thead>
			<tfoot>
   
			</tfoot>
			<tbody>
                            <?php foreach ($this->items as $i => $item) :?>
				<tr class="row<?php echo $i % 2; ?>">
                                    <td>
                                        <?php echo $i + 1 ?>
                                        
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php echo $item->period ?>
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php echo $item->transactions ? $item->transactions : '0'?>
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php echo $item->incoming_money ? '€' . $item->incoming_money  : '€0.00' ?>
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php echo $item->credits_earned ? '€' . $item->credits_earned : '€0.00' ?>
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php echo $item->credits_payout ? '€' . $item->credits_payout : '€0.00'?>
                                    </td>
                                    <td class="order nowrap center hidden-phone">
                                        <?php
                                        if($item->credits_earned > $item->credits_payout) {
                                            echo '€' . round($item->credits_earned - $item->credits_payout, 1);
                                        } else {
                                            echo '€0.00';
                                        }
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
