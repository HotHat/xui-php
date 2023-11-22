<?php

namespace App\Command;

use App\Config;

class XRun
{
    public function stats() {
        $commandLine = sprintf(
            '%s api stats -server 127.0.0.1:%s -json',
            Config::V2RAY_PATH->value,
            Config::V2RAY_PORT->value,
        );

        return $this->shell($commandLine);
    }

    public function addInbound($config) {
        $this->addOrDelInbound('add', $config);
    }

    public function delInbound($config) {
        $this->addOrDelInbound('remove', $config);
    }

    public function createConfig($name, $config) {
        $path = Config::V2RAY_CONFIG_DIR->value . $name . '.json';
        if (file_exists($path)) {
            return;
        }
        file_put_contents($path, $config);
        // TODO:
        $this->addInbound($path);
    }

    public function removeConfig($name) {
        $path = Config::V2RAY_CONFIG_DIR->value . $name . '.json';
        if (file_exists($path)) {
            // TODO:
            $this->delInbound($path);
            unlink($path);
        }
    }
    public function modifyConfig($name, $config) {
        $path = Config::V2RAY_CONFIG_DIR->value . $name . '.json';
        if (file_exists($path)) {
            // TODO:
            $this->delInbound($path);

            file_put_contents($path, $config);

            $this->addInbound($path);
        }
    }

    public function restartX() {
       shell_exec('service v2ray restart');
    }

    private function addOrDelInbound($type, $config) {
        $filePath = Config::V2RAY_CONFIG_DIR->value . $config;
        if (!file_exists($filePath)) {
            throw new \Exception($filePath. ' file not exist');
        }

        $command = '';
        if ($type == 'add') {
            $command = 'adi';
        } else if ($type == 'remove'){
            $command = 'rmi';
        }

        $commandLine = sprintf(
            '%s api %s -s 127.0.0.1:%s -format json %s',
            Config::V2RAY_PATH->value,
            $command,
            Config::V2RAY_PORT->value,
            $filePath
        );

        $this->shell($commandLine);
    }

    private function shell($command) {

        exec($command, $output, $code);

        if ($code !== 0) {
            throw new \Exception($code);
        } else {
            if (str_contains($output[0], 'failed')) {
                throw new \Exception(implode('', $output));
            }
        }

        return implode('', $output);
    }
}