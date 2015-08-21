<?php
namespace PMVC\PlugIn\http_auth;

// \PMVC\l(__DIR__.'/xxx.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\http_auth';

class http_auth extends \PMVC\PlugIn
{
    public function init()
    {
        $this->block();
    }

    public function block()
    {
        header('WWW-Authenticate: Basic realm="'.$this->getTip().'"');
        header('HTTP/1.0 401 Unauthorized');
         
        if (!is_null($this['callBack'])) {
            return call_user_func_array(
                $this['callBack'],
                array()
            );
        }
        $view = \PMVC\plug('view');
        $view->setThemeFolder(
            \PMVC\getOption(_TEMPLATE_DIR)
        );
        $view->setThemePath('forbidden');
        $view->process();
        exit();
    }

    public function getTip()
    {
        if (empty($this['tip'])) {
            $this['tip'] = 'Guard for '.getenv('HTTP_HOST');
        }
        return $this['tip'];
    }
}
