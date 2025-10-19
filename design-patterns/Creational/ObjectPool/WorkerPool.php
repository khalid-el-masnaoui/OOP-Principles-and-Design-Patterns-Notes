<?php

namespace DesignPatterns\Creational;

// The Worker class representing the reusable object
class Worker
{
    private $id;
    private $isBusy = false;

    public function __construct($id)
    {
        $this->id = $id;
        echo "Worker {$this->id} created.\n";
    }

    public function doWork($task)
    {
        $this->isBusy = true;
        echo "Worker {$this->id} is performing task: {$task}\n";
        // Simulate some work
        sleep(1); 
        $this->isBusy = false;
    }

    public function isBusy(): bool
    {
        return $this->isBusy;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function reset()
    {
        $this->isBusy = false;
        // Any other reset logic for the worker
    }
}

// The ObjectPool class to manage Worker objects
class WorkerPool
{
    private $availableWorkers = [];
    private $inUseWorkers = [];
    private $nextWorkerId = 1;

    public function getWorker(): Worker
    {
        if (!empty($this->availableWorkers)) {
            $worker = array_pop($this->availableWorkers);
            echo "Reusing Worker {$worker->getId()}.\n";
        } else {
            $worker = new Worker($this->nextWorkerId++);
        }
        $this->inUseWorkers[$worker->getId()] = $worker;
        return $worker;
    }

    public function releaseWorker(Worker $worker)
    {
        if (isset($this->inUseWorkers[$worker->getId()])) {
            $worker->reset();
            unset($this->inUseWorkers[$worker->getId()]);
            $this->availableWorkers[] = $worker;
            echo "Worker {$worker->getId()} released and returned to pool.\n";
        }
    }
}

// Usage example
$pool = new WorkerPool();

// Get workers and perform tasks
$worker1 = $pool->getWorker();
$worker1->doWork("Process data batch A");

$worker2 = $pool->getWorker();
$worker2->doWork("Generate report X");

// Release workers back to the pool
$pool->releaseWorker($worker1);
$pool->releaseWorker($worker2);

// Get a worker again (should reuse one from the pool)
$worker3 = $pool->getWorker();
$worker3->doWork("Analyze logs");

$pool->releaseWorker($worker3);

?>
