<?php
/**
 * @version     1.0.0
 * @package     com_member_banner
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Nikolay Korban <niklug@ukr.net> - http://
 */
// no direct access
defined('_JEXEC') or die;
$sortFields = $this->getSortFields();
?>
<div id="bannerscontent" >
    <form action="<?php echo JRoute::_('index.php?option=com_member_banner&view=banners'); ?>" method="post" name="adminForm" id="adminForm">
        <br/><br/>
         <div class="btn-group pull-right">
  
            <select name="limit" class="input-medium" onchange="changeLimit(this.value)">
                <option value="<?php echo count($this->items) ?>"><?php echo count($this->items) ?></option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="all"><?php echo JText::_('COM_MEMBER_BANNER_BANNERS_ALL'); ?></option>
            </select>
          
        </div>
        <div class="btn-group pull-right">
            
            <select name="banner_lang" class="input-medium" onchange="sortLanguage(this.value)">
                <option value=""><?php echo JText::_('COM_MEMBER_BANNER_BANNERS_LANGUAGE_FILTER'); ?></option>
                <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
            </select>
        </div>
        <table class="table table-striped" id="bannerList">
            <thead>
                <tr>
                    <th width="1%" class="nowrap center hidden-phone">
                        #
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_NAME'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_LANGUAGE'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_WIDTH'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_HEIGHT'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_PREVIEW'); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_MEMBER_BANNER_BANNERS_GET_CODE'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr>
                        <td class="center hidden-phone">
                            <?php echo (int) $item->id; ?>
                        </td>
                        <td class="center hidden-phone">
                            <?php echo $item->name; ?>
                        </td>
                        <td>

                            <?php switch ($item->language) {
                                    case 'dutch' :
                                        echo JText::_('COM_MEMBER_BANNER_BANNERS_DUTCH');

                                        break;
                                    case 'english' :
                                        echo JText::_('COM_MEMBER_BANNER_BANNERS_ENGLISH');

                                        break;
                                    case 'spanish' :
                                        echo JText::_('COM_MEMBER_BANNER_BANNERS_SPANISH');

                                        break;

                                    default:
                                        break;
                                }?>
                        </td>
                        <td>

                            <?php echo $item->width; ?>
                        </td>
                        <td>

                            <?php echo $item->height; ?>
                        </td>
                        <td style="width:200px;">
                            
                            <?php echo $this->showPreview($item->id, $item->filename, $item->width, $item->height); ?>

                        </td>
                        <td class="center">
                            <a onclick="showCode('<?php echo $item->id ?>')" href="javascript:void(0)"><?php echo JText::_('COM_MEMBER_BANNER_BANNERS_GET_CODE'); ?></a>
                            <?php echo $this->showCode($item->id, $item->filename, $item->width, $item->height); ?>
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

    </form>   
    
    <script type="text/javascript">
        function sortLanguage(lang) {
            var url = "index.php?option=com_member_banner&view=banners&tmpl=component&banner_lang=" + lang; 
            jQuery("#loader").show();
            jQuery.ajax({
                type: "POST",
                url: url,
                success: function(data)
                {
                    
                    jQuery("#bannerscontent").html(data);
                    jQuery("#loader").hide();
               
                }
            });

        }
        
        
        function changeLimit(limit) {
            var url = "index.php?option=com_member_banner&view=banners&tmpl=component&limit=" + limit; 
            jQuery("#loader").show();
            jQuery.ajax({
                type: "POST",
                url: url,
                success: function(data)
                {
                    
                    jQuery("#bannerscontent").html(data);
                    jQuery("#loader").hide();
               
                }
            });

        }
        
        
        function previewShow(id) {
            jQuery(".preview_" + id).show();
        }

        function hidePreview(id) {
            jQuery(".preview_" + id).hide();
        }
        
        function showCode(id){
            jQuery(".code_" + id).show();
        }
        
        function hideCode(id) {
            jQuery(".code_" + id).hide();
        }
    </script>

</div>