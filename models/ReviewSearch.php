<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReviewSearch represents the model behind the search form of `app\models\Review`.
 */
class ReviewSearch extends Review
{
    public $username;
    public $bookName;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'book_id', 'rating', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text', 'username', 'bookName'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Review::find()
            ->joinWith(['user u', 'book b']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'id',
                    'rating',
                    'status',
                    'created_at',
                    'username' => [
                        'asc' => ['u.username' => SORT_ASC],
                        'desc' => ['u.username' => SORT_DESC],
                    ],
                    'bookName' => [
                        'asc' => ['b.name' => SORT_ASC],
                        'desc' => ['b.name' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'review.id' => $this->id,
            'review.user_id' => $this->user_id,
            'review.book_id' => $this->book_id,
            'review.rating' => $this->rating,
            'review.status' => $this->status,
            'review.created_at' => $this->created_at,
            'review.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'review.text', $this->text])
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'b.name', $this->bookName]);

        return $dataProvider;
    }
}