<?php
// $Id: right_widgets.php 902 2012-01-03 07:09:16Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rmc_available_mods(){
	global $available_mods, $xoopsSecurity;
	
	$ret['title'] = __('Available Modules','rmcommon');
	$ret['icon'] = RMCURL.'/images/modules.png';
    
    $limit = 7;
    $tpages = ceil(count($available_mods)/$limit);
    
    $nav = new RMPageNav(count($available_mods), $limit, 1, 3);
    $nav->target_url('#" onclick="load_page({PAGE_NUM});');
	
	ob_start();
    $i = 0;
?>
	<div class="rmc_widget_content_reduced rmc_modules_widget">
        <img id="img-load" src="images/loading.gif" style="display: none; margin: 15px auto;" />
        <div id="mods-widget-container">
            <ul class="list-unstyled">
                <?php foreach($available_mods as $mod): ?>
                <?php if($i==$limit) break; ?>
                    <li>
                        <a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>" class="rmc_mod_img">
                            <img src="<?php echo XOOPS_URL; ?>/modules/<?php echo $mod->getInfo('dirname'); ?>/<?php echo $mod->getInfo('image'); ?>" alt="<?php echo $mod->getInfo('dirname'); ?>">
                        </a>
                        <strong><a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php echo $mod->getInfo('name'); ?></a></strong>
                        <span class="rmc_available_options">
                            <a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php _e('Install','rmcommon'); ?></a> |
                            <a href="javascript:;" onclick="show_module_info('<?php echo $mod->getInfo('dirname'); ?>');"><?php _e('More info','rmcommon'); ?></a>
                        </span>
                        <div class="rmc_mod_info" id="mod-<?php echo $mod->getInfo('dirname'); ?>">
                            <h4><?php echo $mod->getInfo('name'); ?></h4>
                            <span class="help-block">
                                <?php echo $mod->getInfo('description'); ?>
                            </span>
                            <table class="table">
                                <tr>
                                    <td><?php _e('Version:','rmcommon'); ?></td>
                                    <td>
                                        <?php if($mod->getInfo('rmnative')): ?>
                                            <strong><?php echo RMModules::format_module_version($mod->getInfo('rmversion')); ?></strong>
                                        <?php else: ?>
                                            <strong><?php echo $mod->getInfo('version'); ?></strong>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php _e('Author:', 'rmcommon'); ?>
                                    </td>
                                    <td>
                                        <strong><?php echo substr(strip_tags($mod->getInfo('author')), 0, 12); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?php _e('Web site:', 'rmcommon'); ?>
                                    </td>
                                    <td>
                                        <a target="_blank" href="<?php echo $mod->getInfo('authorurl'); ?>"><?php echo $mod->getInfo('authorweb'); ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Updatable:', 'rmcommon'); ?></td>
                                    <td>
                                        <?php if( $mod->getInfo('updateurl') != '' ): ?>
                                            <span class="fa fa-check"></span>
                                        <?php else: ?>
                                            <span class="fa fa-times"></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('License:', 'rmcommon'); ?></td>
                                    <td>
                                        <?php echo $mod->getInfo('license'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('XOOPS Official:', 'rmcommon'); ?></td>
                                    <td>
                                        <?php if ( $mod->getInfo('official') ): ?>
                                            <span class="fa fa-check"></span>
                                        <?php else: ?>
                                            <span class="fa fa-times"></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('C.U. Native:', 'rmcommon'); ?></td>
                                    <td>
                                        <?php if ( $mod->getInfo('rmnative') ): ?>
                                            <span class="fa fa-check"></span>
                                        <?php else: ?>
                                            <span class="fa fa-times"></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Directory:', 'rmcommon'); ?></td>
                                    <td>
                                        <strong><?php echo $mod->getInfo('dirname'); ?></strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php _e('Released:', 'rmcommon'); ?></td>
                                    <td>
                                        <?php if ( $mod->getInfo('releasedate') != '' ): ?>
                                            <?php
                                            $time = strtotime( $mod->getInfo('releasedate') );
                                            echo formatTimestamp( $time, 's' );
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if ( $mod->getInfo('help') != '' && $mod->getInfo('rmnative') ): ?>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <strong><a href="<?php echo $mod->getInfo('help'); ?>" target="_blank"><?php _e('Get Help', 'rmcommon'); ?></a></strong>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="2" class="contact-options text-center">
                                        <?php if ( $mod->getInfo('authormail') != '' ): ?>
                                            <a target="_blank" href="mailto:<?php echo $mod->getInfo('authormail'); ?>"><span class="fa fa-envelope"></span></a>
                                        <?php endif; ?>
                                        <?php foreach( $mod->getInfo('social') as $social ): ?>
                                            <a target="_blank" href="<?php echo $social['url']; ?>"><span class="fa fa-<?php echo $social['type']; ?>-square"></span></a>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-center">
                                        <a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>" class="btn btn-success btn-sm"><?php _e('Install','rmcommon'); ?></a>
                                        <a href="#" onclick="$(this).parents('.rmc_mod_info').slideToggle('fast'); return false;" class="btn btn-warning btn-sm"><?php _e('Close','rmcommon'); ?></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php $i++; endforeach; ?>
                    </li>
            </ul>
        <?php $nav->display(false); ?>
            <input type="hidden" id="token" value="<?php echo $xoopsSecurity->createToken(); ?>" />
        </div>
	</div>
<?php
	$ret['content'] = ob_get_clean();
	return $ret;
	//print_r($available_mods);
	
}

/**
* Show the widget with blocks positions
*/
function rmc_blocks_new(){
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    
    $blocks = RMBlocksFunctions::get_available_list($modules);
    
    // Get intalled modules
    $result = $db->query("SELECT * FROM ".$db->prefix("modules")." WHERE isactive=1 ORDER BY `name`");
    while($row = $db->fetchArray($result)){
        $modules[] = array('dir'=>$row['dirname'], 'name'=>$row['name']);
    }
    
    // Cargamos los grupos
    $sql = "SELECT groupid, name FROM " . $db->prefix("groups") . " ORDER BY name";
    $result = $db->query($sql);
    $groups = array();
    while ($row = $db->fetchArray($result)) {
        $groups[] = array('id' => $row['groupid'], 'name' => $row['name']);
    }
    
    $widget['title'] = 'Add Block';
    $widget['icon'] = '';
    ob_start();
    include RMTemplate::get()->get_template('widgets/rmc_aw_bknew.php');
    $widget['content'] = ob_get_clean();
    return $widget;
}

/**
* Add new position
*/
function rmc_blocks_addpos(){
    global $xoopsSecurity;
    
    $widget['title'] = 'Add Position';
    $widget['icon'] = '';
    
    $positions = RMBlocksFunctions::block_positions();
    
    ob_start();
    include RMTemplate::get()->get_template('widgets/rmc_aw_posnew.php','module','rmcommon');
    $widget['content'] = ob_get_clean();
    return $widget;
    
}