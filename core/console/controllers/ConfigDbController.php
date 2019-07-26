<?php

namespace console\controllers;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\widgets\Table;
use yii\helpers\Console;

/**
 * Class ConfigDbController
 * Config database connection command.
 *
 * @package console\controllers
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class ConfigDbController extends Controller
{
    public $host = 'mysql';
    public $database;
    public $username;
    public $password;
    public $file = 'core/common/config/conf.d/db.php';
    public $help = false;
    private $root;

    public function init()
    {
        parent::init();
        $this->root = dirname(dirname(dirname(__DIR__)));
    }

    public function options($actionID)
    {
        return ['file', 'host', 'database', 'username', 'password'];
    }

    public function optionAliases()
    {
        return [
            'f' => 'file',
            'h' => 'host',
            'd' => 'database',
            'u' => 'username',
            'p' => 'password',
        ];
    }

    /**
     * By command options this method will config database connection.
     *
     * @return integer
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionIndex()
    {
        if (
            empty($this->host) or
            empty($this->file) or
            empty($this->database) or
            empty($this->username) or
            empty($this->password)
        ) {
            $this->stderr("Please fill command inputs.\n", Console::FG_RED);
            return ExitCode::NOINPUT;
        }

        $this->createDatabase($this->host, $this->username, $this->password, $this->database);

        $file = $this->root . '/' . $this->file;

        $content = file_get_contents($file);

        $content = preg_replace('/(host=)([a-z]+)/', "\\1{$this->host}", $content);
        $content = preg_replace('/(dbname=)([a-z]+)/', "\\1{$this->database}", $content);
        $content = preg_replace('/(("|\')username("|\')\s*=>\s*)(".*"|\'.*\')/', "\\1'{$this->username}'", $content);
        $content = preg_replace('/(("|\')password("|\')\s*=>\s*)(".*"|\'.*\'|)/', "\\1'{$this->password}'", $content);

        file_put_contents($file, $content);

        $this->stdout("Database configuration completed.\n", Console::BOLD);
        echo Table::widget([
            'headers' => ['host', 'database', 'username', 'password'],
            'rows' => [
                [$this->host, $this->database, $this->username, '*****']
            ]
        ]);

        return ExitCode::OK;
    }

    public function createDatabase($host, $user, $pass, $database)
    {
        $pdo = new \PDO("mysql:host=".$host, $user, $pass);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $dbname = "`".str_replace("`","``",$database)."`";
        $pdo->query("CREATE DATABASE $dbname CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }
}