<?php

/*
 * the main library for InterBox PHP UI Model
 *
 * It provides a set of functions for resource management that enables multiple
 * themes and languages, and a Page-Process-Box model that separates logics from
 * views, using cache module in the Core to ensure a performant web application
 * built upon this library.
 * Note that the library is dependent on the core1.lib.php and a part of
 * InterBox Core.
 * @version 0.4.20120704
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @license MIT License
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */

//-----------------------------------------------------------
//resource management
//-----------------------------------------------------------
/**
 * gets a relative path to a resource file in the system level
 *
 * @param string $resname   a resource file name in the specified path
 * @param string $path      a path relative to the configured system root
 * For example,
 * if /var/www is the configured system root
 * and the resource file is /var/www/scripts/calendar.js,
 * the path is "scripts".
 * @return string   gives a path to the resource file relative to the
 * configured system root, so it can be used in html; may give an empty string
 * indicating that the resource file does not exist
 */
function GetSysResPath($resname, $path) {
    $syspath = "";
    if (defined("IBC1_SYSTEM_ROOT"))
        $syspath = IBC1_SYSTEM_ROOT;
    $sysrespath = $path . "/" . $resname;
    if (is_file($syspath . $sysrespath))
        return $sysrespath;
    return "";
}

/**
 * gets a relative path to a resource file in the theme level
 *
 * @param string $resname   a resource file name
 * @param string $restype   images, stylesheets, scripts
 * @param int $themeid  optional
 * @return string
 */
function GetThemeResPath($resname, $restype, $themeid = NULL) {
    //set default theme id
    if (empty($themeid))
        $themeid = GetThemeID();

    //get system path
    $syspath = "";
    if (defined("IBC1_SYSTEM_ROOT"))
        $syspath = IBC1_SYSTEM_ROOT;

    //get relative path
    $sysrespath = $restype . "/" . $resname;
    $themerespath = "themes/" . $themeid . "/" . $sysrespath;

    //validate
    if (is_file($syspath . $themerespath))
        return $themerespath;
    if (is_file($syspath . $sysrespath))
        return $sysrespath;

    //not found
    return "";
}

/**
 * gets an absolute path to a directory where cache files are stored
 *
 * @param bool $makedir  optional
 * @param int $themeid  optional
 * @param string $lang   optional
 */
function GetCachePath($makedir = FALSE, $themeid = NULL, $lang = NULL) {
    //set default values
    if (empty($lang))
        $lang = GetLang();
    $lang = strtolower($lang);
    if (empty($themeid))
        $themeid = GetThemeID();
    $themeid = intval($themeid);

    //get system path
    $syspath = "";
    if (defined("IBC1_SYSTEM_ROOT"))
        $syspath = IBC1_SYSTEM_ROOT;

    //get relative path
    $cachepath = "cache/" . $themeid . "/" . $lang;

    //validate
    if (is_dir($syspath . $cachepath)) {
        return $syspath . $cachepath;
    } else if ($makedir) {
        if (!is_dir($syspath . "cache/" . $themeid))
            mkdir($syspath . "cache/" . $themeid);
        if (mkdir($syspath . $cachepath))
            return $syspath . $cachepath;
    }

    //not found
    return "";
}

/**
 * gets an absolute path to a directory where template files are stored
 *
 * @param string $tplname
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string
 */
