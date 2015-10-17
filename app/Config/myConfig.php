<?php

/**
 * サイト全体の設定
 */
$siteNo = 1;

if ($siteNo == 0) { // 本番サーバー
    session_name('todo_app0');
    $config['database'] = 'site0';
} elseif ($siteNo == 1) { // ローカル環境(XAMMP)
    session_name('todo_app1');
    $config['database'] = 'site1';
}