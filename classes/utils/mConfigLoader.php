<?php

namespace App\Utils;

if (!function_exists('array_is_list')) {
    function array_is_list(array $array): bool {
        $i = 0;
        foreach ($array as $k => $v) {
            if ($k !== $i++) return false;
        }
        return true;
    }
}

/**
 * Brief Class Description.
 * Complete Class Description.
 */
class MConfigLoader
{
    /**
     * Attribute Description.
     */
    private $conf;
    private $defaultConf;

    /**
     * Brief Description.
     * Complete Description.
     *
     * @returns (tipo) desc
     *
     */
    public function __construct($loadDefaultConf = true)
    {
        if ( $loadDefaultConf )
        {
            $this->setDefaultConf();
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $module' (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function loadConf($module = '', $file = '')
    {
        $dir = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/html'));
        $fname = ($file == '') ? $dir . (($module == '') ? '/etc/miolo.php' : '/modules/' . $module . '/etc/module.php') : $file;

        if (file_exists($fname))
        {
            $data = require $fname;

            if (is_array($data))
            {
                $this->flattenArray($data, '', $this->conf);
            }
        }
    }

    private function flattenArray(array $array, string $prefix, ?array &$result): void
    {
        if ($result === null)
        {
            $result = [];
        }

        foreach ($array as $key => $value)
        {
            $flatKey = ($prefix !== '') ? $prefix . '.' . $key : $key;

            if (is_array($value) && !array_is_list($value))
            {
                $this->flattenArray($value, $flatKey, $result);
            }
            else
            {
                $result[$flatKey] = $value;
            }
        }
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $key (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function getConf($key)
    {
        $value = null;

        if ( is_array($this->conf) && array_key_exists($key, $this->conf) && !is_null($this->conf[$key]) )
        {
            $value = $this->conf[$key];
        }
        else if ( is_array($this->defaultConf) && array_key_exists($key, $this->defaultConf) && !is_null($this->defaultConf[$key]) )
        {
            $value = $this->defaultConf[$key];
        }

        return $value;
    }

    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $key (tipo) desc
     * @param $value (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function setConf($key, $value)
    {
        $this->conf[$key] = $value;
    }

    /**
     * Return all conf entries whose keys start with the given prefix.
     *
     * @param string $prefix  Key prefix (e.g. 'db.' returns 'db.miolo.host' => '...', etc.)
     * @return array<string, mixed>
     */
    public function getConfByPrefix(string $prefix): array
    {
        $result = [];

        foreach ([$this->conf, $this->defaultConf] as $source) {
            if (!is_array($source)) {
                continue;
            }
            foreach ($source as $key => $value) {
                if (str_starts_with($key, $prefix) && !array_key_exists($key, $result)) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
    /**
     * Brief Description.
     * Complete Description.
     *
     * @param $key (tipo) desc
     * @param $value (tipo) desc
     *
     * @returns (tipo) desc
     *
     */
    public function setDefaultConf()
    {
        $homeMiolo = $this->getConf("home.miolo");
        
        if ( !$homeMiolo )
        {
            $homeMiolo = dirname(dirname(dirname(__FILE__)));
            $this->setConf('home.miolo', $homeMiolo);
        }
        
        $this->defaultConf = array(
            "home.classes"=>$homeMiolo."/classes",
            "home.modules"=>$homeMiolo."/modules",
            "home.etc"=>$homeMiolo."/etc",
            "home.logs"=>$homeMiolo."/var/log",
            "home.trace"=>$homeMiolo."/var/trace",
            "home.db"=>$homeMiolo."/var/db",
            "home.html"=>$homeMiolo."/html",
            "home.themes"=>$homeMiolo."/classes/ui/themes",
            "home.extensions"=>$homeMiolo."/classes/extensions",
            "home.reports"=>$homeMiolo."/var/reports",
            "home.images"=>$homeMiolo."/ui/images",
            
            "i18n.locale" =>$homeMiolo."/locale",

            "home.url_themes"=>"/themes",
            "home.url_reports"=>"/reports",
            "home.module.themes"=>"/ui/themes",
            "home.module.html"=>"/html",
            "home.module.images"=>"/html/images",

            "namespace.core"=>"/classes",
			"namespace.service"=>"/classes/services",
			"namespace.ui"=>"/classes/ui",
			"namespace.themes"=>"/ui/themes",
			"namespace.extensions"=>"/classes/extensions",
			"namespace.controls"=>"/ui/controls",
			"namespace.database"=>"/classes/database",
			"namespace.utils"=>"/classes/utils",
			"namespace.modules"=>"/modules"
		);
    }


    /**
    * Generate a PHP return-array string for configuration file
    * @params array conf values
    * @returns (string) conf
    */
    public function generateConfigPHP($data, $confModule = null)
    {
        (!$confModule) ? $confModule = \MIOLO::_REQUEST('confModule') : null;
        !$confModule ? $confModule = 'miolo' : null;

        $config = [];

        // home
        $homeKeys = ['miolo','classes','modules','etc','logs','trace','db','html',
                     'themes','extensions','reports','images','url',
                     'url_themes','url_reports','module.themes','module.html','module.images'];
        foreach ($homeKeys as $k) {
            if (!empty($data["home.$k"])) $config['home'][$k] = $data["home.$k"];
        }

        // namespace
        $nsKeys = ['core','service','ui','themes','extensions','controls','database','utils','modules'];
        foreach ($nsKeys as $k) {
            if (!empty($data["namespace.$k"])) $config['namespace'][$k] = $data["namespace.$k"];
        }

        // theme
        $themeKeys = ['module','main','lookup','title','company','system','logo','email'];
        foreach ($themeKeys as $k) {
            if (!empty($data["theme.$k"])) $config['theme'][$k] = $data["theme.$k"];
        }
        if (!empty($data['theme.options.close'])) {
            $config['theme']['options']['close'] = $data['theme.options.close'];
        }

        // options
        $optKeys = ['startup','common','scramble','scramble.password','dispatch','url.style',
                    'index','mainmenu','mainmenu.style','mainmenu.clickopen','dbsession',
                    'authmd5','debug','autocomplete_alert','charset','fileextension','json_encode'];
        foreach ($optKeys as $k) {
            if (isset($data["options.$k"]) && $data["options.$k"] !== '') {
                $config['options'][$k] = $data["options.$k"];
            }
        }
        $dumpKeys = ['peer','profile','uses','trace','handlers'];
        foreach ($dumpKeys as $k) {
            if (!empty($data["options.dump.$k"])) $config['options']['dump'][$k] = $data["options.dump.$k"];
        }
        $loadingKeys = ['show','generating'];
        foreach ($loadingKeys as $k) {
            if (!empty($data["options.loading.$k"])) $config['options']['loading'][$k] = $data["options.loading.$k"];
        }

        // i18n
        if (!empty($data['i18n.locale']))   $config['i18n']['locale']   = $data['i18n.locale'];
        if (!empty($data['i18n.language'])) $config['i18n']['language'] = $data['i18n.language'];

        // mad
        if (!empty($data['mad.module'])) $config['mad']['module'] = $data['mad.module'];
        $madKeys = ['access','group','log','session','transaction','user'];
        foreach ($madKeys as $k) {
            if (!empty($data["mad.classes.$k"])) $config['mad']['classes'][$k] = $data["mad.classes.$k"];
        }

        // login
        $loginKeys = ['module','class','check','shared','auto'];
        foreach ($loginKeys as $k) {
            if (isset($data["login.$k"]) && $data["login.$k"] !== '') {
                $config['login'][$k] = $data["login.$k"];
            }
        }

        // session
        if (!empty($data['session.handler'])) $config['session']['handler'] = $data['session.handler'];
        if (!empty($data['session.timeout'])) $config['session']['timeout'] = $data['session.timeout'];

        // db
        $dbKeys = ['system','host','name','user','password'];
        foreach ($dbKeys as $k) {
            if (isset($data["db.$confModule.$k"]) && $data["db.$confModule.$k"] !== '') {
                $config['db'][$confModule][$k] = $data["db.$confModule.$k"];
            }
        }

        // logs
        $logKeys = ['level','handler','peer','port'];
        foreach ($logKeys as $k) {
            if (isset($data["logs.$k"]) && $data["logs.$k"] !== '') {
                $config['logs'][$k] = $data["logs.$k"];
            }
        }

        return "<?php\n\nreturn " . var_export($config, true) . ";\n";
    }
}

class_alias(MConfigLoader::class, 'MConfigLoader');
