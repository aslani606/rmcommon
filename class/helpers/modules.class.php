<?php
// $Id$
// --------------------------------------------------------------
// Common Utilities 2
// A modules framework by Red Mexico
// Author: Eduardo Cortes
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// URI: http://www.redmexico.com.mx
// --------------------------------------------------------------

/**
 * This class contains methods that allow to work with modules directly
 */
class RMModules
{
    /**
     * Retrieves and format the version of a given module.
     * If $module parameter is not given, then will try to get the current module version.
     *
     * Example with <strong>formatted</strong> result:
     * <pre>
     * $version = RMModules::get_module_version('mywords');
     * </pre>
     *
     * Get the same result:
     * <pre>
     * $version = RMModules::get_module_version('mywords', true, 'verbose');
     * </pre>
     *
     * Both previous examples return something like this:
     * <pre>MyWords 2.1.3 production</pre>
     *
     * Example 2. Get an array of values:
     * <pre>
     * print_r(RMModules::get_module_version('mywords', true, 'raw');
     * </pre>
     *
     * Will return:
     * <pre>
     * array
     *
     * @param string $module Module directory to check
     * @param bool $name Return the name and version (true) or only version (false)
     * @param string $type Type of data to get: 'verbose' get a formatted string, 'raw' gets an array with values.
     * @return array|string
     */
    static public function get_module_version( $module = '', $name = true, $type = 'verbose' ){
        global $xoopsModule;

        //global $version;
        if ( $module != '' ){

            if ( $xoopsModule && $xoopsModule->dirname() == $module ){
                $mod = $xoopsModule;
            } else {
                $mod = new XoopsModule();
            }

        } else {
            $mod = new XoopsModule();
        }

        $mod->loadInfoAsVar( $module );
        $version = $mod->getInfo( 'rmversion' );
        $version = is_array( $version ) ? $version : array('major'=>$version, 'minor'=>0, 'revision'=>0, 'stage' => 0, 'name'=>$mod->getInfo('name'));

        if ($type==1)
            return $version;

        return self::format_module_version($version, $name);

    }

    /**
     * Format a given array with version information for a module.
     *
     * @param array $version Array with version values
     * @param bool $name Include module name in return string
     * @return string
     */
    static public function format_module_version($version, $name = false){

        $rtn = '';

        if ( $name )
            $rtn .= ( defined( $version['name'] ) ? constant( $version['name'] ) : $version['name'] ) . ' ';

        // New versioning
        if ( isset( $version['major'] ) ){
            $rtn .= $version['major'];
            $rtn .= '.'.$version['minor'];
            $rtn .= '.'.($version['revision']/10);
            switch( $version['stage'] ){
                case -3:
                    $rtn .= ' alfa';
                    break;
                case -2:
                    $rtn .= ' beta';
                    break;
                case -1:
                    $rtn .= ' RC';
                    break;
                default:
                    $rtn .= ' production';
                    break;
            }
            return $rtn;
        }

        // Format version of a module with previous versioning system
        $rtn .= $version['number'];

        if ( $version['revision'] > 0 )
            $rtn .= '.' . ( $version['revision'] / 100 );
        else
            $rtn .= '.0';

        switch( $version['status'] ){
            case '-3':
                $rtn .= ' alfa';
                break;
            case '-2':
                $rtn .= ' beta';
                break;
            case '-1':
                $rtn .= ' final';
                break;
            case '0':
                break;
        }

        return $rtn;
    }

    /**
     * Load a given Xoops Module
     * @param string|integer $id Indentifier of module. Could be dirname or numeric ID
     * @return XoopsModule
     */
    static public function load_module( $id ){

        $module_handler = xoops_gethandler('module');

        if ( is_numeric( $id ) )
            $module = $module_handler->get( $id );
        else
            $module = $module_handler->getByDirname( $id );

        return $module;
    }

    /**
     * Retrieves the list of installed modules
     *
     * Accepted $status values: 'all', 'active', 'inactive'
     *
     * @param string $status Type of modules to get
     * @return array
     */
    static public function get_modules_list( $status = 'all' ){

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $active = null;

        if ( $status == 'inactive' )
            $active = 0;
        elseif ( $status=='active' )
            $active = 1;

        $sql = "SELECT mid, name, dirname FROM " . $db->prefix("modules");

        if( isset( $active ) )
            $sql .= " WHERE isactive=$active";

        $sql .= " ORDER BY name";
        $result = $db->query($sql);
        $modules = array();

        while ( $row = $db->fetchArray( $result ) ) {
            $modules[] = $row;
        }

        return $modules;
    }

    /**
     * Create the files to store the modules list
     */
    static function build_modules_cache(){

        $modules = XoopsLists::getModulesList();

        print_r($modules);

    }

}