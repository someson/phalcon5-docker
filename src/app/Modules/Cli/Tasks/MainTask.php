<?php

namespace App\Modules\Cli\Tasks;

use Library\Cli\Output;
use Phalcon\Annotations\AnnotationsFactory;
use Phalcon\Cli\Task as BaseTask;

class MainTask extends BaseTask
{
    /**
     * @example $[winpty] docker-compose exec app-service php ./scripts/cli.php main main [-s] [-v] [-r]
     * @return void
     * @throws \ReflectionException
     */
    public function mainAction(): void
    {
        $annotations = (new AnnotationsFactory)->newInstance('memory', [
            'prefix'   => 'annotations',
            'lifetime' => '3600',
        ]);

        Output::text('');
        foreach (new \DirectoryIterator(APP_DIR . '/Modules/Cli/Tasks') as $file) {
            if ($file->isDot() || in_array($file->getBasename('.php'), ['MainTask', 'ErrorTask'])) {
                continue;
            }
            $task = $file->getBasename('.php');
            $reflection = new \ReflectionClass(sprintf('%s\\%s', __NAMESPACE__, $task));
            $methods = array_filter($reflection->getMethods(), static function($item) use ($task) {
                return $item->class === __NAMESPACE__ . '\\' . $task
                    && $item->name !== 'initialize'
                    && str_ends_with($item->name, 'Action');
            });

            $i = 0;
            if ($methodCount = count($methods)) {
                Output::text(strtolower(strstr($task, 'Task', true)));
                foreach ($methods as $method) {
                    $action = strstr($method->name, 'Action', true);
                    $description = '';
                    $collection = $annotations->getMethod($reflection->getName(), $method->name);
                    if ($collection->has('description')) {
                        $annotation = $collection->get('description');
                        $description = Output::COLOR_GREEN . $annotation->getArgument('short') . Output::COLOR_NONE;
                    }
                    $tree = $methodCount > ++$i ? '├─' : '└─';
                    Output::text(sprintf(' %s %s %s', $tree, $action, $description));
                }
                Output::text('');
            }
        }
    }
}
