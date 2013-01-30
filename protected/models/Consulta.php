<?php

/**
 * This is the model class for table "consulta".
 *
 * The followings are the available columns in table 'consulta':
 * @property integer $id
 * @property integer $user
 * @property integer $team_member
 * @property integer $manager
 * @property string $created
 * @property string $assigned
 * @property integer $type
 * @property integer $capitulo
 * @property integer $state
 * @property string $title
 * @property string $body
 *
 * The followings are the available model relations:
 * @property User $user0
 * @property User $teamMember
 * @property User $manager0
 */
class Consulta extends CActiveRecord
{

    public $humanStateValues=array(
                        0=>'Esperando respuesta de la OCAB',
                        1=>'Esperando respuesta de la Administración',
                        2=>'Respuesta con éxito',
                        3=>'Respuesta parcialmente con éxito',
                        4=>'Descartado por el OCAB',
                        5=>'Descartado por la Administración'
					);

    public $humanTypeValues=array(
                        0=>'Genérica',
                        1=>'Pressupostària');

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Consulta the static model class
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
		return 'consulta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user, created, title', 'required'),
			array('user, team_member, manager, type, capitulo, state', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('assigned, body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user, team_member, manager, created, assigned, type, capitulo, state, title, body', 'safe', 'on'=>'search'),
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
			'user0' => array(self::BELONGS_TO, 'User', 'user'),
			'teamMember' => array(self::BELONGS_TO, 'User', 'team_member'),
			'manager0' => array(self::BELONGS_TO, 'User', 'manager'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user' => 'Submitted by',
			'team_member' => 'Assigned to',
			'manager' => 'Manager',
			'created' => 'Submitted on',
			'assigned' => 'Assigned on',
			'type' => 'Type',
			'capitulo' => 'Capitulo',
			'state' => 'State',
			'title' => 'Title',
			'body' => 'Body',
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
		$criteria->compare('user',$this->user);
		$criteria->compare('team_member',$this->team_member);
		$criteria->compare('manager',$this->manager);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('assigned',$this->assigned,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('capitulo',$this->capitulo);
		$criteria->compare('state',$this->state);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('body',$this->body,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
                                