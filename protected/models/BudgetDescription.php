<?php

/**
 * This is the model class for table "budget_description".
 *
 * The followings are the available columns in table 'budget_description':
 * @property integer $id
 * @property integer $category
 * @property string $code
 * @property string $language
 * @property string $concept
 * @property string $description
 *
 * The followings are the available model relations:
 * @property BudgetCategory $category0
 */
class BudgetDescription extends CActiveRecord
{
	
	public $combination;
	
	public function getHumanLanguages($lang)
	{
		$languages=getLanguagesArray();
		if($lang)
			return $languages[$lang];
		return $languages;
	}
		
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BudgetDescription the static model class
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
		return 'budget_description';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('category, language, code, concept', 'required'),
			array('category', 'numerical', 'integerOnly'=>true),
			array('language', 'length', 'max'=>2),
			array('code', 'length', 'max'=>20),
			array('combination', 'validCombination', 'on'=>'create'),
			array('concept', 'length', 'max'=>255),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, category, code, language, concept, description', 'safe', 'on'=>'search'),
		);
	}

	public function validCombination($attribute,$params)
	{
			if($this->findByAttributes(array('category'=>$this->category,'language'=>$this->language,'code'=>$this->code))){
				$this->addError($attribute, __('Category/Language/Code combination already exists.'));
			}
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'category0' => array(self::BELONGS_TO, 'BudgetCategory', 'category'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'category' => 'Category',
			'code' => 'Public code',
			'language' => 'Language',
			'concept' => 'Concept',
			'description' => 'Description',
		);
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

		$criteria->compare('id',$this->id);
		$criteria->compare('category',$this->category);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('concept',$this->concept,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}