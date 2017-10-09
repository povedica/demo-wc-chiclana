<?php
require_once dirname(__FILE__) . '/vendor/autoload.php';
global $argv;

class WPCLI_WPInstaller
{
    private $_file;
    private $_data;
    private $_shell_commands;
    private $_wppath;
    private $_logger;
    private $_debug;

    public function __construct($file)
    {
        $this->_file = $file;
        $this->_data = $this->jsonToArray($this->readFile($this->_file));
        $this->_shell_commands = array();
        $this->_wppath = null;
        $this->_logger = Logger::getLogger("main");
        $this->setIfDebugMode();
    }

    public function init()
    {
        try {
            $this->installWP($this->getInstancesToInstall());
        } catch (Exception $e) {
            echo $e->getMessage();

            return;
        }

    }

    public function log($message)
    {
        $this->_logger->info($message);
    }

    public function getInstancesToInstall()
    {
        return $this->_data["instances"];
    }

    private function jsonToArray($json)
    {
        return json_decode($json, true);
    }

    public function fileExist($file)
    {
        return file_exists($file);
    }

    public function readFile($file)
    {
        return file_get_contents($file);
    }

    public function installWP($instances)
    {
        foreach ($instances as $instance) {
            $this->resetCommands();
            $this->_wppath = $instance["path"];
            $this->processInstance($instance);
        }

        $this->_wppath = null;
    }

    public function processInstance($instance)
    {
        $this->preparePath($this->_wppath);

        foreach ($instance['arguments'] as $command => $arguments) {
            $this->prepareCommand($command, $arguments);
        }

        $this->runCommands();
    }

    public function preparePath($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }
    }

    public function getWPCliBinaryPath()
    {
        return $this->_data["wpcli_binary"];
    }

    public function prepareCommand($command, $arguments)
    {
        $wpcli = $this->getWPCliBinaryPath();
        $method = "{$command}CommandManager";
        if (method_exists($this, $method)) {
            $this->$method($wpcli, $command, $arguments);
        } else {
            $this->_logger->error("There is no method available for command $command");
        }
    }

    public function prepareSubCommand($subcommand, $options, $operator = "=")
    {
        $shell_subcommand = "$subcommand";

        foreach ($options as $option => $value) {
            $shell_subcommand .= " {$option}{$operator}{$value} ";
        }

        return $shell_subcommand;
    }

    public function addCommand($command)
    {
        $this->_shell_commands[] = $command . " --path={$this->_wppath}";
    }

    public function resetCommands()
    {
        unset($this->_shell_commands);
        $this->_shell_commands = array();
    }

    public function runCommands()
    {
        foreach ($this->_shell_commands as $command) {
            $this->log($command);
            if (!$this->_debug) {
                $output = exec($command);
                $this->log($output);
            }
        }
    }

    public function pluginCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments["install"] as $plugin) {
            $version = $activate = '';
            if (isset($plugin['version'])) {
                $version = "--version={$plugin['version']}";
            }

            if (isset($plugin['activate'])) {
                $activate = " --activate ";
            }

            $this->addCommand("{$wpcli} {$command} install {$plugin['name']} $version $activate ");
        }
    }

    public function themeCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments as $subcommand => $option) {
            $this->addCommand("{$wpcli} {$command} {$subcommand} {$option} ");
        }
    }

    public function rewriteCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments as $subcommand => $option) {
            $this->addCommand("{$wpcli} {$command} {$subcommand} {$option} ");
        }
    }

    public function postCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments as $subcommand => $posts) {
            foreach ($posts as $post) {
                $string_options = '';
                foreach ($post as $post_key => $post_value) {
                    $string_options .= " --{$post_key}=\"{$post_value}\"";
                }
                $this->addCommand("{$wpcli} {$command} {$subcommand} {$string_options} ");
            }
        }
    }

    public function menuCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments as $subcommand => $options) {
            $shell_command = "{$wpcli} {$command} ";
            if (is_array($options)) {
                $shell_command .= $this->prepareSubCommand($subcommand, $options, ' ');
            } else {
                $shell_command .= " $subcommand $options ";
            }
            $this->addCommand($shell_command);
        }
    }

    public function install_coreCommandManager($wpcli, $command, $arguments)
    {
        $this->coreCommandManager($wpcli, 'core', $arguments);
    }

    public function dbCommandManager($wpcli, $command, $arguments)
    {
        $this->coreCommandManager($wpcli, $command, $arguments);
    }

    public function coreCommandManager($wpcli, $command, $arguments)
    {
        foreach ($arguments as $subcommand => $options) {
            $shell_command = "{$wpcli} {$command} ";
            if (is_array($options)) {
                $shell_command .= $this->prepareSubCommand($subcommand, $options);
            } else {
                $shell_command .= " $subcommand $options ";
            }
            $this->addCommand($shell_command);
        }
    }

    public function getFile()
    {
        return $this->_file;
    }

    private function setIfDebugMode()
    {
        global $argv;
        $this->_debug = false;
        foreach ($argv as $arg) {
            if ($arg === '--debug') {
                $this->_debug = true;
            }
        }
    }
}

try {
    if (isset($argv[1])) {
        $wp = new WPCLI_WPInstaller($argv[1]);
        $wp->init();
    } else {
        throw new Exception('Por favor, pásame la masa para cocinar los churros');
    }
} catch (Exception $e) {
    echo $e->getMessage();
}


