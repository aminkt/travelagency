<?php

namespace common\models\search;

use common\models\Category;
use common\models\Developer;
use common\models\Game;
use common\models\Publisher;
use common\models\schemas\SystemRequirementSchema;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Class GameSearch
 * Search in game model.
 *
 * @package common\models\search
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class GameSearch extends Game
{
    /** @var string $developer Developer name to search games based on that. */
    public $developer;
    /** @var string $publisher Publisher name to search games based on that. */
    public $publisher;
    /** @var string $platform Platform name to search games based on that. */
    public $platform;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'developer', 'publisher', 'categories', 'tags'], 'string'],
            [['status'], 'in', 'range' => [static::STATUS_PUBLISH, static::STATUS_DRAFT]],
            [['platform'], 'in', 'range' => [
                SystemRequirementSchema::CONSOLE_XBOX_ONE,
                SystemRequirementSchema::CONSOLE_XBOX_360,
                SystemRequirementSchema::CONSOLE_PS4,
                SystemRequirementSchema::CONSOLE_PS3,
                SystemRequirementSchema::CONSOLE_PS2,
            ]],
        ];
    }

    /**
     * Search in games and filter the results.
     *
     * @param array  $params   Search params to load in model.
     * @param string $formName Form name for loading array params into model.
     *
     * @return \yii\data\ActiveDataProvider
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function search($params, $formName = '')
    {
        $query = static::find();
        $query->where(['is_deleted' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ]
        ]);

        if (!($this->load($params, $formName))) {
            return $dataProvider;
        }

        $query->andFilterWhere(['name' => ['$regex' => ".*{$this->name}.*"]]);
        $query->andFilterWhere(['status' => $this->status]);
//        $query->andFilterWhere(['system_requirements.console' => $this->platform]);

        if ($this->tags) {
            $this->tags = explode(',', $this->tags);
            $query->andFilterWhere(['tags' => ['$in' => $this->tags]]);
        }

//        if ($this->developer) {
//            $developers = Developer::find()
//                ->where(['name' => ['$regex' => ".*{$this->developer}.*"]])
//                ->select(['_id'])
//                ->asArray()
//                ->all();
//            if ($developers) {
//                $query->andFilterWhere(['developer_id' => ['$in' => $developers]]);
//            }
//        }
//
//        if ($this->publisher) {
//            $developers = Developer::find()
//                ->where(['name' => ['$regex' => ".*{$this->publisher}.*"]])
//                ->select(['_id'])
//                ->asArray()
//                ->all();
//            if ($developers) {
//                $query->andFilterWhere(['developer_id' => ['$in' => $developers]]);
//            }
//        }
//
//        if ($this->categories) {
//            $category = Category::find()
//                ->where(['name' => ['$regex' => ".*{$this->categories}.*"]])
//                ->select(['_id'])
//                ->asArray()
//                ->all();
//
//            if ($category) {
//                $ids = ArrayHelper::getColumn($category, '_id', false);
//                $query->andFilterWhere(['publisher_id' => ['$in' => $ids]]);
//            }
//        }

        return $dataProvider;
    }
}