function GetTplPath($tplname, $classname = NULL, $themeid = NULL, $lang = NULL) {

    $filename = "";
    if (IBC1_MODE_DEV) {//developer's mode
        $filename = $tplname . ".tpl";
    } else if (!empty($classname)) {
        //store all templates for one class in 1 php file
        //use the name of the class as its name
        $filename = $classname . ".tpl.php";
    } else {
        $filename = "default.tpl.php";
    }

    if (empty($themeid))
        $themeid = GetThemeID();
    $themeid = intval($themeid);

    //get system path
    $syspath = "";
    if (defined("IBC1_SYSTEM_ROOT"))
        $syspath = IBC1_SYSTEM_ROOT;

    //get relative path
    $sysrespath = "templates/";
    $themerespath = "themes/" . $themeid . "/templates/";

    if (!empty($classname))
        $classname.="/";
    else
        $classname = "";

    if ($lang == NULL)
        $lang = GetLang();
    else
        $lang = strtolower($lang);

    while (TRUE) {
        if ($lang != "neutral") {
            $tplpath = $syspath . $themerespath . $classname . $lang . "/" . $filename;
            if (is_file($tplpath)) {
                return $tplpath;
            }
            $tplpath = $syspath . $sysrespath . $classname . $lang . "/" . $filename;
            if (is_file($tplpath)) {
                return $tplpath;
            }
        }
        if ($lang != IBC1_DEFAULT_LANGUAGE) {
            //neutral
            $tplpath = $syspath . $themerespath . $classname . $filename;
            if (is_file($tplpath)) {
                return $tplpath;
            }
            $tplpath = $syspath . $sysrespath . $classname . $filename;
            if (is_file($tplpath)) {
                return $tplpath;
            }
            $lang = IBC1_DEFAULT_LANGUAGE;
        } else {
            //not found
            return "";
        }
    }
}

//-----------------------------------------------------------
//variables
//-----------------------------------------------------------
LoadIBC1Class('ICacheReader', 'cache');
LoadIBC1Class('PHPCacheReader', 'cache.phpcache');

/**
 * gets theme id
 *
 * choose user preferred one or the system default one
 * @return int
 */
function GetThemeID() {
    $key = IBC1_PREFIX . '_ThemeID';

    if (isset($GLOBALS[$key]))
        return $GLOBALS[$key];

    $id = strCookie('Style');

    if ($id == '')
        $GLOBALS[$key] = 1; //default & preserved theme id
    else
        $GLOBALS[$key] = intval($id);

    return $GLOBALS[$key];
}

/**
 * gets language code according to http request and system default setting
 *
 * @return string
 */
function GetLang() {
    $key = IBC1_PREFIX . '_Language';

    //not first time
    if (isset($GLOBALS[$key]))
        return $GLOBALS[$key];

    //by preference
    if (strCookie('Lang') != '' && is_dir('lang/' . strCookie('Lang'))) {
        $GLOBALS[$key] = strCookie('Lang');
        return $GLOBALS[$key];
    }

    //by browser
    $l = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    $l = explode(',', $l[0]);
    foreach ($l as $lang) {
        $lang = strtolower($lang);
        if (is_dir('lang/' . $lang)) {
            $GLOBALS[$key] = $lang;
            return $GLOBALS[$key];
        }
        $pos = strpos($lang, '-');
        if ($pos > 0) {
            //e.g. zh-cn
            $lang = substr($lang, 0, $pos);
            if (is_dir('lang/' . $lang)) {
                $GLOBALS[$key] = $lang;
                return $GLOBALS[$key];
            }
        }
    }

    //default
    $GLOBALS[$key] = strtolower(IBC1_DEFAULT_LANGUAGE);
    return $GLOBALS[$key];
}

/**
 * reads a value associated with the given key in the config file
 *
 * @param string $key
 * @param string $group optional, designed to reduce loading unused data
 * @return mixed
 */
function GetConfigValue($key, $group = NULL) {
    if (empty($group))
        $group = 'settings'; //default group

    $key = IBC1_PREFIX . '_ConfigReader';
    $reader = &$GLOBALS[$key];
    if (!isset($reader) || !isset($reader[$group])) {
        $reader[$group] = new PHPCacheReader("conf/$group.conf.php", $group);
    }
    return $reader[$group]->GetValue($key);
}

/**
 * reads words in a certain language with a given key
 *
 * @param string $key   a short word that identifies a longer expression
 * in some language
 * @param string $group optional, designed to reduce loading unused data
 * @return string
 */
function GetLangData($key, $group = NULL) {

    $lang = &$GLOBALS[IBC1_PREFIX . '_Language'];
    if (!isset($lang))
        $lang = GetLang();

    if (empty($group))
        $group = $lang;
    else
        $group = $lang . '_' . $group;


    $reader = &$GLOBALS[IBC1_PREFIX . '_LangDataReader'];
    if (!isset($reader) || !isset($reader[$group])) {
        $reader[$group] = new PHPCacheReader("lang/$lang/$group.lang.php", $group);
    }
    return $reader[$group]->GetValue($key);
}

