<?php

namespace Kanboard\Plugin\UpdateBoardDate;

use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\UpdateBoardDate\Action\TaskUpdateBoardDate;

class Plugin extends Base
{
    public function initialize()
    {
        $this->actionManager->register(new TaskUpdateBoardDate($this->container));
    }
}
