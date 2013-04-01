<?php

/**
 * This is the model class for table "budget".
 *
 * The followings are the available columns in table 'budget':
 * @property integer $id
 * @property integer $parent
 * @property integer $year
 * @property string $csv_id
 * @property string $csv_parent_id
 * @property string $code
 * @property string $label
 * @property string $concept
 * @property string $initial_provision
 * @property string $actual_provision
 * @property string $spent_t1
 * @property string $spent_t2
 * @property string $spent_t3
 * @property string $spent_t4
 * @property integer $featured
 * @property integer $weight
 *
 * The followings are the available model relations:
 * @property Budget $parent0
 * @property Budget[] $budgets
 * @property Enquiry[] $enquiries
 */
class Budget extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Budget the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'budget';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('year, concept, initial_provision, actual_provision, spent_t1, spent_t2, spent_t3, spent_t4, featured', 'required'),
			array('parent, year, featured, weight', 'numerical', 'integerOnly'=>true),
			array('initial_provision, actual_provision, spent_t1, spent_t2, spent_t3, spent_t4', 'type', 'type'=>'float'),
			//array('initial_provision, actual_provision, spent_t1, spent_t2, spent_t3, spent_t4', 'length', 'max'=>14),
			array('code, csv_id, csv_parent_id', 'length', 'max'=>20),
			array('csv_id', 'unique', 'className' => 'Budget'),
			array('label, concept', 'length', 'max'=>255),
			array('year', 'unique', 'className'=>'Budget', 'on'=>'newYear'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent, year, code, label, concept, provision, featured, weight', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'parent0' => array(self::BELONGS_TO, 'Budget', 'parent'),
			'budgets' => array(self::HAS_MANY, 'Budget', 'parent'),
			'enquirys' => array(self::HAS_MANY, 'Enquiry', 'budget'),
		);
	}

	public function behaviors()  {
		// http://www.yiiframework.com/forum/index.php/topic/10285-how-to-compare-two-active-record-models/
		return array('PCompare'); 
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'parent' => __('Parent'),
			'csv_id' => 'internal code',
			'csv_parent_id' => 'internal parent code',
			'year' => __('Year'),
			'code' => __('Code'),
			'label' => __('Label'),
			'concept' => __('Concept'),
			'initial_provision' => __('Initial provision'),
			'actual_provision' => __('Actual provision'),
			'spent_t1' => __('Spent T1'),
			'spent_t2' => __('Spent T2'),
			'spent_t3' => __('Spent T3'),
			'spent_t4' => __('Spent T4'),
			'weight' => __('Weight'),
		);
	}

	public function publicSearch()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is null and year = '.$this->year);
		$yearly_budget=$this->find($criteria);
		if(!$yearly_budget)
			return new CActiveDataProvider($this,array('data'=>array()));
		if(!Yii::app()->user->isAdmin()){
			if($yearly_budget->code != 1)	//not published
				return new CActiveDataProvider($this,array('data'=>array()));
		}
		if(!$this->code && !$this->concept)
			return new CActiveDataProvider($this,array('data'=>array()));

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is not null');	// dont show year budget

		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('initial_provision',$this->initial_provision);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$criteria->addCondition('parent is not null');	// dont show year budget

		$criteria->compare('id',$this->id);
		$criteria->compare('parent',$this->parent);
		$criteria->compare('csv_id',$this->csv_id,true);
		$criteria->compare('csv_parent_id',$this->csv_parent_id,true);
		$criteria->compare('year',$this->year);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('initial_provision',$this->initial_provision);
		$criteria->compare('featured',$this->featured);
		$criteria->compare('weight',$this->weight);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