//-----------------------------------------------------------
//template processing
//-----------------------------------------------------------
/**
 * reads a template file and gets content of it
 *
 * @param string $tplname
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string
 */
function GetTemplate($tplname, $classname = NULL, $themeid = NULL, $lang = NULL) {
    $path = GetTplPath($tplname, $classname, $themeid, $lang);
    if ($path == '')
        return '';
    if (IBC1_MODE_DEV) {
        return file_get_contents($path);
    } else if (!empty($classname)) {
        $reader = &$GLOBALS[IBC1_PREFIX . '_TplReader'];
        if (!isset($reader) || !isset($reader[$classname])) {
            $reader[$classname] = new PHPCacheReader($path, $classname);
        }
        return $reader[$classname]->GetValue($tplname);
    } else {
        return '';
    }
}

/**
 * reads a template, passes parameters to it and generates HTML
 *
 * @param string $tplname
 * @param array $vars
 * @param string $classname
 * @param int $themeid
 * @param string $lang
 * @return string
 * @see GetTemplate()
 * @see Tpl2HTML()
 */
function TransformTpl($tplname, $vars, $classname = NULL, $themeid = NULL, $lang = NULL) {
    $tpl = GetTemplate($tplname, $classname, $themeid, $lang);

    return Tpl2HTML($tpl, $vars);
}

/**
 * passes parameters to the template and generates HTML
 *
 * @param string $tpl   content of a template
 * @param array $vars   variables to be assigned
 * <code>
 * array(
 *     [variable1 name]=>[variable1 value],
 *     [variable2 name]=>[variable2 value],
 *     ...
 * )
 * </code>
 * @return string
 */
function Tpl2HTML($tpl, $vars) {
    LoadIBC1Class('HTMLFilter', 'util');
    $htmlfilter = new HTMLFilter();
    foreach ($vars as $varname => $varvalue) {
        $pos = strpos($varname, '_');
        if ($pos) {
            switch (substr($varname, 0, $pos)) {
                case 'html':
                    $varvalue = $htmlfilter->filter($varvalue);
                    break;
                case 'text':
                    $varvalue = htmlspecialchars($varvalue);
                    break;
                case 'jsstr':
                    $varvalue = toScriptString($varvalue);
                    break;
                case 'urlparam':
                    $varvalue = urlencode($varvalue);
                    break;
                case 'int':
                    $varvalue = intval($varvalue);
                    break;
            }
        }
        $varvalue = str_replace('\\', '\\\\', $varvalue);
        $varvalue = str_replace('"', '\\"', $varvalue);
        $varvalue = str_replace('$', '\\$', $varvalue);
        eval("\$$varname=\"$varvalue\";");
    }
    $tpl = str_replace('\\', '\\\\', $tpl);
    $tpl = str_replace('"', '\\"', $tpl);
    eval("\$tpl=\"$tpl\";");
    return $tpl;
}

//-----------------------------------------------------------
//cache related
//-----------------------------------------------------------
/**
 * checks data version with the current up-to-date version stored somewhere
 * in the system for the necessity to regenerate the data to be cached
 *
 * @param int $dataversion
 * @param int $current
 * @return boolean
 */
function IsCachedDataOld($dataversion, $current) {
    if ($dataversion < 1)
        return FALSE;
    return $current > $dataversion;
}

/**
 * designed for <code>$_cacheVersion</code> in BoxModel and BoxFactory
 *
 * It is useful when there are a couple of versions that affect the views
 * created by a BoxModel or a BoxFactory.
 * For example, in a box view, there is a list of titles whose data source
 * has a version A, whereas a setting data item with a version of B controls
 * the number of the titles in the list. Therefore, the
 * <code>$_cacheVersion</code> for this box view should be
 * <code>AddVersions(A,B)</code>.
 * @param int $version1
 * @param int $version2
 * @return int
 */
function AddVersions($version1, $version2) {
    if ($version1 > $version2)
        return $version1;
    return $version2;
}

/**
 * designed for <code>$_cacheGroup</code> and <code>$_cacheKey</code>
 * in BoxModel and BoxFactory
 *
 * @param string $name      a name for cached data group or key and simply
 * for basic identification
 * @param array $factors    optional, for further identification, meaning
 * factors that affect the content, which should be cached differently
 * For example, in a paged list, pages should be cached separately and one of
 * the so-called factors is its page number.
 * Note that in this version, the sequence of factors matters. So, be sure
 * that it is consistent.
 * @return string
 */
