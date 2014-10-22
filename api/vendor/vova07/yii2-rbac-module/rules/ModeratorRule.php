<?php

namespace vova07\rbac\rules;

use yii\rbac\Rule;

class ModeratorRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'moderator';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']['author_id'] == $user : false;
    }
}
