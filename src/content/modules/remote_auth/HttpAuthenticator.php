<?php

class HttpAuthenticator extends Controller
{

    public function getConfig()
    {
        $cfg = new CMSConfig();
        if (isset($cfg->remote_auth_config) and is_array($cfg->remote_auth_config)) {
            return $cfg->remote_auth_config;
        }
        return null;
    }

    // get the environment variables which could contain the user name
    public function getEnvVars()
    {
        $env_vars = array(
            "REMOTE_USER",
            "REDIRECT_REMOTE_USER"
        );
        $cfg = $this->getConfig();
        if (isset($cfg["env_vars"])) {
            if (is_array($cfg["env_vars"])) {
                $env_vars = $cfg["env_vars"];
            } else if (is_string($cfg["env_vars"])) {
                $env_vars = array(
                    $cfg["env_vars"]
                );
            }
        }
        return $env_vars;
    }

    // get the remote user from an environment variable
    public function getRemoteUser()
    {
        $config = $this->getConfig();
        $remove_realm = (isset($config["remove_realm"]) and $config["remove_realm"]);
        $vars = $this->getEnvVars();
        foreach ($vars as $var) {
            if (isset($_SERVER[$var]) and StringHelper::isNotNullOrWhitespace($_SERVER[$var])) {
                $user = $_SERVER[$var];
                if ($remove_realm and strpos($user, '@') !== false) {
                    $user = substr($user, 0, strpos($user, '@'));
                }
                return $user;
            }
        }
        return null;
    }

    // check authentication
    public function auth()
    {
        $user = $this->getRemoteUser();
        if (! $user) {
            return null;
        }
        $user = getUserByName($user);
        $cfg = $this->getConfig();
        if (! $user and isset($cfg["create_user"]) and $cfg["create_user"]) {
            // create user account if it doesn't exist (if enabled)
            if (isset($cfg["create_user"]) and $cfg["create_user"]) {
                $user = $this->getRemoteUser();
                $password = rand_string(12);
                $mail_suffix = isset($cfg["mail_suffix"]) ? $cfg["mail_suffix"] : "";
                // add mail suffix to user address
                $email = $user . $mail_suffix;
                adduser($user, $cfg["default_lastname"], $cfg["default_firstname"], $email, $password, false);
                
                $user = getUserByName($user);
            }
        }
        return $user;
    }
}