function GenerateCacheId($name, $factors = NULL) {
    //TODO sequence of factors
    if (!empty($factors)) {
        foreach ($factors as $f) {
            $name.=$f;
        }
    }
    //SOLUTION 1:
    //    $name = str_replace("/", "_", $name);
    //    $name = str_replace("\\", "_", $name);
    //    $name = str_replace(":", "_", $name);
    //    return $name;
    //SOLUTION 2:
    //    return md5($name);
    //SOLUTION 3:
    return urlencode($name);
}

//-----------------------------------------------------------
//the Page-Process-Box model
//-----------------------------------------------------------
/**
 * a generic page model, based on a simple php string template
 *
 * @version 0.8.20120415
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
abstract class PageModel {

    /**
     * name of page template
     *
     * @var string
     */
    protected $_pagetpl = "";
    protected $_classname;
    private $_title = "";
    private $_keywords;
    private $_description;
    private $_meta = "";
    private $_css = "";
    private $_cssfile = "";
    private $_js = "";
    private $_jsfile = "";
    private $_icon = "";
    private $_regions = array();

    /**
     * constructor
     *
     * @param string $pagetpl   name of page template
     * @see $_pagetpl
     */
    function __construct($pagetpl) {
        $this->_pagetpl = $pagetpl;
        $this->_classname = __CLASS__;
        $this->Initialize();
        LoadIBC1Class("WordList", "util");
        $this->_keywords = new WordList();
        $this->_description = new WordList();
    }

    /**
     * automatically called in the end of constructing process
     *
     *  e.g. start timer
     */
    protected function Initialize() {
        //do nothing, but overrideable
    }

    /**
     * invoke functions requested by the client and registered in the
     *  config parameter; add box views specified as default views in
     *  the config parameter or as output views from processes into
     *  the page
     *
     * manually called before outputing HTML
     * @param array $config
     * array(
     *      "definitions"=>array(
     *          "module"=>"",
     *          "function"=>""
     *      ),
     *      "box"=>array([box name],[params]),
     *      "boxes"=>array(
     *          array([box name],[params]),
     *          ...
     *      ),
     *      "boxfactory"=>array([box factory name],[params]),
     *      "functions"=>array(
     *          [function name]=>array([process name],[params]),
     *          ...
     *      ),
     *      "modules"=>array(
     *          [module name]=>array(
     *              "box"=>array([box name],[params]),
     *              "boxes"=>array(
     *                  array([box name],[params]),
     *                  ...
     *              ),
     *              "boxfactory"=>array([box factory name],[params]),
     *              "functions"=>array(
     *                  [function name]=>array([process name],[params]),
     *                  ...
     *              )
     *          ),
     *          ...
     *      )
     * )
     */
    final public function Process($config) {

        //default parameter names for module & function
        $module = "module";
        $function = "function";

        //custom parameter names for module & function
        if (isset($config["definitions"])) {
            $defconf = &$config["definitions"];
            if (isset($defconf["module"]))
                $module = $defconf["module"];
            if (isset($defconf["function"]))
                $function = $defconf["function"];
        }

        //dynamically load and invoke functions
        // either in or out of specified modules
        $module_name = strGet($module);
        $function_name = strGet($function);
        if (!empty($module_name) && isset($config["modules"])) {

            //locate a module with a recursive approach
            //?module=[module name]
            $modconf = &$config["modules"];
            if (isset($modconf[$module_name])) {
                return $this->Process($modconf[$module_name]);
            }
        } else if (!empty($function_name) && isset($config["functions"])) {

            //?function=[function name]
            //?module=[module name]&function=[function name]
            $funconf = &$config["functions"];
            if (isset($funconf[$function_name])) {

                $proconf = &$funconf[$function_name];
                //a process should be done only once
                //require GetSysResPath($proconf[0], $proconf[1]);#fixed package
                require GetSysResPath($proconf[0] . ".class.php", "modules/processes");

                //$proc = new $proconf[0]($proconf[2]);#delete package field
                $proc = new $proconf[0]($proconf[1]);
                if ($proc->Process()) {

                    //show output of the function
                    //require GetSysResPath($proc->output_box, $proc->output_box_pkg);#fixed package
                    require_once GetSysResPath($proc->output_box . ".class.php", "modules/boxes");
                    $this->AddBox(new $proc->output_box($proc->output_box_params));

                    return TRUE;
                }

                return FALSE; //silent & no output
            }
        }

        //a default view if there's no function invoked
        if (isset($config["box"])) {

            //a single box view
            //require GetSysResPath($config["box"][0], $config["box"][1]);#fixed package
            require_once GetSysResPath($config["box"][0] . ".class.php", "modules/boxes");
            //$this->AddBox(new $config["box"][0]($config["box"][2]));#delete package field
            $this->AddBox(new $config["box"][0]($config["box"][1]));

            return TRUE;
        } else if (isset($config["boxes"])) {

            //an array of boxes
            foreach ($config["boxes"] as $box) {
                //require GetSysResPath($box[0], $box[1]);#fixed package
                require_once GetSysResPath($box[0] . ".class.php", "modules/boxes");
                //$this->AddBox(new $box[0]($box[2]));#delete package field
                $this->AddBox(new $box[0]($box[1]));
            }

            return TRUE;
        } else if (isset($config["boxfactory"])) {

            //boxes generated by a factory
            //require GetSysResPath($config["boxfactory"][0], $config["boxfactory"][1]);#fixed package
            require_once GetSysResPath($config["boxfactory"][0] . ".class.php", "modules/boxfactories");
            //$this->AddBoxFactory(new $config["boxfactory"][0]($config["boxfactory"][2]));#delete package field
            $this->AddBoxFactory(new $config["boxfactory"][0]($config["boxfactory"][1]));

            return TRUE;
        }
        return FALSE;
    }

    /**
     * automatically invoked when outputing HTML
     *
     * e.g. set page header/footer, stop timer
     */
    protected function Finalize() {
        //do nothing, but overrideable
    }

    /**
     * add a box factory to a region defined by itself
     *
     * @param BoxFactory $factory
     */
    public function AddBoxFactory(BoxFactory $factory) {

        //deploy js
        $this->AddJSFiles($factory->require_js);

        //deploy css
        $this->AddCSSFiles($factory->require_css);

        //deploy html
        $region = $factory->region;
        if (isset($this->_regions[$region]))
            $this->_regions[$region].= $factory->GetHTML();
        else
            $this->_regions[$region] = $factory->GetHTML();
    }

    /**
     * add a box to a region defined by itself
     *
     * @param BoxModel $box
     */
    public function AddBox($box) {
//        if ($box->status < 2) {//not hidden
//            //forward
//            if ($box->status == 1) {
//                //require GetSysResPath($b, $pkg);
//                require_once GetSysResPath($box->forward_box . ".class.php", "modules/boxes");
//                $box = new $box->forward_box($box->forward_box_params);
//            }
//
//            //deploy js
//            $this->AddJSFiles($box->require_js);
//
//            //deploy css
//            $this->AddCSSFiles($box->require_css);
//
//            //deploy html
//            $region = $box->region;
//            if (isset($this->_regions[$region]))
//                $this->_regions[$region].= $box->GetHTML();
//            else
//                $this->_regions[$region] = $box->GetHTML();
//        }
        if ($box->status < 2) {//not hidden
            //bind data
            $html = $box->GetHTML();

            //forward
            if ($box->status == 1) {
                //require GetSysResPath($b, $pkg);
                require_once GetSysResPath($box->forward_box . ".class.php", "modules/boxes");
                $box = new $box->forward_box($box->forward_box_params);
                $html = $box->GetHTML();
            }

            //deploy js
            $this->AddJSFiles($box->require_js);

            //deploy css
            $this->AddCSSFiles($box->require_css);

            //deploy html
            $region = $box->region;
            if (isset($this->_regions[$region]))
                $this->_regions[$region].= $html;
            else
                $this->_regions[$region] = $html;
        }
    }

    /**
     * set title for the page
     *
     * @param string $title
     */
    public function SetTitle($title) {
        $this->_title = htmlspecialchars($title);
    }

    /**
     * add keywords
     *
     * @param string $keywords
     */
    public function AddKeywords($keywords) {
        $this->_keywords->AddWords($keywords);
    }

    /**
     * append a string to description
     *
     * @param string $desc
     */
    public function AppendDescription($desc) {
        $this->_description->AddWords($desc);
    }

    /**
     * add extra &lt;meta&gt; information
     *
     * @param string $name
     * @param string $content
     */
    public function AddMeta($name, $content) {
        $name = htmlspecialchars($name);
        $content = htmlspecialchars($content);
        $this->_meta.="<meta content=\"$content\" name=\"$name\" />\n";
    }

    /**
     * append CSS code fragment to &lt;head&gt;
     *
     * @param string $css
     */
    public function AppendCSS($css) {
        $this->_css.=$css;
    }

    /**
     * add a CSS file to &lt;head&gt;
     *
     * @param string $cssfile
     * @param int $mode
     * <ul>
     * <li>mode=0, external => module=URL that locates the js file</li>
     * <li>mode=1, system level => module=name of the js file</li>
     * <li>mode=2, theme level => module=name of the js file</li>
     * </ul>
     */
    public function AddCSSFile($cssfile, $mode = 0) {
        //TODO prevent repetition
        switch ($mode) {
            case 1:
                $cssfile = GetSysResPath($cssfile, "stylesheets");
                break;
            case 2:
                $cssfile = GetThemeResPath($cssfile, "stylesheets");
                break;
        }
        if (!empty($cssfile)) {
            $this->_cssfile .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$cssfile}\" />\n";
        }
    }

    /**
     * add an array of CSS files to &lt;head&gt;
     *
     * @param array $cssfiles
     * array(
     *      array([cssfile],[mode]),
     *      ...
     * )
     * @see PageModel::AddCSSFile()
     */
    public function AddCSSFiles($cssfiles) {
        if (!empty($cssfiles)) {
            foreach ($cssfiles as $css) {
                $this->AddCSSFile($css[0], $css[1]);
            }
        }
    }

    /**
     * append JavaScript code fragment to &lt;head&gt;
     *
     * @param string $js
     */
    public function AppendJS($js) {
        $this->_js.=$js;
    }

    /**
     * add a JavaScript file to &lt;head&gt
     *
     * @param string $jsfile
     * @param int $mode
     * @see PageModel::AddCSSFile()
     */
    public function AddJSFile($jsfile, $mode = 0) {
        //TODO prevent repetition
        switch ($mode) {
            case 1:
                $jsfile = GetSysResPath($jsfile, "scripts");
                break;
            case 2:
                $jsfile = GetThemeResPath($jsfile, "scripts");
                break;
        }
        if (!empty($jsfile)) {
            $this->_jsfile .= "<script language=\"javascript\" type=\"text/javascript\" src=\"{$jsfile}\"></script>\n";
        }
    }

    /**
     * add an array of JavaScript files to &lt;head&gt
     *
     * @param array $jsfiles
     * array(
     *      array([jsfile],[mode]),
     *      ...
     * )
     * @see PageModel::AddJSFile()
     */
    public function AddJSFiles($jsfiles) {
        if (!empty($jsfiles)) {
            foreach ($jsfiles as $js) {
                $this->AddJSFile($js[0], $js[1]);
            }
        }
    }

    /**
     * set an image as an icon
     * @param stirng $iconfile
     */
    public function SetIcon($iconfile, $type = "") {
        if ($type == "")
            $type = GetFileExt($iconfile);
        switch (strtolower($type)) {
            case "ico":
                $mime = "image/x-icon";
                break;
            case "jpeg":
            case "jpg":
                $mime = "image/jpeg";
                break;
            case "gif":
                $mime = "image/gif";
                break;
            case "png":
                $mime = "image/png";
                break;
            case "bmp":
                $mime = "image/bmp";
                break;
            default:
                $mime = $type;
        }
        $this->_icon = "<link rel=\"shortcut icon\" type=\"{$mime}\" href=\"{$iconfile}\" />\n";
    }

    public function GetHTML() {
        //head BEGIN
        $head = "";
        //icon
        if (!empty($this->_icon)) {
            $head.=$this->_icon;
        }
        //css
        if (!empty($this->_cssfile)) {
            $head.=$this->_cssfile;
        }
        if (!empty($this->_css)) {
            $head.="<style>\n<!--\n{$this->_css}\n-->\n</style>\n";
        }
        //js
        if (!empty($this->_jsfile)) {
            $head.=$this->_jsfile;
        }
        if (!empty($this->_js)) {
            $head.="<script language=\"javascript\" type=\"text/javascript\">\n//<![CDATA[\n{$this->_js}\n//]]>\n</script>\n";
        }
        //meta
        $head.=$this->_meta;
        //head END
        //output
        $this->_regions["Keywords"] = $this->_keywords->GetWords();
        $this->_regions["Description"] = $this->_description->GetWords();
        $this->_regions["Title"] = $this->_title;
        $this->_regions["Head"] = $head;
        $this->Finalize();
        return TransformTpl($this->_pagetpl, $this->_regions, $this->_classname);
    }

    public function Show() {
        echo $this->GetHTML();
    }

}

