<?php

/* Copyright (C) 2004-2018 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2018	   Nicolas ZABOURI 	<info@inovea-conseil.com>
 * Copyright (C) 2018 SuperAdmin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 	\defgroup   html2pdf     Module Html2Pdf
 *  \brief      Html2Pdf module descriptor.
 *
 *  \file       htdocs/html2pdf/core/modules/modHtml2Pdf.class.php
 *  \ingroup    html2pdf
 *  \brief      Description and activation file for module Html2Pdf
 */
include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

// The class name should start with a lower case mod for Dolibarr to pick it up
// so we ignore the Squiz.Classes.ValidClassName.NotCamelCaps rule.
// @codingStandardsIgnoreStart
/**
 *  Description and activation class for module Html2Pdf
 */
class modHtml2Pdf extends DolibarrModules {

    // @codingStandardsIgnoreEnd
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db) {
        global $langs, $conf;

        $this->db = $db;

        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 500101;  // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve id number for your module
        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'html2pdf';

        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
        // It is used to group modules by family in module setup page
        $this->family = "technic";
        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '90';
        // Gives the possibility to the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        //$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
        // Module label (no space allowed), used if translation string 'ModuleHtml2PdfName' not found (MyModue is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));
        // Module description, used if translation string 'ModuleHtml2PdfDesc' not found (MyModue is name of module).
        $this->description = "Html2PdfDescription";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "Html2PdfDescription (Long)";

        $this->editor_name = 'Hoverepic';
        $this->editor_url = 'https://github.com/HoverEpic';

        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = '1.0';

        //Url to the file with your last numberversion of this module
        $this->url_last_version = 'http://www.example.com/versionmodule.txt';
        // Key used in llx_const table to save module status enabled/disabled (where HTML2PDF is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);
        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        $this->picto = 'generic';

        // Defined all module parts (triggers, login, substitutions, menus, css, etc...)
        // for default path (eg: /html2pdf/core/xxxxx) (0=disable, 1=enable)
        // for specific path of parts (eg: /html2pdf/core/modules/barcode)
        // for specific css file (eg: /html2pdf/css/html2pdf.css.php)
        $this->module_parts = array(
            'triggers' => 1, // Set this to 1 if module has its own trigger directory (core/triggers)
            'login' => 0, // Set this to 1 if module has its own login method file (core/login)
            'substitutions' => 1, // Set this to 1 if module has its own substitution function file (core/substitutions)
            'menus' => 0, // Set this to 1 if module has its own menus handler directory (core/menus)
            'theme' => 0, // Set this to 1 if module has its own theme directory (theme)
            'tpl' => 0, // Set this to 1 if module overwrite template dir (core/tpl)
            'barcode' => 0, // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'models' => 0, // Set this to 1 if module has its own models directory (core/modules/xxx)
            'css' => array(), // Set this to relative path of css file if module has its own css file
            'js' => array(), // Set this to relative path of js file if module must load a js on all pages
            'hooks' => array('data' => array('hookcontext1', 'hookcontext2'), 'entity' => '0'), // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context 'all'
            'moduleforexternal' => 0       // Set this to 1 if feature of module are opened to external users
        );

        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/html2pdf/temp","/html2pdf/subdir");
        $this->dirs = array("/html2pdf/temp");

        // Config pages. Put here list of php page, stored into html2pdf/admin directory, to use to setup module.
        $this->config_page_url = array("setup.php@html2pdf");

        // Dependencies
        $this->hidden = false;   // A condition to hide module
        $this->depends = array();  // List of module class names as string that must be enabled if this module is enabled
        $this->requiredby = array(); // List of module class names to disable if this one is disabled
        $this->conflictwith = array(); // List of module class names as string this module is in conflict with
        $this->langfiles = array("html2pdf@html2pdf");
        $this->phpmin = array(5, 4);     // Minimum version of PHP required by module
        $this->need_dolibarr_version = array(4, 0); // Minimum version of Dolibarr required by module
        $this->warnings_activation = array();                     // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array();                 // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)

        $this->const = array();

        if (!isset($conf->html2pdf) || !isset($conf->html2pdf->enabled)) {
            $conf->html2pdf = new stdClass();
            $conf->html2pdf->enabled = 0;
        }
        $this->tabs = array();
        $this->dictionaries = array();
        $this->boxes = array();
        $this->rights = array();
        // rights
        $r = 0;
        $this->rights[$r][0] = $this->numero + $r;              // Permission id (must not be already used)
        $this->rights[$r][1] = 'Create a pdf from a html file'; // Permission label
        $this->rights[$r][3] = 1;                               // Permission by default for new user (0/1)
        $this->rights[$r][4] = 'create';                        // In php code, permission will be checked by test if ($user->rights->html2pdf->level1->level2)
        $this->rights[$r][5] = '';                              // In php code, permission will be checked by test if ($user->rights->html2pdf->level1->level2)

        $this->menu = array();
        $r = 0;
        $this->menu[$r++] = array(
            'fk_menu' => 'fk_mainmenu=tools', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'titre' => 'List Gift',
            'mainmenu' => 'html2pdf',
            'leftmenu' => 'html2pdf',
            'url' => '/html2pdf/html2pdfindex.php',
            'langs' => 'html2pdfe@html2pdf', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position' => 1000 + $r,
            'enabled' => '$conf->html2pdf->enabled', // Define condition to show or hide menu entry. Use '$conf->html2pdf->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms' => '1', // Use 'perms'=>'$user->rights->html2pdf->level1->level2' if you want your menu with a permission rules
            'target' => '',
            'user' => 2                             // 0=Menu for internal users, 1=external users, 2=both
        );
        $this->menu[$r++] = array(
            'fk_menu' => 'fk_mainmenu=html2pdf', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type' => 'left', // This is a Left menu entry
            'titre' => 'List Gift',
            'mainmenu' => 'html2pdf',
            'leftmenu' => 'html2pdf_list',
            'url' => '/html2pdf/html2pdfindex.php',
            'langs' => 'html2pdfe@html2pdf', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position' => 1000 + $r,
            'enabled' => '$conf->html2pdf->enabled', // Define condition to show or hide menu entry. Use '$conf->html2pdf->enabled' if entry must be visible if module is enabled. Use '$leftmenu==\'system\'' to show if leftmenu system is selected.
            'perms' => '1', // Use 'perms'=>'$user->rights->html2pdf->level1->level2' if you want your menu with a permission rules
            'target' => '',
            'user' => 2                             // 0=Menu for internal users, 1=external users, 2=both
        );
    }

    /**
     * 	Function called when module is enabled.
     * 	The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     * 	It also creates data directories
     *
     * 	@param      string	$options    Options when enabling module ('', 'noboxes')
     * 	@return     int             	1 if OK, 0 if KO
     */
    public function init($options = '') {

        dol_syslog("Html2Pdf::init(options=" . $options . ")");
        //download
        $url = "https://github.com/dompdf/dompdf/releases/download/v0.8.2/dompdf_0-8-2.zip";
        $name = basename($url);
        $dir = DOL_DOCUMENT_ROOT . "/custom/html2pdf/lib/";
        $file = $dir . $name;
        // Création d'un flux
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                "Cookie: foo=bar\r\n"
            )
        );
        $context = stream_context_create($opts);
        dol_syslog("Html2Pdf::init Downloading " . $url);
        $content = file_get_contents($url, false, $context);
        $handle = fopen($file, "w");
        fwrite($handle, $content);
        fclose($handle);
        dol_syslog("Html2Pdf::init Unziping " . $file);
        //unzip in /html2pdf/lib
        if (file_exists($file)) {
            $zip = new ZipArchive();
            if ($zip->open($file) === TRUE) {
                $zip->extractTo($dir);
                $zip->close();
                dol_syslog("Html2Pdf::init Deleting " . $file);
                unlink($file);
                return $this->_init(array(), $options);
            }
        }
        return 0;
    }

    /**
     * 	Function called when module is disabled.
     * 	Remove from database constants, boxes and permissions from Dolibarr database.
     * 	Data directories are not deleted
     *
     * 	@param      string	$options    Options when enabling module ('', 'noboxes')
     * 	@return     int             	1 if OK, 0 if KO
     */
    public function remove($options = '') {
        $sql = array();
        return $this->_remove($sql, $options);
    }

    /**
     * 	Static function.
     * 	Create a pdf file from the html input.
     *
     * 	@param      string	$html       The input html content
     * 	@return     binary                  The output pdf content
     */
    public static function html2pdf($html) {
        // include autoloader
        require_once DOL_DOCUMENT_ROOT . '/custom/html2pdf/lib/dompdf/autoload.inc.php';
        require_once DOL_DOCUMENT_ROOT . '/custom/html2pdf/lib/dompdf/lib/html5lib/Parser.php';
        require_once DOL_DOCUMENT_ROOT . '/custom/html2pdf/lib/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
        require_once DOL_DOCUMENT_ROOT . '/custom/html2pdf/lib/dompdf/lib/php-svg-lib/src/autoload.php';
        require_once DOL_DOCUMENT_ROOT . '/custom/html2pdf/lib/dompdf/src/Autoloader.php';
        Dompdf\Autoloader::register();

        // Instantiate and use the dompdf class
        $dompdf = new Dompdf\Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // (Optional) Set images dpi lesser
        $dompdf->set_option( 'dpi' , '200' );

        // Render the HTML as PDF
        $dompdf->render();

        // Return the pdf content
        return $dompdf->output();
    }

}
