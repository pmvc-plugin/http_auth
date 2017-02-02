<?php
namespace PMVC\PlugIn\http_auth;

// \PMVC\l(__DIR__.'/xxx.php');

${_INIT_CONFIG}[_CLASS] = __NAMESPACE__.'\http_auth';

class http_auth extends \PMVC\PlugIn
{
    public function auth()
    {
        $env = \PMVC\plug('getenv');
        $authUser = $env->get('PHP_AUTH_USER');
        $authPass = $env->get('PHP_AUTH_PW');
        if (empty($authUser) && empty($authPass)) {
            $this->header();
            echo $this->getTip();
            exit();
        }
        if ($this['user'] && $this['pass']) {
            if (
                0 === strcmp((string)$this['user'], (string)$authUser) &&
                0 === strcmp((string)$this['pass'], (string)$authPass)
            ) {
                return true;
            }
        }
        $this->block();
    }

    public function header()
    {
        $headers = [
            'WWW-Authenticate: Basic realm="'.$this->getTip().'"',
            'HTTP/1.0 401 Unauthorized'
        ];
        foreach ($headers as $h) {
            header($h);
        }
    }

    public function block()
    {
        $this->header();
        if (!is_null($this['callback'])) {
            return call_user_func(
                $this['callback']
            );
        }
        trigger_error(403, E_USER_ERROR);
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