/**
 * an abstract process for the Page-Process-Box model
 *
 * @version 0.1.20120311
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
abstract class ProcessModel {

    /**
     * name of a BoxModel as output
     * @var string
     */
    var $output_box;

    /*
     * package name of the BoxModel
     * @var string
     */
    //var $output_box_pkg;

    /**
     * parameters for constructing the BoxModel
     * @var array
     */
    var $output_box_params;

    /**
     * @return bool
     * TRUE  - output is prepared as parameters
     * FALSE - silently ends the process without any output
     */
    abstract public function Process();
}

/**
 * a box container model in the Page-Process-Box model
 *
 * @version 0.6.20120311
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
class BoxModel {

    /**
     * name of a BoxModel
     * @var string
     */
    var $forward_box;

    /*
     * package name of the BoxModel
     * @var string
     */
    //var $forward_box_pkg;

    /**
     * parameters for constructing the BoxModel
     * @var array
     */
    var $forward_box_params;

    /**
     * 0 - normal
     * 1 - forward
     * 2 - hidden
     * @var int
     */
    var $status;

    /**
     * @see PageModel::AddJSFiles()
     * @var array
     */
    var $require_js;

    /**
     * @see PageModel::AddCSSFiles()
     * @var array
     */
    var $require_css;
    var $region;
    var $width;
    var $height;
    var $title;
    var $padding;
    var $align;
    var $valign;
    private $_content;
    protected $_tplName;
    protected $_cachePath;
    protected $_cacheTimeout;
    protected $_cacheGroup;
    protected $_cacheKey;
    protected $_cacheVersion;
    protected $_cacheRandFactor;
    protected $_classname;

    function __construct($region) {
        $this->region = $region;
        $this->height = "auto";
        $this->title = "";
        $this->padding = 0;
        $this->status = 0;
        $this->align = "left";
        $this->valign = "top";
        $this->_content = "";
        $this->_classname = __CLASS__;
        $this->_cachePath = "";
        $this->_cacheGroup = "";
        $this->_cacheKey = "";
        $this->_cacheTimeout = 0;
        $this->_cacheVersion = 0;
        $this->_cacheRandFactor = 1;
    }

    public function SetContent($html, $align = NULL, $valign = NULL, $padding = -1) {
        $this->_content = $html;
        if ($align != NULL)
            $this->align = $align;
        if ($valign != NULL)
            $this->valign = $valign;
        if ($padding > -1)
            $this->padding = $padding;
    }

    public function AppendContent($html) {
        $this->_content .= $html;
    }

    public function CacheBind() {
        //to be over ridden
    }

    public function DataBind() {
        //to be over ridden
    }

    public function GetRefreshedHTML() {

        $this->DataBind();

        $html = "";
        if ($this->_tplName != "") {
            $html = TransformTpl($this->_tplName, array(
                "Width" => $this->width,
                "Height" => $this->height,
                "Title" => htmlspecialchars($this->title),
                "Content" => $this->_content,
                "Padding" => $this->padding,
                "Align" => $this->align,
                "VAlign" => $this->valign
                    ), $this->_classname);
        } else {
            $html = $this->_content;
        }

        if (!empty($this->_cacheGroup)) {
            try {
                LoadIBC1Class("ICacheEditor", "cache");
                LoadIBC1Class("PHPCacheEditor", "cache.phpcache");
                $ce = new PHPCacheEditor($this->_cachePath, $this->_cacheGroup);
                $ce->SetValue($this->_cacheKey, $html, $this->_cacheTimeout, $this->_cacheVersion > 0);
                $ce->Save();
            } catch (Exception $ex) {
                
            }
        }

        return $html;
    }

    public function GetHTML() {

        $this->CacheBind();
        if (!empty($this->_cacheGroup)) {
            //LoadIBC1Class("PHPCacheReader", "cache.phpcache");
            $cr = new PHPCacheReader($this->_cachePath, $this->_cacheGroup);
            $cr->SetRefreshFunction(array($this, "GetRefreshedHTML"));
            return $cr->GetValue($this->_cacheKey, $this->_cacheVersion, $this->_cacheRandFactor);
        } else {
            return $this->GetRefreshedHTML();
        }
    }

}

