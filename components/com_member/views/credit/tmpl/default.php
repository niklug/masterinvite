<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div id="creditscontent" >
        <div class="btn-group pull-right">
            <?php
            if(count($this->items) > 10) {
            ?>
            <select name="banner_lang" class="input-medium" onchange="sortLanguage(this.value)">
                <option value="<?php echo count($this->items) ?>"><?php echo count($this->items) ?></option>
                <option value="25">10</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all"><?php echo JText::_('COM_MEMBER_ALL'); ?></option>
            </select>
            <?php
            }
            ?>
        </div>
        <br/> <br/>

        <table class="table table-striped" id="bannerList">
            <thead>
                <tr>
                    <th width="1%" class="nowrap center hidden-phone">
                        #
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_PERIOD'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_CREDITNOTA_NUMBER'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_AMOUNT'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_TRANSACTIONS'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_PAID'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_DOWNLOAD'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $c = 0;
                foreach ($this->items as $i => $item) : 
                    
                    $c++;
                ?>
                    <tr>
                        <td class="center">
                            <?php echo $c; ?>
                        </td>
                        <td class="center">
                            <?php echo $item->publish_up . ' - ' .  $item->publish_down ?>
                        </td>
                            
   
                        <td>
                            <?php if ($item->credit_invoice_number ) {
                                echo $item->credit_invoice_number; 
                            } else {
                                echo '-';
                            } ?>
                        </td>
                        
                        <td>
                            <?php if($item->ammount) {
                                echo '€ ' . $item->ammount;
                            } else {
                                echo '€ ' . '0.00';
                            } 
                            ?>
                        </td>
                        
                        <td>
                            <?php if($item->transactions) {
                                echo $item->transactions;
                            } else {
                                echo '0';
                            } 
                            ?>
                        </td>
                        
                        <td align="center">
                            <?php if($item->credit_paid):?>
                            <img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/enabled.png" align="center" title="" />
                            <?php else:?>
                            <img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/frontend/disabled.png" align="center" title="" />
                            <?php endif;?>
                        </td>
                        
                        <td>
                            <?php
                            if($item->file) {
                                $url = JRoute::_('index.php?option=com_akeebasubs&view=invoices&task=download&type=creditnota&file=' . $item->file . '&id='.$item->credit_invoice_number);
                            ?>
                            <a style="margin-right:5px;"  href="<?php echo $url; ?>">
                                <img src="<?php echo JURI::base(); ?>/media/com_akeebasubs/images/download.jpg" align="center" title="<?php echo JText::_('COM_MEMBER_SAVE_CREDITNOTA'); ?>" />
                            </a>
                            <?php }
                            ?>
                        </td>
                             </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

    
    <script type="text/javascript">
        function sortLanguage(rows) {
            var url = "index.php?option=com_member&view=credit&tmpl=component&rows=" + rows; 

            jQuery.ajax({
                type: "POST",
                url: url,
                success: function(data)
                {
                    jQuery("#creditscontent").html(data);
               
                }
            });

        }

    </script>

</div>