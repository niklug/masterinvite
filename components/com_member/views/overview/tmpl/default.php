<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div id="overview_page">
    <div id="link_ballance">
        <div id="affiliate_link">
            <?php echo '<strong>' . JText::_('COM_MEMBER_YOUR_AFFILIATE_LINK') . '</strong>' . ': ' . JURI::base() . '?aid=' . $this->username; ?>
        </div>
        <div id="overview_ballance">
            <?php echo '<strong>' . JText::_('COM_MEMBER_YOUR_BALLANCE') . ': €' . $this->billing_model->getUserCreditsBallance($this->user_id) . '</strong>' ?>
        </div>
    </div>
    <br/>
    <div id=""access_code >
        <?php echo '<strong>' . JText::_('COM_MEMBER_YOUR_ACCESS_CODE') . ': </strong> ' . $this->username ?>
    </div>
    <div id="number_members" >
        <?php echo '<strong>' . JText::_('COM_MEMBER_NUMBER_ACTIVE_MEMBER') . ': </strong> ' . $this->model->getNumberActiveMembers($this->user_id) ?>
    </div>
    <br/>
    <div id="graph_wrapper">
        <?php echo '<strong>' . JText::_('COM_MEMBER_OVERVIEW_GRAPH_TITLE') . '</strong> ' ?>
    </div>



    <?php
    $daily_amount = array_reverse($this->model->creditsGraphicData($this->user_id, 15));


    ?>


        <table id="histogram" style="display:none; height: 200px;" >
            <caption></caption>
            <thead>
                <tr>
                    <?php
                    foreach ($daily_amount as $month => $amount) {
                        echo '<th>' . $month . '</th>';

                    }
                    ?>
                    
                 </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?php echo  JText::_('COM_MEMBER_CREDIT_AMOUNT') ?> (€)</th>
                          <?php
                    foreach ($daily_amount as $month => $amount) {
                        echo '<td>' . $amount . '</td>';

                    }
                    ?>
                </tr>
            </tbody>
        </table>
 


</div>
<script type="text/javascript">
jQuery("#loader").show();

jQuery(function() {
    jQuery("#loader").hide();
});
    
 </script>