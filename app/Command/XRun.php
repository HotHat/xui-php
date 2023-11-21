<?php

class XRun
{
    const V2RAY_PATH = '/usr/local/bin/v2ray';
    const V2RAY_CONFIG_DIR = '/usr/local/etc/v2ray/';
    const V2RAY_PORT = 12385;

    public function stats() {
        $commandLine = sprintf(
            '%s api stats -server 127.0.0.1:%s -json',
            self::V2RAY_PATH,
            self::V2RAY_PORT,
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
        $path = self::V2RAY_CONFIG_DIR . $name;
        if (file_exists($path)) {
            return;
        }
        file_put_contents($path, $config);
        // TODO:
        // $this->addInbound($path);
    }

    public function removeConfig($name) {
        $path = self::V2RAY_CONFIG_DIR . $name;
        if (file_exists($path)) {
            // TODO:
            // $this->delInbound($path);
            unlink($path);
        }
    }
    public function modifyConfig($name, $config) {
        $path = self::V2RAY_CONFIG_DIR . $name;
        if (file_exists($path)) {
            // TODO:
            // $this->delInbound($path);

            file_put_contents($path, $config);

            // $this->addInbound($path);
        }
    }

    public function restartX() {
       shell_exec('service v2ray restart');
    }

    private function addOrDelInbound($type, $config) {
        $filePath = self::V2RAY_CONFIG_DIR . $config;
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
            self::V2RAY_PATH,
            $command,
            self::V2RAY_PORT,
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