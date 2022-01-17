<?php
/**
 * 使用场景：多进程数据共享
 */
use Swoole\Table;

// 创建内存表
$table = new Table(1024);

// 内存表增加一列
$table->column('id', Table::TYPE_INT);
$table->column('name', Table::TYPE_STRING, 64);
$table->column('age', Table::TYPE_INT);

// 创建内存表
$table->create();

$table->set('singwa_imooc', ['id'=>1, 'name'=>'singwa', 'age'=>30]);

$value = $table->get('singwa_imooc');
var_dump($value);

// 获取表格的最大行数
$getSize = $table->size;
// 获取实际占用内存的尺寸，单位为字节
$getMemorySize = $table->memorySize;

echo $getSize . PHP_EOL;
echo round(($getMemorySize / 1024)) . "KB" . PHP_EOL;