<?php

namespace Kanboard\Plugin\UpdateBoardDate\Action;

use Kanboard\Model\TaskModel;
use Kanboard\Action\Base;

/**
 * Set the start date of task
 *
 * @package Kanboard\Action
 * @author  Zdenko Pikula
 */
class TaskUpdateBoardDate extends Base
{
    /**
     * Get automatic action description
     *
     * @access public
     * @return string
     */
    public function getDescription()
    {
        return t('Automatically update the board date');
    }

    /**
     * Get the list of compatible events
     *
     * @access public
     * @return array
     */
    public function getCompatibleEvents()
    {
        return array(
            TaskModel::EVENT_MOVE_COLUMN,
        );
    }

    /**
     * Get the required parameter for the action (defined by the user)
     *
     * @access public
     * @return array
     */
    public function getActionRequiredParameters()
    {
        return array(
            'column_id' => t('Column'),
            'period' => array (
                'daily' => T('daily'),
                'monthly' => T('monthly'),
                'yearly' => T('yearly'),
            ),
        );
    }

    /**
     * Get the required parameter for the event
     *
     * @access public
     * @return string[]
     */
    public function getEventRequiredParameters()
    {
        return array(
            'task_id',
            'task' => array(
                'project_id',
                'column_id',
            ),
        );
    }

    /**
     * Execute the action (update the task board date)
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool            True if the action was executed or false when not executed
     */
    public function doAction(array $data)
    {
        $period = $this->getParam('period');

        switch($period) {
            case 'daily':
                $date_board = strtotime('tomorrow');
                break;
            case 'monthly':
                $date_board = strtotime('first day of +1 month');
                break;
            case 'yearly':
                $date_board = mktime(0, 0, 0, 1, 1, date('Y') + 1);
                break;
        }

        $values = array(
            'id' => $data['task_id'],
            'date_board' => $date_board,
        );

        return $this->taskModificationModel->update($values, false);
    }

    /**
     * Check if the event data meet the action condition
     *
     * @access public
     * @param  array   $data   Event data dictionary
     * @return bool
     */
    public function hasRequiredCondition(array $data)
    {
        return $data['task']['column_id'] == $this->getParam('column_id');
    }
}