/**
 * a factory for the box model
 *
 * @version 0.1.20120226
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
class BoxFactory {

    var $region;

    /**
     * @see PageModel::AddJSFiles()
     * @var array
     */
    var $require_js;

    /**
     * @see PageModel::AddCSSFiles()
     * @var array
     */
    var $require_css;
    protected $_cachePath;
    protected $_cacheTimeout;
    protected $_cacheGroup;
    protected $_cacheKey;
    protected $_cacheVersion;
    protected $_cacheRandFactor;
    private $_html;

    function __construct($region) {
        $this->region = $region;
        $this->_html = "";
        $this->_cachePath = "";
        $this->_cacheGroup = "";
        $this->_cacheKey = "";
        $this->_cacheTimeout = 0;
        $this->_cacheVersion = 0;
        $this->_cacheRandFactor = 1;
    }

    public function CacheBind() {
        //to be over ridden
    }

    public function AddBox(BoxModel $box) {
        if ($box->region === $this->region) {
            //TODO check status
            if (empty($this->_html))
                $this->_html = $box->GetHTML();
            else
                $this->_html .= $box->GetHTML();
        }
    }

    public function DataBind() {
        //to be over ridden
    }

    public function GetRefreshedHTML() {

        $this->DataBind();

        if (!empty($this->_cacheGroup)) {

            try {
                LoadIBC1Class("ICacheEditor", "cache");
                LoadIBC1Class("PHPCacheEditor", "cache.phpcache");
                $ce = new PHPCacheEditor($this->_cachePath, $this->_cacheGroup);
                $ce->SetValue($this->_cacheKey, $this->_html, $this->_cacheTimeout, $this->_cacheVersion > 0);
                $ce->Save();
            } catch (Exception $ex) {
                
            }
        }

        return $this->_html;
    }

    public function GetHTML() {
        $this->CacheBind();
        if (!empty($this->_cacheGroup)) {
            //LoadIBC1Class("PHPCacheReader", "cache.phpcache");
            $cr = new PHPCacheReader($this->_cachePath, $this->_cacheGroup);
            $cr->SetRefreshFunction(array($this, "GetRefreshedHTML"));
            return $cr->GetValue($this->_cacheKey, $this->_cacheVersion, $this->_cacheRandFactor);
        } else {
            return $this->GetRefreshedHTML();
        }
    }

}

/**
 * TODO URLModel
 *
 * @version 0.1.20120320
 * @author Zhiji Gu <gu_zhiji@163.com>
 * @copyright &copy; 2010-2012 InterBox Core 1.1.5 for PHP, GuZhiji Studio
 * @package interbox.core.uimodel
 */
class URLModel {

    private $page;
    private $params;

    function __construct($page) {
        $this->page = $page;
        $this->params = array();
    }

//TODO query[]=aaa&query[]=bbb
    public function CopyCurrentParams() {
        foreach ($_GET as $key => $value) {
            $this->params[$key] = $value;
        }
    }

    public function AddParam($key, $value) {
        $this->params[$key] = urlencode($value);
    }

    public function GetURL() {
        $url = "";
        foreach ($this->params as $key => $value) {
            if (!empty($url))
                $url.="&";
            $url.=$key . "=" . $value;
        }
        return $url;
    }

}